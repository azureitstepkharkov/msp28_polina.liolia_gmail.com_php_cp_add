@extends('layouts.app')

@section('title', '| '.__("Edit Your Account"))
@section('content')
    @guest
    @include('auth.login')
    @else
        <div class='col-lg-12'>
            <span class="col-lg-6">
            <h3 class="page_header">{{__("Edit your data")}}</h3>
            <hr>
            <p class="w3-left-align"><img src="{{url($user->avatar)}}"  alt="Avatar" id="user_avatar"></p>

                {{--Change image with dropzone--}}
            {{Form::open(['url'=>"user_account/$user->id/avatar",
            'class'=>"dropzone",
            'id'=>"myAwesomeDropzone"])}}
                <div class="dz-message col-lg-12">Drop files here or click to select new avatar</div>
            {{ Form::close() }}
                {{--End dropzone--}}

            {{ Form::model($user, array('route' => array('userAccount.update', $user->id),'files'=>'true', 'method' => 'PUT')) }}
            <div class="form-group">
                {{ Form::label('name', __('Name')) }}
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>

            <div class="form-group">
                {{ Form::label('email', __('Email')) }}
                {{ Form::email('email', null, array('class' => 'form-control')) }}
            </div>

             <div class="form-group">
                 {{ Form::label('password', __('Input your password to save changes:')) }}<br>
                 {{ Form::password('password', array('class' => 'form-control')) }}
             </div>

             {{ Form::submit(__('Edit data'), array('class' => 'btn btn-primary')) }}
             {{ Form::close() }}
            </span>
            <span class="col-lg-6">
            <h3 class="page_header">{{__("Change password")}}</h3>
            <hr>

            {{ Form::model($user, array('route' => array('userAccount.updatePassword', $user->id), 'method' => 'PUT')) }}

            <div class="form-group">
                {{ Form::label('new_password', __('New password')) }}<br>
                {{ Form::password('new_password', array('class' => 'form-control')) }}

            </div>

            <div class="form-group">
                {{ Form::label('new_password', __('Confirm new password')) }}<br>
                {{ Form::password('new_password_confirmation', array('class' => 'form-control')) }}

            </div>

            <div class="form-group">
                {{ Form::label('prev_password', __('Input current password to save changes:')) }}<br>
                {{ Form::password('prev_password', array('class' => 'form-control')) }}
            </div>

            {{ Form::submit(__('Change password'), array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}
            </span>
        </div>
        @endguest
@endsection