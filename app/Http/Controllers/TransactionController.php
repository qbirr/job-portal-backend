<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPopRequest;
use App\Models\Transaction;
use Exception;
use Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Stripe\StripeClient;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class TransactionController
 */
class TransactionController extends AppBaseController
{
    /**
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        if (Auth::user()->hasRole('Employer')) {
            return view('employer.transactions.index');
        }

        return view('transactions.index');
    }

    /**
     * @param  string  $invoiceId
     * @return mixed
     *
     * @throws Exception
     */
    public function getTransactionInvoice($invoiceId)
    {
        try {
            setStripeApiKey();
            $stripe = new StripeClient(
                config('services.stripe.secret_key')
            );

            $invoice = $stripe->invoices->retrieve(
                $invoiceId,
                []
            );

            $charge = $stripe->charges->retrieve($invoice->charge);
            $receiptUrl = $charge->receipt_url;

            return $this->sendResponse($receiptUrl, __('messages.flash.invoice_retrieve'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function uploadPop(UploadPopRequest $request, Transaction $transaction) {
        try {
            $fileExtension = getFileName('download', $request->file('file'));
            $transaction->addMedia($request->file('file'))->usingFileName($fileExtension)
                ->toMediaCollection(Transaction::POP_PATH, config('app.media_disc'));
            return $this->sendSuccess(__('messages.flash.pop_uploaded'));
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function download(Transaction $transaction) {
        Gate::allows('view', $transaction);
        return $transaction->media()->latest()->first();
    }
}
