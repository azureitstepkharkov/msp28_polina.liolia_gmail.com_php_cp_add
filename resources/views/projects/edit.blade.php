@extends('layouts.app')

@section('title', '| Edit Project')

@section('content')
    @guest
        @include('auth.login')
    @else
        <div class='col-lg-4 col-lg-offset-4'>

            <h1 class="page_header">{{__('Edit Project')}}</h1>
            <hr>

            {{ Form::model($project, array('route' => ['projects.update', $project->id], 'method'=>'PUT')) }}

            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('project_manager_id', 'Project manager') }}
                {{ Form::select('project_manager_id', $PMs)}}
            </div>
            <div class="form-group">
                {{ Form::label('client_id', 'Client') }}
                {{ Form::select('client_id', $clients)}}
            </div>
            <h5><b>Assign Technologies:</b></h5>
            <div class='form-group'>
                @foreach ($technologies as $technology)
                    {{ Form::checkbox('technologies[]',  $technology->id, null, ['id'=>$technology->name]) }}
                    {{ Form::label($technology->name, ucfirst($technology->name)) }}<br>
                @endforeach
            </div>
            <div class="form-group">
                {{ Form::label('status', 'Status') }}
                {{ Form::select('status', $statuses)}}
            </div>
            @if(Auth::User()->can("edit_project") || Auth::User()->hasRole("Admin"))
            {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}
            @else
                <div>You have no permissions to edit projects.</div>
            @endif
            {{ Form::close() }}

        </div>
        @endguest
@endsection