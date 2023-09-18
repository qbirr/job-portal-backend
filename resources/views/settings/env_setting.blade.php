@extends('settings.index')
@section('title')
    {{ __('messages.env') }}
@endsection
@section('section')
    {{ Form::open(['route' => 'settings.update', 'id' => 'envUpdateForm']) }}
    {{ Form::hidden('sectionName', $sectionName) }}
    <div class="row mt-3">
        {{--        <div class="col-md-12 d-flex justify-content-end">--}}
        {{--            <label class="custom-switch mt-2">--}}
        {{--                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input form-check-input" id="enableEdit">--}}
        {{--                <span class="custom-switch-indicator"></span>--}}
        {{--                <span class="custom-switch-description fs-6 fw-bolder text-gray-700 mb-3 mt-5"--}}
        {{--                      id="envUpdateText">{{ __('messages.setting.enable_edit') }}</span>--}}
        {{--            </label>--}}
        {{--        </div>--}}
        <div class="col-sm-6 mb-5">
            {{ Form::label('status', __('messages.setting.enable_edit'), ['class' => 'form-label mt-5']) }}
            <div class="form-check form-switch">
                <input class="form-check-input " name="custom-switch-checkbox" id="enableEdit"
                       type="checkbox">
                <span class="custom-switch-indicator"></span>
            </div>
        </div>
        <div class="col-sm-12 my-0">
            <div class="card">
                <h5>{{ __('messages.setting.mail') }} :</h5>
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('mail_mailer', __('messages.setting.mail_mailer').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('mail_mailer', (empty($mail['MAIL_MAILER'])) ? '' : $mail['MAIL_MAILER'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.mail_mailer')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('mail_host', __('messages.setting.mail_host').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('mail_host', (empty($mail['MAIL_HOST'])) ? '' : $mail['MAIL_HOST'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.mail_host')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('mail_port', __('messages.setting.mail_port').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('mail_port', (empty($mail['MAIL_PORT'])) ? '' : $mail['MAIL_PORT'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.mail_port')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('mail_username', __('messages.setting.mail_username').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('mail_username', (empty($mail['MAIL_USERNAME'])) ? '' : $mail['MAIL_USERNAME'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.mail_username')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('mail_password', __('messages.setting.mail_password').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('mail_password', (empty($mail['MAIL_PASSWORD'])) ? '' : $mail['MAIL_PASSWORD'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.mail_password')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('mail_from_address', __('messages.setting.mail__from_address').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('mail_from_address', (empty($mail['MAIL_FROM_ADDRESS'])) ? '' : $mail['MAIL_FROM_ADDRESS'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.mail__from_address')]) }}
                    </div>
                </div>
            </div>
            <div class="card">
                <h5 class="mt-5">{{ __('messages.setting.facebook') }} :</h5>
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('facebook_app_id', __('messages.setting.facebook_app_id').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('facebook_app_id', (empty($facebook['FACEBOOK_APP_ID'])) ? '' : $facebook['FACEBOOK_APP_ID'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.facebook_app_id')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('facebook_app_secret', __('messages.setting.facebook_app_secret').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('facebook_app_secret', (empty($facebook['FACEBOOK_APP_SECRET'])) ? '' : $facebook['FACEBOOK_APP_SECRET'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.facebook_app_secret')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('facebook_redirect', __('messages.setting.facebook_redirect').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('facebook_redirect', (empty($facebook['FACEBOOK_REDIRECT'])) ? '' : $facebook['FACEBOOK_REDIRECT'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.facebook_redirect')]) }}
                    </div>
                </div>
            </div>
            <div class="card">
                <h5 class="mt-5">{{ __('messages.setting.pusher') }} :</h5>
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('pusher_app_id', __('messages.setting.pusher_app_id').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('pusher_app_id', (empty($pusher['PUSHER_APP_ID'])) ? '' : $pusher['PUSHER_APP_ID'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.pusher_app_id')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('pusher_app_key', __('messages.setting.pusher_app_key').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('pusher_app_key', (empty($pusher['PUSHER_APP_KEY'])) ? '' : $pusher['PUSHER_APP_KEY'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.pusher_app_key')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('pusher_app_secret', __('messages.setting.pusher_app_secret').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('pusher_app_secret', (empty($pusher['PUSHER_APP_SECRET'])) ? '' : $pusher['PUSHER_APP_SECRET'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.pusher_app_secret')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('pusher_app_cluster', __('messages.setting.pusher_app_cluster').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('pusher_app_cluster', (empty($pusher['PUSHER_APP_CLUSTER'])) ? '' : $pusher['PUSHER_APP_CLUSTER'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.pusher_app_cluster')]) }}
                    </div>
                </div>
            </div>
            <div class="card">
                <h5 class="mt-5">{{ __('messages.setting.stripe') }} :</h5>
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('stripe_key', __('messages.setting.stripe_key').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('stripe_key', (empty($stripe['STRIPE_KEY'])) ? '' : $stripe['STRIPE_KEY'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.stripe_key')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('stripe_secret', __('messages.setting.stripe_secret_key').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('stripe_secret', (empty($stripe['STRIPE_SECRET'])) ? '' : $stripe['STRIPE_SECRET'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.stripe_secret_key')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('stripe_webhook_key', __('messages.setting.stripe_webhook_key').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('stripe_webhook_key', (empty($stripe['STRIPE_WEBHOOK_SECRET_KEY'])) ? '' : $stripe['STRIPE_WEBHOOK_SECRET_KEY'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.stripe_webhook_key')]) }}
                    </div>
                </div>
            </div>
            <div class="card">
                <h5 class="mt-5">{{ __('messages.setting.paypal') }} :</h5>
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('paypal_client_id', __('messages.setting.paypal_client_id').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('paypal_client_id', (empty($paypal['PAYPAL_CLIENT_ID'])) ? '' : $paypal['PAYPAL_CLIENT_ID'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.paypal_client_id')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('paypal_secret', __('messages.setting.paypal_secret').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('paypal_secret', (empty($paypal['PAYPAL_SECRET'])) ? '' : $paypal['PAYPAL_SECRET'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.paypal_secret')]) }}
                    </div>
                </div>
            </div>
            <div class="card">
                <h5 class="mt-5">{{__('messages.setting.linkedin') }} :</h5>
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('linkedin_client_id', __('messages.setting.linkedin_client_id').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('linkedin_client_id', (empty($linkedin['LINKEDIN_CLIENT_ID'])) ? '' : $linkedin['LINKEDIN_CLIENT_ID'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.linkedin_client_id')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('linkedin_client_secret', __('messages.setting.linkedin_client_secret').':', ['class' => 'form-label mt-5']) }}

                        {{ Form::text('linkedin_client_secret', (empty($linkedin['LINKEDIN_CLIENT_SECRET'])) ? '' : $linkedin['LINKEDIN_CLIENT_SECRET'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.linkedin_client_secret')]) }}
                    </div>
                </div>
            </div>
            <div class="card">
                <h5 class="mt-5">{{ __('messages.setting.google') }} :</h5>
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::label('google_client_id', __('messages.setting.google_client_id').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('google_client_id', (empty($google['GOOGLE_CLIENT_ID'])) ? '' : $google['GOOGLE_CLIENT_ID'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.google_client_id')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('google_client_secret', __('messages.setting.google_client_secret').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('google_client_secret', (empty($google['GOOGLE_CLIENT_SECRET'])) ? '' : $google['GOOGLE_CLIENT_SECRET'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.google_client_secret')]) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('google_redirect', __('messages.setting.google_redirect').':', ['class' => 'form-label mt-5']) }}
                        {{ Form::text('google_redirect', (empty($google['GOOGLE_REDIRECT'])) ? '' : $google['GOOGLE_REDIRECT'], ['class' => 'form-control', 'disabled', 'placeholder' => __('messages.setting.google_redirect')]) }}
                    </div>
                </div>
            </div>
            <div class="card">
                <h5 class="mt-5">{{ __('messages.setting.cookie') }} :</h5>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="mt-2 pl-0 form-check form-switch">
                            {{--                            <input type="checkbox" name="cookie_consent_enabled" class="custom-switch-input form-check-input"--}}
                            {{--                                   id="enableCookie"--}}
                            {{--                                   {{ (!empty($cookie['COOKIE_CONSENT_ENABLED']) && filter_var($cookie['COOKIE_CONSENT_ENABLED'], FILTER_VALIDATE_BOOLEAN)) ? 'checked' : '' }} disabled>--}}
                            {{--                            --}}
                            <input class="form-check-input mr-5" name="cookie_consent_enabled"
                                   id="enableCookie" type="checkbox"
                                   {{ (!empty($cookie['COOKIE_CONSENT_ENABLED']) && filter_var($cookie['COOKIE_CONSENT_ENABLED'], FILTER_VALIDATE_BOOLEAN)) ? 'checked' : '' }} disabled>
                            <span class=""></span>
                            <span class="fw-bolder text-gray-700 ms-3"
                                  id="enableCookieText">
                                @if(!empty($cookie['COOKIE_CONSENT_ENABLED']) && filter_var($cookie['COOKIE_CONSENT_ENABLED'], FILTER_VALIDATE_BOOLEAN))
                                    {{ __('messages.setting.disable_cookie') }}
                                @else
                                    {{ __('messages.setting.enable_cookie') }}
                                @endif
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-5 mt-5">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit','class' => 'btn btn-primary me-3','id' => 'btnSaveEnvData', 'disabled']) }}
                <a href="{{ route('settings.index', ['section' => 'env_setting']) }}"
                   class="btn btn-secondary me-2">{{__('messages.common.cancel')}}</a>
            </div>
        </div>
    {{ Form::close() }}
@endsection

