@extends('layouts.app')

@section('title', '| Add Technology')

@section('content')
    @guest
        @include('auth.login')
    @else
    <div class='col-lg-4 col-lg-offset-4'>

        <h1 class="page_header">{{__('Add Technology')}}</h1>
        <hr>

        {{ Form::open(array('url' => 'technologies')) }}

        <div class="form-group">
            {{ Form::label('name', 'Technology') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div><div class="form-group">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', null, array('class' => 'form-control')) }}
        </div>

        @if(Auth::User()->hasRole("Admin"))
        {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
        @else
            <div>Only Admin can add a new Technology.</div>
        @endif
        {{ Form::close() }}

    </div>
    @endguest
@endsection