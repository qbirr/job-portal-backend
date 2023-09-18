@extends('layouts.app')
@section('title')
    {{ __('messages.jobs') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:job-table/>
        </div>
    </div>
@endsection
{{--@push('scripts')--}}
{{--    <script src="{{mix('assets/js/jobs/job_datatable_admin.js')}}"></script>--}}
{{--@endpush--}}

