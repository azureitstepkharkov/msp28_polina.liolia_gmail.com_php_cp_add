@extends('layouts.app')

@section('title', '| Edit Contact')

@section('content')
    @guest
    @include('auth.login')
    @else
        <div class='col-lg-4 col-lg-offset-4'>
            <h1><i class='fa fa-key'></i> Edit Contact: {{$contact->value}}</h1>
            <hr>

            {{ Form::model($contact, array('route' => array('contacts.update', $contact->id), 'method' => 'PUT')) }}
            <div class="form-group">
                {{ Form::label('type_id', 'Type') }}
                {{ Form::select('type_id', $contact_types)}}
            </div>
            <div class="form-group">
                {{ Form::label('value', 'Value') }}
                {{ Form::text('value', null, array('class' => 'form-control')) }}
            </div>
            @if(Auth::User()->hasRole('Admin') || Auth::User()->can('edit_user') ||
            $user->hasRole('Programmer') && Auth::User()->can('edit_programmer') ||
            $user->hasRole('Client') && Auth::User()->can('edit_client'))
            {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}
            @endif
        </div>
        @endguest
@endsection