@extends('layouts.app')
@section('title')
    {{ __('messages.banks') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:bank-table/>
        </div>
    </div>
    @include('banks.add_modal')
    @include('banks.edit_modal')
    {{Form::hidden('bank-notes',__('messages.bank.notes'),['id' => 'bankNotes'])}}
@endsection
@push('scripts')
@endpush
