@extends('layouts.app')

@section('title', '| Permissions')


@section('content')
    @guest
        @include('auth.login')
    @else
    <div class="col-lg-10 col-lg-offset-1">
        <h1 class="page_header">Available Permissions

            <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
            <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
            <a href="{{ route('tasks.index') }}" class="btn btn-default pull-right">Tasks</a></h1>
        <hr>
        @if(Auth::User()->hasRole("Admin"))
        <div class="table-responsive">
            <table class="table table-bordered table-striped">

                <thead>
                <tr>
                    <th>Permissions</th>
                    <th>Roles</th>
                    <th>Operation</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>{{$permission->roles->implode('name', ', ')}}</td>
                        <td>
                            <a href="{{ URL::to('permissions/'.$permission->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                            {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Delete', array('type' => 'submit',
                                                                                                        'class' => 'btn btn-danger',
                                                                                                        'data-toggle'=>'confirmation',
                                                                                                        'data-title'=>"Delete",
                                                                                                        'data-content'=>"Delete permission $permission->name?",
                                                                                                        'data-placement'=>"center",
                                                                                                        'title'=>"")) !!}
                            {!! Form::close() !!}

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <a href="{{ URL::to('permissions/create') }}" class="btn btn-success">Add Permission</a>
        @else
            <div>Only Admin can see a Permission list.</div>
        @endif
    </div>
    @endguest
@endsection