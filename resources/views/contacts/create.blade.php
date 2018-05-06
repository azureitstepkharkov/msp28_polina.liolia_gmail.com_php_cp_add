@extends('layouts.app')

@section('title', '| Add Contact')

@section('content')
    @guest
    @include('auth.login')
    @else
        <div class='col-lg-4 col-lg-offset-4'>

            <h1><i class='fa fa-key'></i> {{__('Add contact to ' . $user->name)}} </h1>
            <hr>

            {{ Form::open(array('url' => 'contacts')) }}
            {{ Form::hidden('user_id', $user->id)}}
            <div>
                <span class="form-group" id="contact_types_select">
                    {{ Form::label('type_id', 'Type') }}
                    {{ Form::select('type_id', $contact_types)}}
                </span>
                @if(Auth::User()->hasRole('Admin') || Auth::User()->can('add_contact_type'))
                <a href="" class="btn-xs btn-success pull-right" id="add_contact_type">New type</a>
                <a href="" class="btn-xs btn-warning pull-right" id="cancel_contact_type_creation">Cancel type creation</a>
                @endif
            </div>
            <div class="form-group" id="new_contact_type_input">
                {{ Form::label('new_contact_type', 'New type name') }}
                {{ Form::text('new_contact_type', null, array('class' => 'form-control')) }}
            </div>
            <br>

            <div class="form-group">
                {{ Form::label('value', 'Value') }}
                {{ Form::text('value', null, array('class' => 'form-control')) }}
            </div>
            @if(Auth::User()->hasRole('Admin') || Auth::User()->can('edit_user') ||
            $user->hasRole('Programmer') && Auth::User()->can('edit_programmer') ||
            $user->hasRole('Client') && Auth::User()->can('edit_client'))
            {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

            {{ Form::close() }}
            @endif
        </div>
        @endguest
@endsection