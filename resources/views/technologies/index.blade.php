@extends('layouts.app')

@section('title', '| Technologies')

@section('content')
    @guest
        @include('auth.login')
    @else
    <div class="col-lg-10 col-lg-offset-1">
        <h1 class="page_header"> {{__('Technologies')}}
        </h1>
        <hr>
        @if(Auth::User()->can("index_technology") || Auth::User()->hasRole("Admin"))
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>{{__('Technology')}}</th>
                    <th>{{__('Description')}}</th>
                    <th>{{__('Operation')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($technologies as $technology)
                    <tr>
                        <td>{{ $technology->name }}</td>
                        <td>{{ $technology->description }}</td>
                        <td>
                            <a href="{{ URL::to('technologies/'.$technology->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">{{__('Edit')}}</a>

                            {!! Form::open(['method' => 'DELETE', 'route' => ['technologies.destroy', $technology->id] ]) !!}
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Delete', array('type' => 'submit',
                                                                                                        'class' => 'btn btn-danger',
                                                                                                        'data-toggle'=>'confirmation',
                                                                                                        'data-title'=>"Delete",
                                                                                                        'data-content'=>"Delete technology $technology->name?",
                                                                                                        'data-placement'=>"center",
                                                                                                        'title'=>"")) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div>You have no permissions to view Technologies.</div>
        @endif
        @if(Auth::User()->hasRole("Admin"))
        <a href="{{ URL::to('technologies/create') }}" class="btn btn-success">{{__('Add Technology')}}</a>
        @else
            <div>Only Admin can add a new Technology.</div>
        @endif
    </div>
    @endguest
@endsection
