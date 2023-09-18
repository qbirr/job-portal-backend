<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Models\SalaryCurrency;
use App\Repositories\PlanRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;

class PlanController extends AppBaseController
{
    /** @var*/
    private $planRepository;

    public function __construct(PlanRepository $PlanRepo)
    {
        $this->planRepository = $PlanRepo;
    }

    /**
     * Display a listing of the Plan.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        $currency = SalaryCurrency::toBase()->orderBy('id')->pluck('currency_name', 'id')->toArray();
        $currencyIcon = SalaryCurrency::toBase()->orderBy('id')->pluck('currency_icon', 'id')->toArray();

        return view('plans.index', compact('currency', 'currencyIcon'));
    }

    /**
     * Store a newly created Plan in storage.
     *
     * @param  CreatePlanRequest  $request
     * @return JsonResource
     */
    public function store(CreatePlanRequest $request)
    {
        try {
            $input = $request->all();
            /** @var PlanRepository $planRepo */
            $planRepo = app(PlanRepository::class);
            $planRepo->createPlan($input);

            return $this->sendSuccess(__('messages.flash.plan_Save'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Plan  $plan
     * @return JsonResponse
     */
    public function edit(Plan $plan)
    {
        if ($plan->salary_currency_id == 0) {
            $salaryCurrencyId = SalaryCurrency::whereCurrencyName('USD US Dollar')->first()->id;
            $plan->salary_currency_id = $salaryCurrencyId;
        }

        return $this->sendResponse($plan, __('messages.flash.plan_retrieve'));
    }

    /**
     * Show the form for editing the specified Plan.
     *
     * @param  Plan  $plan
     * @return JsonResource
     */
    public function show(Plan $plan)
    {
        return $this->sendResponse($plan, __('messages.flash.plan_retrieve'));
    }

    /**
     * Update the specified Plan in storage.
     *
     * @param  UpdatePlanRequest  $request
     * @param  Plan  $plan
     * @return JsonResource
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        try {
            $input = $request->all();
            /** @var PlanRepository $planRepo */
            $planRepo = app(PlanRepository::class);
            $updatePlan = $planRepo->updatePlan($input, $plan);
            if (! $updatePlan) {
                return $this->sendError(__('messages.flash.plan_cant_update'));
            }

            return $this->sendSuccess(__('messages.flash.plan_update'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified Plan from storage.
     *
     * @param  Plan  $plan
     * @return JsonResource
     *
     * @throws Exception
     */
    public function destroy(Plan $plan)
    {
        if ($plan->activeSubscriptions->count() > 0) {
            return $this->sendError(__('messages.flash.plan_cant_delete'));
        }

        try {
            /** @var PlanRepository $planRepo */
            $planRepo = app(PlanRepository::class);
            $planRepo->deletePlan($plan);

            return $this->sendSuccess(__('messages.flash.plan_delete'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @param  Plan  $plan
     * @return mixed
     */
    public function changeTrialPlan(Plan $plan)
    {
        Plan::where('is_trial_plan', true)->update([
            'is_trial_plan' => false,
        ]);
        $plan->update([
            'is_trial_plan' => true,
        ]);

        return $this->sendSuccess(__('messages.flash.trial_plan_update'));
    }
}
