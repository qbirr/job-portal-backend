@extends('employer.layouts.app')
@section('title')
    {{ __('messages.employer_menu.manage_subscriptions') }}
@endsection
@section('content')
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-12">
                <form method="post" action="{{ route('manually-payment.create', $plan->id) }}">
                    @csrf
                    <div class="">
                        <label for="bank" class="form-label">Bank:</label>
                        <span class="required"></span>
                        <select id="bank" name="bank_id" class="form-select" data-control="select2" required onclick="">
                            <option selected>Select a Bank</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-end mt-5">
                        {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'name' => 'save', 'class' => 'btn btn-primary me-3','id' => 'transferSaveBtn','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                        <a href="{{ route('payment-method-screen', $plan->id) }}"
                           class="btn btn-secondary me-2">{{__('messages.common.cancel')}}</a>
                    </div>
                </form>

                <div id="card-bank" hidden="" class="job-card card py-30">
                    <div class="row d-flex justify-content-lg-between">
                        <div class="col-12 mt-3">
                            <p class="details-page-card-text mb-0" >
                                Nominal
                            </p>
                            <p class="fs-14" id="nominal">
                                <span class="fw-bolder">
                                    {{ empty($plan->salaryCurrency->currency_icon)?'$':$plan->salaryCurrency->currency_icon }}{{ $plan->amount }}
                                </span>
                            </p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="details-page-card-text mb-0" >
                                @lang('messages.banks')
                            </p>
                            <p class="fw-bolder fs-14" id="name"></p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="details-page-card-text mb-0" >
                                @lang('messages.bank.acc_no')
                            </p>
                            <p class="fw-bolder fs-14" id="acc_no"></p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="details-page-card-text mb-0" >
                                @lang('messages.bank.acc_name')
                            </p>
                            <p class="fw-bolder fs-14" id="acc_name"></p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="details-page-card-text mb-0" >
                                @lang('messages.bank.swift_code')
                            </p>
                            <p class="fw-bolder fs-14" id="swift_code"></p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="details-page-card-text mb-0" >
                                @lang('messages.bank.notes')
                            </p>
                            <p class="fw-bolder fs-14" id="notes"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <img src="{{ asset('assets/img/payment.png') }}" class="img-fluid" alt="payment">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        let banks = {!! $banks !!};
        let showDetail = id => {
            let bank = banks.find(bank => bank.id == id)
            $('#card-bank').prop('hidden', false)
            $('#name').text(bank.name)
            $('#acc_no').text(bank.acc_no)
            $('#acc_name').text(bank.acc_name)
            $('#swift_code').text(bank.swift_code)
            $('#notes').html(bank.notes)
        }

        $(_ => {
            $('#bank').on('select2:select', function (e) {
                console.log(e.params.data)
                showDetail(e.params.data.id)
            })
        })
    </script>
@endpush
