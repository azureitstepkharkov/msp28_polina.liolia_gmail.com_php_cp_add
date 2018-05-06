@extends('layouts.app')

@section('title', '| '.__("Edit User"))
@section('content')
    @guest
    @include('auth.login')
    @else
        <div class='col-lg-4 col-lg-offset-4'>
            <h3 class="page_header">{{__("Edit")}} {{$user->name}}</h3>
            <hr>

            {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with user data --}}

            <div class="form-group">
                {{ Form::label('name', __('Name')) }}
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>

            <div class="form-group">
                {{ Form::label('email', __('Email')) }}
                {{ Form::email('email', null, array('class' => 'form-control')) }}
            </div>
            @ability('Admin', 'adminperm')
            <h5><b>{{__("Give Role")}}</b></h5>

            <div class='form-group'>
                @foreach ($roles as $role)
                    {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
                    {{ Form::label($role->name, ucfirst($role->name)) }}<br>

                @endforeach
            </div>
            <h5><b></b></h5>

            <div class='form-group'>
                @if(isset($user->roles()->pluck('name')[0]) && (($user->roles()->pluck('name')[0] === 'Programmer') ||
                ($user->roles()->pluck('name')[0] === 'ProjectMan') || ($user->roles()->pluck('name')[0] === 'TeamLeader')))
                    <h5><b>{{__("Assign Technology")}}</b></h5>

                    <div class='form-group'>

                        @foreach ($technologies as $technology)
                            {{ Form::checkbox('technologies[]',  $technology->id, $user->technologies ) }}
                            {{ Form::label($technology->name, ucfirst($technology->name)) }}<br>

                        @endforeach
                        @endif
                    </div>
                    @endability
                    @if(Auth::user()->hasRole('ProjectMan') || Auth::user()->hasRole('TeamLeader') || Auth::user()->hasRole('Programmer') || Auth::user()->hasRole('Client'))
                        <div class='form-group' style="display: none; visibility: hidden">
                            @foreach ($roles as $role)
                                {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
                            @endforeach
                        </div>
                    @endif
                    <div class="form-group">
                        {{ Form::label('password', __('Password')) }}<br>
                        {{ Form::password('password', array('class' => 'form-control')) }}

                    </div>

                    <div class="form-group">
                        {{ Form::label('password', __('Confirm Password')) }}<br>
                        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

                    </div>
                    @if(Auth::User()->hasRole("Admin"))
                        {{ Form::submit(__('Edit'), array('class' => 'btn btn-primary')) }}
                    @else
                        <div>Only Admin can edit User's data.</div>
                    @endif
                    {{ Form::close() }}
            </div>
        </div>
        @endguest
@endsection