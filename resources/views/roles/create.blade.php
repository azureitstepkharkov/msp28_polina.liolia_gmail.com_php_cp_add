@extends('layouts.app')

@section('title', '| Add Role')

@section('content')
    @guest
        @include('auth.login')
    @else
    <div class='col-lg-4 col-lg-offset-4'>

        <h1 class="page_header">Add Role</h1>
        <hr>

        {{ Form::open(array('url' => 'roles')) }}

        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>

        <h5><b>Assign Permissions</b></h5>

        <div class='form-group'>
            @foreach ($permissions as $permission)
                {{ Form::checkbox('permissions[]',  $permission->id, null, ['id'=>$permission->name]) }}
                {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>

            @endforeach
        </div>

        @if(Auth::User()->hasRole("Admin"))
        {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
        @else
            <div>Only Admin can add a new Role.</div>
        @endif
        {{ Form::close() }}

    </div>
    @endguest
@endsection