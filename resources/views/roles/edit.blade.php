@extends('layouts.app')

@section('title', '| Edit Role')

@section('content')
    @guest
        @include('auth.login')
    @else
    <div class='col-lg-4 col-lg-offset-4'>
        <h1 class="page_header">Edit Role: {{$role->name}}</h1>
        <hr>

        {{ Form::model($role, array('route' => array('roles.update', $role->id), 'method' => 'PUT')) }}

        <div class="form-group">
            {{ Form::label('name', 'Role Name') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>

        <h5><b>Assign Permissions</b></h5>

        @foreach ($permissions as $permission)

            {{Form::checkbox('permissions[]',  $permission->id, $role->permissions, ['id'=>$permission->name]) }}
            {{Form::label($permission->name, ucfirst($permission->name)) }}<br>

        @endforeach
        <br>
        @if(Auth::User()->hasRole("Admin"))
        {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}
        @else
            <div>Only Admin can edit a Role.</div>
        @endif
        {{ Form::close() }}
    </div>
    @endguest
@endsection