@extends('layouts.app')

@section('title', '| Edit Technology')

@section('content')
    @guest
        @include('auth.login')
    @else
    <div class='col-lg-4 col-lg-offset-4'>
        <h1 class="page_header">{{__('Edit Technology')}}: {{$technology->name}}</h1>
        <hr>

        {{ Form::model($technology, array('route' => array('technologies.update', $technology->id), 'method' => 'PUT')) }}

        <div class="form-group">
            {{ Form::label('name', 'Technology') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', null, array('class' => 'form-control')) }}
        </div>
        <br>
        @if(Auth::User()->hasRole("Admin"))
        {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}
        @else
            <div>Only Admin can edit Technology.</div>
        @endif
        {{ Form::close() }}
    </div>
    @endguest
@endsection