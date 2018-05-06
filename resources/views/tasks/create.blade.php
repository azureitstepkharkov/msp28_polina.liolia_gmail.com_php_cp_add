@extends('layouts.app')

@section('title', '| Add Task')

@section('content')
    @guest
    @include('auth.login')
    @else
        <div class='col-lg-4 col-lg-offset-4'>

            <h1><i class='fa fa-key'></i> {{__('Add Task')}}</h1>
            <hr>

            {{ Form::open(array('url' => 'tasks')) }}

            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('start', 'Starts') }}
                {{ Form::date('start', \Carbon\Carbon::now()) }}
            </div>
            <div class="form-group">
                {{ Form::label('end', 'Ends') }}
                {{ Form::date('end', \Carbon\Carbon::now()) }}
            </div>
            @if($project_id === null)
                <div class="form-group">
                    {{ Form::label('project_id', 'Project') }}
                    {{ Form::select('project_id', $projects)}}
                </div>
            @else
                {{ Form::hidden('project_id', $project_id)}}
            @endif

            <div class="form-group">
                {{ Form::label('status', 'Status') }}
                {{ Form::select('status', $statuses)}}
            </div>
            <br>
            @if (Auth::user()->hasRole('Client'))
                <div class='form-group' style="display: none; visibility: hidden">
                    {{ Form::checkbox('users',  Auth::user()->id, true ) }}
                </div>
            @endif
            @if(Auth::User()->can("add_task") || Auth::User()->hasRole("Admin"))
            {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
            @else
                <div>You have no permissions to create a new Task.</div>
            @endif
            {{ Form::close() }}

        </div>
        @endguest
@endsection