@extends('layouts.app')

@section('title', '| Users')
@section('content')
    @guest
    @include('auth.login')
    @else
        <div class="col-lg-10 col-lg-offset-1">
            <h1 class="page_header"> User Administration
                @ability('Admin', 'adminperm')
                <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
                <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
                @endability
                <a href="{{ route('tasks.index') }}" class="btn btn-default pull-right">Tasks</a></h1>
            <hr>
            @if(Auth::User()->can("index_user") || Auth::User()->hasRole("Admin"))
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contacts</th>
                            <th>User Roles</th>
                            <th>User Status</th>
                            <th>Operations</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td><a href="{{url("users/$user->id")}}">{{ $user->name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->contacts as $contact)
                                        <div>
                                            @if(Auth::User()->hasRole("Admin"))
                                                <a href="{{ route('contacts.edit', $contact->id) }}"
                                                   class="glyphicon glyphicon-pencil" style="margin-left: 3px;"></a>
                                                {!! Form::open(['method' => 'DELETE', 'class'=>'custom_delete_form', 'route' => ['contacts.destroy', $contact->id] ]) !!}
                                                {!! Form::button("<i class='glyphicon glyphicon-trash'></i>", array('type' => 'submit',
                                                'class' => 'custom_delete_btn',
                                                'data-toggle'=>'confirmation',
                                                'data-title'=>"Delete",
                                                'data-content'=>"Delete contact $contact->value?",
                                                'data-placement'=>"center",
                                                'title'=>"")) !!}
                                                {!! Form::close() !!}
                                            @endif
                                            {{$contact->type->contact_type}}: {{$contact->value}}
                                        </div>
                                    @endforeach
                                    @if(Auth::User()->hasRole("Admin"))
                                        <a href="{{ route('contacts.create', ['user_id'=>$user->id]) }}"
                                           class="btn-xs btn-success pull-right" style="margin-right: 3px;">Add</a>
                                    @endif
                                </td>
                                <td>{{ $user->roles()->pluck('name')->implode(', ') }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}
                                <td>{{ Form::label($user->status, ucfirst($user->status)) }}</td>
                                <td>
                                    @if(Auth::User()->hasRole("Admin"))
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-left"
                                           style="margin-right: 3px;">Edit</a>
                                        @if($user->status == 'active')
                                            {!! Form::open(['method' => 'PUT', 'route' => ['users.inactive', $user->id] ]) !!}
                                            {!! Form::button(" Deactivate", array('type' => 'submit',
                                                                        'class' => 'btn btn-danger btn-danger2',
                                                                        'data-toggle'=>'confirmation',
                                                                        'data-title'=>"Deactivate",
                                                                        'data-content'=>"Deactivate user $user->name?",
                                                                        'data-placement'=>"center",
                                                                        'title'=>"")) !!}
                                            {!! Form::close() !!}
                                        @elseif($user->status == 'inactive')
                                            {!! Form::open(['method' => 'PUT', 'route' => ['users.inactive', $user->id] ]) !!}
                                            {!! Form::button(" Activate", array('type' => 'submit',
                                                                        'class' => 'btn btn-danger btn-danger2',
                                                                        'data-toggle'=>'confirmation',
                                                                        'data-title'=>"Activate",
                                                                        'data-content'=>"Activate user $user->name?",
                                                                        'data-placement'=>"center",
                                                                        'title'=>"")) !!}
                                            {!! Form::close() !!}
                                        @endif

                                        {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id] ]) !!}
                                        {!! Form::button("<i class='glyphicon glyphicon-trash'></i> Delete", array('type' => 'submit',
                                        'class' => 'btn btn-danger btn-danger3',
                                        'data-toggle'=>'confirmation',
                                        'data-title'=>"Delete",
                                        'data-content'=>"Delete user $user->name?",
                                        'data-placement'=>"center",
                                        'title'=>"")) !!}
                                        {!! Form::close() !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(Auth::User()->hasRole("Admin"))
                        <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
                    @endif
                </div>
            @else
                <div>You have no permissions to view Users list.</div>
            @endif
        </div>
        @endguest
@endsection