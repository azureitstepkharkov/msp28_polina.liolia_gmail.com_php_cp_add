@extends('layouts.app')

@section('title', '| Home')
@section('content')
    @guest
    @include('auth.login')
    @else
        <div class="col-lg-12">
            <h1 class="page_header">{{__("Projects")}}</h1>
            <hr>
            @if($projects && !$projects->isEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('Project')}}</th>
                            <th>{{__('Description')}}</th>
                            <th>{{__('Client')}}</th>
                            <th>{{__('PM')}}</th>
                            <th>{{__('Technologies')}}</th>
                            <th>{{__('Status')}}</th>
                            @if (Auth::user()->hasRole('ProjectMan') || Auth::user()->hasRole('TeamLeader')
                                || Auth::user()->hasRole('Admin'))
                                <th>{{__('Operation')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td><a href="{{route("projects.show", ['id' => $project->id])}}">{{ $project->name }}</a></td>
                                <td>{{ $project->description }}</td>
                                <td>{{ $project->client->name }}</td>
                                <td>{{ $project->project_manager->name }}</td>
                                <td>{{ $project->technologies->implode('name', ', ') }}</td>
                                <td>{{ $project->status }}</td>
                                @if (Auth::user()->hasRole('ProjectMan') || Auth::user()->hasRole('TeamLeader')
                                || Auth::user()->hasRole('Admin'))
                                    <td>
                                        <a href="{{ URL::to('projects/'.$project->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                                        {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id] ]) !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Delete', array('type' => 'submit',
                                                                                                                    'class' => 'btn btn-danger',
                                                                                                                    'data-toggle'=>'confirmation',
                                                                                                                    'data-title'=>"Delete",
                                                                                                                    'data-content'=>"Delete project $project->name?",
                                                                                                                    'data-placement'=>"center",
                                                                                                                    'title'=>"")) !!}
                                        {!! Form::close() !!}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <h1 class="page_header">{{__('Tasks')}}</h1>
            <hr>
            @if($tasks && !$tasks->isEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>{{__('Task')}}</th>
                            <th>{{__('Description')}}</th>
                            <th>{{__('Project')}}</th>
                            <th>{{__('Starts')}}</th>
                            <th>{{__('Ends')}}</th>
                            <th>{{__('Status')}}</th>
                            @if (Auth::user()->hasRole('ProjectMan') || Auth::user()->hasRole('TeamLeader')
                                || Auth::user()->hasRole('Admin'))
                                <th>{{__('Stuff')}}</th>
                                <th>{{__('Operation')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tasks as $task)
                            @if($task->end < $date && ($task->status == 'new' || $task->status == 'in_progress'))
                                <tr class="overdue">
                            @else
                                <tr>
                            @endif
                                    <td><a href="{{url("tasks/$task->id")}}">{{ $task->name }}</a></td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->project->name }}</td>
                                    <td>{{ $task->start }}</td>
                                    <td>{{ $task->end }}</td>
                                    <td>{{ $task->status }}</td>
                                    @if (Auth::user()->hasRole('ProjectMan') || Auth::user()->hasRole('TeamLeader')
                                    || Auth::user()->hasRole('Admin'))
                                        <td>{{ $task->users()->pluck('name')->implode(', ')  }}</td>
                                        <td>
                                            <a href="{{ URL::to('tasks/'.$task->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['tasks.destroy', $task->id] ]) !!}
                                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Delete', array('type' => 'submit',
                                                                                                                        'class' => 'btn btn-danger',
                                                                                                                        'data-toggle'=>'confirmation',
                                                                                                                        'data-title'=>"Delete",
                                                                                                                        'data-content'=>"Delete task $task->name?",
                                                                                                                        'data-placement'=>"center",
                                                                                                                        'title'=>"")) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    @endif
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endguest
@endsection
