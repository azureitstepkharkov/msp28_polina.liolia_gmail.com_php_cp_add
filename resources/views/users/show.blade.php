@extends('layouts.app')

@section('title', '| User')

@section('content')
    @guest
    @include('auth.login')
    @else
        @if(Auth::User()->can("index_user") || Auth::User()->hasRole("Admin"))
            <div class="col-lg-12">
                <h1 class="page_header">
                    {{__("User ")}}<span>{{$user->name}}</span>
                    @if(Auth::User()->hasRole("Admin"))
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-right"
                           style="margin-right: 3px;">Edit</a>
                    @endif
                </h1>
                <hr>
                <div class="col-lg-5">

                <p class="w3-left-align"><img src="{{url($user->avatar)}}"  alt="Avatar" id="user_avatar"></p>
                @if(Auth::User()->can("edit_user") || Auth::User()->hasRole("Admin"))
                    {{--Change image with dropzone--}}
                    {{Form::open(['url'=>"user_account/$user->id/avatar",
                    'class'=>"dropzone",
                    'id'=>"myAwesomeDropzone"])}}
                    <div class="dz-message ">Drop files here or click to select new avatar</div>
                    {{ Form::close() }}
                    {{--End dropzone--}}
                @endif
                </div>

                <div class="col-lg-5 col-lg-offset-1">
                <h3 class="show_details">Name:</h3>
                <span>{{$user->name}}</span> <br>
                <h3 class="show_details">Email:</h3>
                <span>{{$user->email}}</span> <br>
                <h3 class="show_details">Contacts:</h3>
                <div>
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
                </div>
                    <h3 class="show_details">Status:</h3>
                    <span class="inline_form_control">
                    {{$user->status}}
                        @if(Auth::User()->can('deactivate_user') || Auth::User()->hasRole("Admin"))
                            @if($user->status == 'active')
                                {!! Form::open(['method' => 'PUT', 'route' => ['users.inactive', $user->id], 'class'=> 'form_inline' ]) !!}
                                {!! Form::button(" Deactivate", array('type' => 'submit',
                                                            'class' => 'btn btn-danger btn-danger2',
                                                            'data-toggle'=>'confirmation',
                                                            'data-title'=>"Deactivate",
                                                            'data-content'=>"Deactivate user $user->name?",
                                                            'data-placement'=>"center",
                                                            'title'=>"")) !!}
                                {!! Form::close() !!}
                            @elseif($user->status == 'inactive')
                                {!! Form::open(['method' => 'PUT', 'route' => ['users.inactive', $user->id], 'class'=> 'form_inline' ]) !!}
                                {!! Form::button(" Activate", array('type' => 'submit',
                                                            'class' => 'btn btn-danger btn-danger2',
                                                            'data-toggle'=>'confirmation',
                                                            'data-title'=>"Activate",
                                                            'data-content'=>"Activate user $user->name?",
                                                            'data-placement'=>"center",
                                                            'title'=>"")) !!}
                                {!! Form::close() !!}
                            @endif
                        @endif
                </span>
                </div>
                <div class="col-lg-12">
                    <hr>
                </div>
                <div class="col-lg-5">
                @ability('Admin', 'adminperm')
                <h3 class="show_details">Roles:</h3>
                <span class="">
                    {{ Form::model($user, array('route' => array('users.updateRoles', $user->id), 'method' => 'PUT')) }}
                    <div class='form-group'>
                        @foreach ($roles as $role)
                            {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
                            {{ Form::label($role->name, ucfirst($role->name)) }}<br>
                        @endforeach
                    </div>
                    {{ Form::submit(__('Save roles'), array('class' => 'btn btn-primary')) }}
                    {{ Form::close() }}
                </span>
                @endability
                </div>

                @if($user->hasRole("ProjectMan") || $user->hasRole("Programmer") || $user->hasRole("TeamLeader"))
            <span class="col-lg-6 col-lg-offset-1">
                <h3>Technologies:</h3>
                @if(Auth::User()->can("index_technology") || Auth::User()->hasRole("Admin"))
                    @if(!$user->technologies->isEmpty() && $user->technologies )
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
                                @foreach($user->technologies as $tech)
                                    <tr>
                                        <td>{{ $tech->name }}
                                        <td>{{ $tech->description }}</td>
                                        <td>
                                            @if(Auth::User()->can("edit_user") || Auth::User()->hasRole("Admin"))
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['users.detach_technology', $user->id, $tech->id] ]) !!}
                                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Remove', array('type' => 'submit',
                                                                                                                            'class' => 'btn btn-danger',
                                                                                                                            'data-toggle'=>'confirmation',
                                                                                                                            'data-title'=>"Remove",
                                                                                                                            'data-content'=>"Remove technology $tech->name from user $user->name?",
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
                        @if(!empty($technologies_available))
                            {{ Form::open(['method' => 'POST','url' => "users/$user->id/attach_technology"]) }}

                            <div class="form-group">
                                    {{ Form::label('technology', 'Available technologies:') }}
                                {{ Form::select('technology', $technologies_available)}}
                                @if(Auth::User()->can("edit_user") || Auth::User()->hasRole("Admin"))
                                    {{ Form::submit('Add technology', array('class' => 'btn btn-primary')) }}
                                @endif
                                </div>
                            {{ Form::close() }}
                        @endif
                @else
                    <div>You have no permissions to view Technologies.</div>
                @endif
            </span>
                @endif
                <div class="col-lg-12">
                    <hr>
                </div>

        {{--Comments--}}
            <span class="col-lg-12">
            <h3>Comments:</h3>
                @if(Auth::User()->can("index_comment_user") || Auth::User()->hasRole("Admin"))
                    @if(!$user->comments->isEmpty())
                        <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('Created')}}</th>
                            <th>{{__('Author')}}</th>
                            <th>{{__('Text')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->comments as $comment)
                            <tr>
                                <td>{{ $comment->created_at }}</td>
                                <td>{{ $comment->author->name}}</td>
                                <td>{{ $comment->comment }}
                                <td>
                                    @if($comment->user_id == $user->id || Auth::User()->hasRole("Admin"))
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['comment_user.destroy', $comment->id] ]) !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Remove', array('type' => 'submit',
                                                                                                                    'class' => 'btn btn-danger',
                                                                                                                    'data-toggle'=>'confirmation',
                                                                                                                    'data-title'=>"Remove",
                                                                                                                    'data-content'=>"Remove comment from this user?",
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
                {!! Form::open(['method' => 'POST', 'route' => ['comment_user.store'] ]) !!}
                {!! Form::textarea('comment', null, array('class' => 'form-control')) !!}
                {!! Form::hidden('author_id', Auth::user()->id) !!}
                {!! Form::hidden('user_id', $user->id) !!}
                {!! Form::button('Add comment', array('type' => 'submit',
                                                          'class' => 'btn btn-primary',
                                                          'data-toggle'=>'confirmation',
                                                          'data-title'=>"Publish comment",
                                                          'data-content'=>"Do you want to publish your new comment?",
                                                          'data-placement'=>"center",
                                                          'title'=>"")) !!}
                {!! Form::close() !!}
            </span>
            {{--End comments--}}

        @else
            <div>You have no permissions to view user's data.</div>
        @endif
            </div>
        @endguest
@endsection