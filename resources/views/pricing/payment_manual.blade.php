@extends('employer.layouts.app')
@section('title')
    {{ __('messages.employer_menu.manage_subscriptions') }}
@endsection
@section('content')
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-12">
                <form method="post" action="">
                    @csrf
                    <div class="">
                        {{ Form::label('bank', 'Bank:', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('bank', $banks, null, ['id'=>'bankId','class' => 'form-select','placeholder' => 'Select a bank','data-control'=>'select2','required']) }}
                    </div>
                    <div class="d-flex justify-content-end mt-5">
                        {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'name' => 'save', 'class' => 'btn btn-primary me-3','id' => 'transferSaveBtn','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                        <a href="{{ route('payment-method-screen', $plan->id) }}"
                           class="btn btn-secondary me-2">{{__('messages.common.cancel')}}</a>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-12">
                <img src="{{ asset('assets/img/payment.png') }}" class="img-fluid" alt="payment">
            </div>
        </div>
    </div>

@endsection
