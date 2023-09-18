@extends('layouts.app')
@section('title')
    {{ __('messages.header_sliders') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/header-padding.css') }}">
@endpush
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            <div class="col-lg-9 mt-5">
                <form method="post" id="searchIsActiveHeaderSlider">
                    @csrf
                    {{--                    <div--}}
                    {{--                            class="col-lg-6 col-sm-12 form-check form-switch col-sm d-block">--}}
                    {{--                        <label class="switch-label">--}}
                    {{--                            <input type="checkbox" name="is_active"--}}
                    {{--                                   class="searchIsActiveHeaderSlider form-check-input" {{ ($settings['slider_is_active'] == 1) ? 'checked' : '' }} >--}}
                    {{--                            <span class=" switch-span"></span>--}}
                    {{--                        </label>--}}
                    {{--                        <span--}}
                    {{--                                class="pb-2 fs-5 text-gray-600 mb-3 mx-3 mb-md-0">{{ __('messages.image_slider.message') }}--}}
                    {{--                                     <i class="fas fa-question-circle ml-1"--}}
                    {{--                                        data-bs-toggle="tooltip"--}}
                    {{--                                        title="{{ __('messages.image_slider.message_title') }}"></i>--}}
                    {{--                           </span>--}}
                    {{--                    </div>--}}
                    <div class="mb-5 d-flex align-items-center">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input searchIsActiveHeaderSlider" type="checkbox"
                                   name="is_active" {{ ($settings['slider_is_active'] == 1) ? 'checked' : '' }}>
                        </div>
                        <label class="form-label fs-5 text-gray-600 me-5 mb-0 mb-1">
                            {{ __('messages.image_slider.message') }}
                            <span data-bs-toggle="tooltip"
                                  data-bs-original-title="{{ __('messages.image_slider.message_title') }}"><i
                                        class="fas fa-question-circle ml-1"></i>
                                </span>
                        </label>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex flex-column ">
            <livewire:header-slider-table/>
        </div>
        @include('header_sliders.add_modal')
        @include('header_sliders.edit_modal')
                
                {{Form::hidden('default_document_imageUrl',asset('assets/img/infyom-logo.png'),['id' => 'defaultDocumentImageUrl'])}}
                {{Form::hidden('view',__('messages.common.view'), ['id' => 'view'])}}
                {{Form::hidden('header-size-message',__('messages.header_slider.image_size_message'),['id' => 'headerSizeMessage'])}}
                {{Form::hidden('header-extension-message',__('messages.image_slider.image_extension_message'),['id' => 'headerExtensionMessage'])}}
                
            </div>
@endsection
{{--@push('scripts')--}}
{{--    <script src="{{mix('assets/js/header_sliders/header_sliders.js')}}"></script>--}}
{{--@endpush--}}
