<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\Plan as SubscriptionPlan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Laracasts\Flash\Flash;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;
use PayPalHttp\IOException;

/** All Paypal Details class **/
class PaypalController extends Controller
{
    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /** setup PayPal api context **/
//        $paypal_conf = \Config::get('paypal');
//        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'],
//            $paypal_conf['secret']));
//        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    /**
     * @param  SubscriptionPlan  $plan
     * @return JsonResponse|RedirectResponse
     *
     * @throws HttpException
     * @throws IOException
     */
    public function oneTimePayment(SubscriptionPlan $plan)
    {
        if ($plan->salaryCurrency != null && ! in_array($plan->salaryCurrency->currency_code,
                getPayPalSupportedCurrencies())) {
            Flash::error(__('messages.flash.this_currency_is'));

            return redirect()->route('manage-subscription.index');
        }
//        $currency = $plan->with('salaryCurrency')->findOrFail($plan->id);
        $clientId = config('paypal.paypal.client_id');
        $clientSecret = config('paypal.paypal.client_secret');
        $mode = config('paypal.mode');
        if ($mode == 'live') {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }

        $client = new PayPalHttpClient($environment);
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $plan->id,
                    'amount' => [
                        'value' => (int) $plan->amount,
                        'currency_code' => $plan->salaryCurrency != null ? $plan->salaryCurrency->currency_code : 'USD',
                    ],
                ],
            ],
            'application_context' => [
                'cancel_url' => URL::route('status'),
                'return_url' => URL::route('status'),
            ],
        ];

        $order = $client->execute($request);

        session(['payment_type' => request()->get('payment_type')]);

        $payment = response()->json($order);
        $payment = $payment->getData();
        foreach ($payment->result->links as $link) {
            if ($link->rel == 'approve') {
                $redirect_url = $link->href;
                break;
            }
        }

        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }

        Session::put('error', 'Unknown error occurred');

        return Redirect::route('manage-subscription.index');
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function getPaymentStatus(Request $request)
    {
        $clientId = config('paypal.paypal.client_id');
        $clientSecret = config('paypal.paypal.client_secret');
        $mode = config('paypal.paypal.mode');

        if ($mode == 'live') {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);

        // Here, OrdersCaptureRequest() creates a POST request to /v2/checkout/orders
        $request = new OrdersCaptureRequest($request->get('token'));
        $request->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($request);

            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            $planId = $response->result->purchase_units[0]->reference_id;
            $paymentAmount = $response->result->purchase_units[0]->amount->value;
            $paymentId = $response->result->id;     // $response->result->id gives the orderId of the order created above

            session()->flash('success', 'Payment success');

            /** @var User $user */
            $user = Auth::user();

            /** @var \App\Models\Plan $plan */
            $plan = SubscriptionPlan::findOrFail($planId);

            /** @var Subscription $existingSubscription */
            $existingSubscription = Subscription::NotOnTrial()
                ->whereUserId($user->id)
                ->active()
                ->first();

            // end trial subscription
            Subscription::whereUserId($user->id)->where(function (Builder $query) {
                $query->where('stripe_status', '=', 'trialing');
            })->whereNotNull('trial_ends_at')
                ->update([
                    'ends_at' => Carbon::now(),
                    'trial_ends_at' => Carbon::now(),
                ]);

            /** @var Subscription $tsSubscription */
            $tsSubscription = Subscription::create([
                'name' => $plan->name,
                'stripe_status' => 'active',
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'current_period_start' => Carbon::now(),
                'current_period_end' => Carbon::now()->addMonth(),
                'paypal_payment_id' => $paymentId,
            ]);
            $adminId = User::role('Admin')->first()->id;
            NotificationSetting::where('key', 'EMPLOYER_PURCHASE_PLAN')->first()->value == 1 ?
                addNotification([
                    Notification::EMPLOYER_PURCHASE_PLAN,
                    $adminId,
                    Notification::ADMIN,
                    $user->first_name.' '.$user->last_name.' purchase '.$plan->name,
                ]) : false;

            $transaction = (new \App\Models\Transaction())->fill([
                'user_id' => $tsSubscription->user_id,
                'owner_id' => $tsSubscription->id,
                'owner_type' => Subscription::class,
                'amount' => $paymentAmount,
                'plan_currency_id' => $plan->salary_currency_id,
            ]);

            $transaction->save();

            // if another account subscription already running than cancel it
            if ($existingSubscription && $existingSubscription->user_id === $user->id) {
                // immediately cancel old subscription from strip
                $existingSubscription->update(['ends_at' => Carbon::now()]);
            }
            Flash::success(__('messages.flash.your_payment_comp'));

            return Redirect::route('manage-subscription.index');
        } catch (HttpException $ex) {
            Flash::error('Your Payment is Cancelled');

            return Redirect::route('manage-subscription.index');
        }
    }
}
