@extends('layouts.app')

@section('title', '| Create Permission')

@section('content')
    @guest
        @include('auth.login')
    @else
    <div class='col-lg-4 col-lg-offset-4'>

        <h1 class="page_header">Add Permission</h1>
        <br>

        {{ Form::open(array('url' => 'permissions')) }}

        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', '', array('class' => 'form-control')) }}
        </div><br>
        @if(!$roles->isEmpty())
        <h4>Assign Permission to Roles</h4>

        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
        @endif
        <br>
        @if(Auth::User()->hasRole("Admin"))
            {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
        @else
            <div>Only Admin can add a new Permission.</div>
        @endif


        {{ Form::close() }}

    </div>
    @endguest

@endsection