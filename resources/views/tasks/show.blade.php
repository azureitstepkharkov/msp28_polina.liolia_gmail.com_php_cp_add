@extends('layouts.app')

@section('title', '| Task')

@section('content')
    @guest
    @include('auth.login')
    @else
        @if(Auth::User()->can("index_task") || Auth::User()->hasRole("Admin"))
            <div class="col-lg-10 col-lg-offset-1">
                <h1 class="page_header">
                    {{__("Task ")}}<span>{{$task->name}}</span>
                    @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                        <a href="{{ URL::to('tasks/'.$task->id.'/edit') }}"
                           class="btn btn-info pull-right" style="margin-right: 3px;">Edit</a>
                    @endif
                </h1>
                <hr>
                <h3 class="show_details">Project:</h3>
                <span><a href="{{route("projects.show", ['id' => $task->project->id])}}">{{$task->project->name}}</a></span>
                <hr>
                <h3 class="show_details">Description:</h3>
                <span>{{$task->description}}</span>
                <hr>
                <h3 class="show_details">Status:</h3>
                <span class="inline_form_control">
                    {{ Form::model($task, array('route' => array('tasks.change_status', $task->id), 'method' => 'PUT')) }}
                    {{ Form::select('status', $statuses)}}
                    @if(Auth::User()->can("change_task_status") || Auth::User()->hasRole("Admin"))
                        {{ Form::submit('Change status', array('class' => 'btn btn-primary')) }}
                    @endif
                    {{ Form::close() }}
                </span>
                <hr>
                <span class="col-lg-7">
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
                                    @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['tasks.detach_technology', $task->id, $tech->id] ]) !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Remove', array('type' => 'submit',
                                                                                                                    'class' => 'btn btn-danger',
                                                                                                                    'data-toggle'=>'confirmation',
                                                                                                                    'data-title'=>"Remove",
                                                                                                                    'data-content'=>"Remove technology $tech->name from this task?",
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
                        @if(!empty($project_technologies))
                            {{ Form::open(['method' => 'POST','url' => "tasks/$task->id/attach_technology"]) }}

                            <div class="form-group">
                            {{ Form::label('technology', 'Available technologies:') }}
                                {{ Form::select('technology', $project_technologies)}}
                                @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                                    {{ Form::submit('Add technology', array('class' => 'btn btn-primary')) }}
                                @endif
                </div>
                            {{ Form::close() }}
                        @endif
                    @else
                        <div>You have no permissions to view a Technologies list.</div>
                    @endif
        </span>

                <span class="col-lg-5">
            <h3>Stuff:</h3>
                    @if(!$stuff->isEmpty())
                        <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($stuff as $s)
                            <tr>
                                <td>{{ $s->name }}
                                <td>
                                     @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['tasks.detach_user', $task->id, $s->id] ]) !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Remove', array('type' => 'submit',
                                                                                                                    'class' => 'btn btn-danger',
                                                                                                                    'data-toggle'=>'confirmation',
                                                                                                                    'data-title'=>"Remove",
                                                                                                                    'data-content'=>"Remove programmer $s->name from this task?",
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
                    @if(!empty($stuff_available))
                        {{ Form::open(['method' => 'POST','url' => "tasks/$task->id/attach_user"]) }}

                        <div class="form-group">
                    {{ Form::label('stuff', 'Available stuff:') }}
                            {{ Form::select('stuff', $stuff_available)}}
                            @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                                {{ Form::submit('Add employee', array('class' => 'btn btn-primary')) }}
                            @endif
                </div>
                        {{ Form::close() }}
                    @endif
        </span>

                <span class="col-lg-12">
            <h3>Comments:</h3>
                    @if(Auth::User()->can("index_comment_task") || Auth::User()->hasRole("Admin"))
                        @if(!$comments->isEmpty())
                            <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('Created')}}</th>
                            <th>{{__('User')}}</th>
                            <th>{{__('Text')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($comments as $comment)
                            <tr>
                                <td>{{ $comment->created_at }}</td>
                                <td>{{ $comment->author->name}}</td>
                                <td>{{ $comment->comment }}
                                <td>
                                    @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['comment_task.destroy', $comment->id] ]) !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Remove', array('type' => 'submit',
                                                                                                                    'class' => 'btn btn-danger',
                                                                                                                    'data-toggle'=>'confirmation',
                                                                                                                    'data-title'=>"Remove",
                                                                                                                    'data-content'=>"Remove comment from this task?",
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
                        <div>You have no permissions to view Comments for Task.</div>
                    @endif
                    {!! Form::open(['method' => 'POST', 'route' => ['comment_task.store'] ]) !!}
                    {!! Form::textarea('comment', null, array('class' => 'form-control')) !!}
                    {!! Form::hidden('author_id', Auth::user()->id) !!}
                    {!! Form::hidden('task_id', $task->id) !!}
                    @if(Auth::User()->can("comment_task") || Auth::User()->hasRole("Admin"))
                        {!! Form::button('Add comment', array('type' => 'submit',
                                                              'class' => 'btn btn-primary',
                                                              'data-toggle'=>'confirmation',
                                                              'data-title'=>"Publish comment",
                                                              'data-content'=>"Do you want to publish your new comment?",
                                                              'data-placement'=>"center",
                                                              'title'=>"")) !!}
                    @else
                        <div>You have no permissions to comment Task.</div>
                    @endif
                    {!! Form::close() !!}
        </span>

                <span class="col-lg-12">
            <h3>Files:</h3>
                    @if(!$files->isEmpty())
                        <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('Created')}}</th>
                            <th>{{__('User added')}}</th>
                            <th>{{__('Path')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td>{{ $file->created_at }}</td>
                                <td>{{ $file->author->name }}</td>
                                <td><a href="{{ asset($file->path) }}">{{last(explode("/",$file->path)) }}</a>
                                <td>
                                    @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['files.destroy', $file->id] ]) !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Remove', array('type' => 'submit',
                                                                                                                    'class' => 'btn btn-danger',
                                                                                                                    'data-toggle'=>'confirmation',
                                                                                                                    'data-title'=>"Remove",
                                                                                                                    'data-content'=>"Remove file from this task?",
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
                    @if(Auth::User()->can("edit_task") || Auth::User()->hasRole("Admin"))
                        {{--Change image with dropzone--}}
                        {{Form::open(['url'=>"files",
                        'class'=>"dropzone", 'id'=>"myFilesDropzone"])}}
                        {!! Form::hidden('author_id', Auth::user()->id) !!}
                        {!! Form::hidden('task_id', $task->id) !!}
                        <div class="dz-message col-lg-12">Drop files here or click to select files</div>
                        {{ Form::close() }}
                        {{--End dropzone--}}

                    @else
                        <div>You have no permissions to add files to Task.</div>
                    @endif
        </span>
            </div>
        @else
            <div>You have no permissions to view a Task.</div>
        @endif
        @endguest
@endsection