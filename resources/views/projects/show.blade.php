@extends('layouts.app')

@section('title', '| Project')

@section('content')
    @guest
    @include('auth.login')
    @else
        @if(Auth::User()->can("index_project") || Auth::User()->hasRole("Admin"))
            <div class="col-lg-10 col-lg-offset-1">
                <h1 class="page_header">
                    {{__("Project ")}}<span>{{$project->name}}</span>
                    @if(Auth::User()->can("edit_project") || Auth::User()->hasRole("Admin"))
                        <a href="{{ URL::to('projects/'.$project->id.'/edit') }}"
                           class="btn btn-info pull-right" style="margin-right: 3px;">Edit</a>
                    @endif
                </h1>
                <hr>
                <h3 class="show_details">Description:</h3>
                <span>{{$project->description}}</span>
                <hr>
                <h3 class="show_details">Client:</h3>
                <span>{{$project->client->name}}</span>
                <hr>
                <h3 class="show_details">Status:</h3>
                <span class="inline_form_control">
                    {{ Form::model($project, array('route' => ['projects.change_status', $project->id], 'method'=>'PUT')) }}
                    {{ Form::select('status', $statuses)}}
                    @if(Auth::User()->can("edit_project") || Auth::User()->hasRole("Admin"))
                        {{ Form::submit('Change status', array('class' => 'btn btn-primary')) }}
                    @endif
                    {{ Form::close() }}
                </span>
                <hr>
                <h3 class="show_details">Created:</h3>
                <span>{{$project->created_at}}</span>
                <hr>
            </div>
            <span>
                <h3>Technologies:</h3>
                @if(Auth::User()->can("index_technology") || Auth::User()->hasRole("Admin"))
                    @if(!$technologies->isEmpty() && $technologies )
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('Technology')}}</th>
                                    <th>{{__('Description')}}</th>
                                    <th>{{__('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($technologies as $tech)
                                <tr>
                                    <td>{{ $tech->name }}
                                    <td>{{ $tech->description }}</td>
                                    <td>
                                        @if(Auth::User()->can("edit_project") || Auth::User()->hasRole("Admin"))
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['projects.detach_technology', $project->id, $tech->id] ]) !!}
                                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Remove', array('type' => 'submit',
                                                                                                                        'class' => 'btn btn-danger',
                                                                                                                        'data-toggle'=>'confirmation',
                                                                                                                        'data-title'=>"Remove",
                                                                                                                        'data-content'=>"Remove technology $tech->name from this project?",
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
                    @if(!empty($other_technologies))
                        {{ Form::open(['method' => 'POST','url' => "projects/$project->id/attach_technology"]) }}

                        <div class="form-group">
                        {{ Form::label('technology', 'Available technologies:') }}
                            {{ Form::select('technology', $other_technologies)}}
                            @if(Auth::User()->can("edit_project") || Auth::User()->hasRole("Admin"))
                                {{ Form::submit('Add technology', array('class' => 'btn btn-primary')) }}
                            @else
                                <div>You have no permissions to add Technology to Project.</div>
                            @endif
                            {!! Form::close() !!}
                        </div>
                    @endif
                    @else
                        <div>You have no permissions to view Technologies.</div>
                    @endif
            </span>

            <h3>Tasks:</h3>
            @if(Auth::User()->can("index_task") || Auth::User()->hasRole("Admin"))
                @if(!$tasks->isEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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
                                @if($task->project->client->id == Auth::user()->id ||
                                $task->project->project_manager_id == Auth::user()->id ||
                                 Auth::user()->hasRole('Admin'))
                                    <tr>
                                        <td><a href="{{url("tasks/$task->id")}}">{{ $task->name }}</a></td>
                                        {{--                        <td><a href="{{route("tasks.show", ['id' => $task->id])}}">{{ $task->name }}</a></td>--}}
                                        <td>{{ $task->description }}</td>
                                        <td>{{ $task->project->name }}</td>
                                        <td>{{ $task->start }}</td>
                                        <td>{{ $task->end }}</td>
                                        <td>{{ $task->status }}</td>
                                        <td>{{ $task->users()->pluck('name')->implode(', ')  }}</td>
                                        <td>
                                            @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                                                <a href="{{ route("tasks.edit", ['id' => $task->id, 'project_id' => $project->id]) }}"
                                                   class='btn btn-info pull-left' style='margin-right: 3px;'>Edit</a>
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
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <div>You have no permissions to view a Project's Tasks list.</div>
            @endif
        @else
            <div>You have no permissions to view a Project.</div>
        @endif
        @if(Auth::User()->can("add_task") || Auth::User()->hasRole("Admin"))
            <a href="{{ route("tasks.create", ['project_id' => $project->id]) }}"
               class="btn btn-success">{{__('Add Task')}}</a>
        @else
            <div>You have no permissions to add Tasks to Project.</div>
        @endif
    @endguest
@endsection