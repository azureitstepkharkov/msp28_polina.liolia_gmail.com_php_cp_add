@extends('layouts.app')

@section('title', '| Projects')

@section('content')
    @guest
    @include('auth.login')
    @else
        <div class="col-lg-10 col-lg-offset-1">
            <h1 class="page_header">{{__('Projects')}}
                <a href="{{ route('tasks.index') }}" class="btn btn-default pull-right">Tasks</a>
            </h1>
            <hr>
            @if(Auth::User()->can("index_project") || Auth::User()->hasRole("Admin"))
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
                                <th>{{__('Operation')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>
                                        <a href="{{route("projects.show", ['id' => $project->id])}}">{{ $project->name }}</a>
                                    </td>
                                    <td>{{ $project->description }}</td>
                                    <td>{{ $project->client->name }}</td>
                                    <td>{{ $project->project_manager->name }}</td>
                                    <td>{{ $project->technologies->implode('name', ', ') }}</td>
                                    <td>{{ $project->status }}</td>
                                    <td>
                                        @if(Auth::User()->can("edit_project") || Auth::User()->hasRole("Admin"))
                                            <a href="{{ URL::to('projects/'.$project->id.'/edit') }}"
                                               class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                                        @endif

                                        @if(Auth::User()->can("deactivate_project") || Auth::User()->hasRole("Admin"))
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id] ]) !!}
                                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Delete', array('type' => 'submit',
                                                                                                                        'class' => 'btn btn-danger',
                                                                                                                        'data-toggle'=>'confirmation',
                                                                                                                        'data-title'=>"Delete",
                                                                                                                        'data-content'=>"Delete project $project->name?",
                                                                                                                        'data-placement'=>"center",
                                                                                                                        'title'=>"")) !!}

                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <div>You have no permissions to view a Projects list.</div>
            @endif
            @if (Auth::User()->can("add_project") || Auth::User()->hasRole("Admin"))
                <a href="{{ URL::to('projects/create') }}" class="btn btn-success">{{__('Add Project')}}</a>
            @else
                <div>You have no permissions to create a new Project.</div>
            @endif
        </div>
        @endguest
@endsection
