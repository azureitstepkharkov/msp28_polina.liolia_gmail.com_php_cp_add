@extends('layouts.app')

@section('title', '| Roles')

@section('content')
    @guest
        @include('auth.login')
    @else
    <div class="col-lg-10 col-lg-offset-1">
        <h1 class="page_header"> Roles
            <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
            <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
            <a href="{{ route('tasks.index') }}" class="btn btn-default pull-right">Tasks</a>
        </h1>

        <hr>
        @if(Auth::User()->hasRole("Admin"))
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Operation</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($roles as $role)
                    <tr>

                        <td>{{ $role->name }}</td>

                        <td>{{$role->permissions->implode("name",", ")}}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                        <td>
                            <a href="{{ URL::to('roles/'.$role->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                            {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Delete', array('type' => 'submit',
                                                                                                        'class' => 'btn btn-danger',
                                                                                                        'data-toggle'=>'confirmation',
                                                                                                        'data-title'=>"Delete",
                                                                                                        'data-content'=>"Delete role $role->name?",
                                                                                                        'data-placement'=>"center",
                                                                                                        'title'=>"")) !!}
                            {!! Form::close() !!}

                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
        <a href="{{ URL::to('roles/create') }}" class="btn btn-success">Add Role</a>
        @else
            <div>Only Admin can see a Roles list.</div>
        @endif
    </div>
    @endguest
@endsection