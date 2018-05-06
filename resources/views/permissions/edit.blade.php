@extends('layouts.app')

@section('title', '| Edit Permission')

@section('content')
    @guest
        @include('auth.login')
    @else
    <div class='col-lg-4 col-lg-offset-4'>

        <h1 class="page_header"> Edit {{$permission->name}}</h1>
        <br>
        {{ Form::model($permission, array('route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}

        <div class="form-group">
            {{ Form::label('name', 'Permission Name') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>
        <br>
        @if(Auth::User()->hasRole("Admin"))
        {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}
        @else
            <div>Only Admin can edit a Permission.</div>
        @endif
        {{ Form::close() }}

    </div>
    @endguest
@endsection