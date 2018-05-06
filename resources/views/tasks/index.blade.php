@extends('layouts.app')

@section('title', '| Tasks')

@section('content')
    @guest
    @include('auth.login')
    @else
        <div class="col-lg-10 col-lg-offset-1">
            <h1 class="page_header">{{__('Tasks')}}
                <a href="{{ route('projects.index') }}" class="btn btn-default pull-right">Projects</a>
            </h1>
            <hr>
            @if(Auth::User()->can("index_task") || Auth::User()->hasRole("Admin"))
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
                                <th>{{__('Stuff')}}</th>
                                <th>{{__('Operation')}}</th>
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
                                        <td>{{ $task->users()->pluck('name')->implode(', ')  }}</td>
                                        <td>
                                            @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                                                <a href="{{ URL::to('tasks/'.$task->id.'/edit') }}"
                                                   class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                                            @endif
                                            @if(Auth::User()->can("remove_task") || Auth::User()->hasRole("Admin"))
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['tasks.destroy', $task->id] ]) !!}
                                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Delete', array('type' => 'submit',
                                                                                                                            'class' => 'btn btn-danger',
                                                                                                                            'data-toggle'=>'confirmation',
                                                                                                                            'data-title'=>"Delete",
                                                                                                                            'data-content'=>"Delete task $task->name?",
                                                                                                                            'data-placement'=>"center",
                                                                                                                            'title'=>"")) !!}
                                            @endif
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <div>You have no permissions to view a Tasks list.</div>
            @endif
            @if (Auth::User()->can("add_task") || Auth::User()->hasRole("Admin"))
                <a href="{{ URL::to('tasks/create') }}" class="btn btn-success">{{__('Add Task')}}</a>
            @else
                <div>You have no permissions to create a new Task.</div>
            @endif
        </div>
        @endguest
@endsection
