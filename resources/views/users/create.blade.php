@extends('layouts.app')

@section('title', '| Add User')

@section('content')
    @guest
        @include('auth.login')
    @else
    <div class='col-lg-4 col-lg-offset-4'>

        <h1 class="page_header"> Add User</h1>
        <hr>

        {{ Form::open(array('url' => 'users')) }}

        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', '', array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('email', 'Email') }}
            {{ Form::email('email', '', array('class' => 'form-control')) }}
        </div>

        <div class='form-group'>
            @foreach ($roles as $role)
                @if($role->name == 'ProjectMan' || $role->name == 'Programmer' || $role->name == 'TeamLeader')
                    {{ Form::checkbox('roles[]',  $role->id, null, array('class' => 'programmable_role') ) }}
                @else
                    {{ Form::checkbox('roles[]',  $role->id ) }}
                @endif
                {{ Form::label($role->name, ucfirst($role->name)) }}<br>

            @endforeach
        </div>

        <div class='form-group tech_list'>
            @foreach ($technologies as $technology)
                {{ Form::checkbox('technologies[]',  $technology->id ) }}
                {{ Form::label($technology->name, ucfirst($technology->name)) }}<br>
            @endforeach
        </div>

        <div class="form-group">
            {{ Form::label('password', 'Password') }}<br>
            {{ Form::password('password', array('class' => 'form-control')) }}

        </div>

        <div class="form-group">
            {{ Form::label('password', 'Confirm Password') }}<br>
            {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

        </div>
        @if(Auth::User()->hasRole("Admin"))
        {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
        @else
            <div>Only Admin can add a new User.</div>
        @endif
        {{ Form::close() }}

    </div>
    @endguest

@endsection