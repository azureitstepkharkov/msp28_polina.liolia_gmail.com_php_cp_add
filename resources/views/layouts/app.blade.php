<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('layouts.partials._head')
<body class="w3-theme-l5">
    @include('layouts.partials._navbar')
    <div id="app" class="w3-container w3-content" style="max-width:2560px;max-height:1440px;margin-bottom:142px;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">

                </div>
            </div>
        </div>
        @guest
            @yield('content')
        @else

        <div class="container">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="w3-row">
                <!-- Left Column -->
                <div class="w3-col m3">
                    <!-- Profile -->
                    <div class="w3-card w3-round w3-white">
                        <div class="w3-container">
                            <h4 class="w3-center">{{__('My Profile')}}</h4>
                            @if(isset(Auth::user()->avatar))
                                <p class="w3-center"><img src="{{url(Auth::user()->avatar)}}" class="w3-circle" alt="Avatar" id="Avatar"></p>
                            @elseif (Auth::user()->hasRole('Admin'))
                                <p class="w3-center"><img src="{{url('./img/avatars/admin.jpg')}}" class="w3-circle" style="height:106px;width:106px" alt="Avatar" id="Avatar"></p>
                            @elseif (Auth::user()->hasRole('ProjectMan'))
                                <p class="w3-center"><img src="{{url('./img/avatars/pm.jpg')}}" class="w3-circle" style="height:106px;width:106px" alt="Avatar" id="Avatar"></p>
                            @elseif (Auth::user()->hasRole('TeamLeader'))
                                <p class="w3-center"><img src="{{url('./img/avatars/tl.jpg')}}" class="w3-circle" style="height:106px;width:106px" alt="Avatar" id="Avatar"></p>
                            @elseif (Auth::user()->hasRole('Programmer'))
                                <p class="w3-center"><img src="{{url('./img/avatars/dev.jpg')}}" class="w3-circle" style="height:106px;width:106px" alt="Avatar" id="Avatar"></p>
                            @elseif (Auth::user()->hasRole('Client'))
                            <p class="w3-center"><img src="{{url('./img/avatars/client.jpg')}}" class="w3-circle" style="height:106px;width:106px" alt="Avatar" id="Avatar"></p>
                            @else
                                <p class="w3-center"><img src="{{url('./img/avatars/other.jpg')}}" class="w3-circle" style="height:106px;width:106px" alt="Avatar" id="Avatar"></p>
                            @endif
                            <button class="w3-button w3-block w3-theme-l4" onclick="location.href= '{{ route('userAccount.edit', Auth::user()->id) }}'">{{__('Edit')}}</button>
                            <hr>
                            <p><i class="fa fa-pencil fa-fw w3-margin-right w3-text-theme"></i>{{ Auth::user()->name }}</p>
                            <p><i class="fa fa-home fa-fw w3-margin-right w3-text-theme"></i>{{ Auth::user()->email }}</p>

                        </div>
                    </div>
                    <!-- End Profile -->
                    <br>
                    <!-- Accordion -->
                    <div class="w3-card w3-round">
                        <div class="w3-white">
                            @if(Auth::user()->hasRole('Admin'))
                                @ability('Admin', 'adminperm')
                                <button onclick="location.href='{{ route('roles.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-calendar-check-o fa-fw w3-margin-right"></i> {{__('Roles')}}</button>
                                <button onclick="location.href='{{ route('permissions.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Permissions')}}</button>
                                <button onclick="location.href='{{ route('technologies.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Technologies')}}</button>
                                <button onclick="location.href='{{ route('users.index', ['filter'=>'all']) }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-users fa-fw w3-margin-right"></i> {{__('Users')}}</button>
                                <button onclick="location.href='{{ route('users.index', ['filter'=>'employees']) }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right" id="employees"></i> {{__('Employees')}}</button>
                                <button onclick="location.href='{{ route('users.index', ['filter'=>'programmers']) }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Programmers')}}</button>
                                <button onclick="location.href='{{ route('users.index', ['filter'=>'clients']) }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Clients')}}</button>
                                <button onclick="location.href='{{ route('tasks.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Tasks')}}</button>
                                <button onclick="location.href='{{ route('projects.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Projects')}}</button>
                                @endability
                            @elseif (Auth::user()->hasRole('ProjectMan'))
                                <button onclick="location.href='{{ route('users.index', ['filter'=>'clients']) }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Clients')}}</button>
                                <button onclick="location.href='{{ route('home') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Projects')}}</button>
                                <button onclick="location.href='{{ route('tasks.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Tasks')}}</button>
                                <button onclick="location.href='{{ route('users.index', ['filter'=>'programmers']) }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Programmers')}}</button>
                                <button onclick="location.href='{{ route('technologies.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Technologies')}}</button>
                                <button onclick="location.href='{{ route('users.index', ['filter'=>'employees']) }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right" id="employees"></i> {{__('Employees')}}</button>
                                <button onclick="location.href='{{ route('projects.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Projects')}}</button>
                            @elseif (Auth::user()->hasRole('TeamLeader'))
                                <button onclick="location.href='{{ route('tasks.index', Auth::user()->id) }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Tasks')}}</button>
                                <button onclick="location.href='{{ route('users.index', ['filter'=>'programmers']) }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Programmers')}}</button>
                                <button onclick="location.href='{{ route('technologies.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Technologies')}}</button>
                                <button onclick="location.href='{{ route('projects.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Projects')}}</button>
                            @elseif (Auth::user()->hasRole('Client'))
                                <button onclick="location.href='{{ route('tasks.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Tasks')}}</button>
                                <button onclick="location.href='{{ route('projects.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Projects')}}</button>
                            @elseif (Auth::user()->hasRole('Programmer'))
                                <button onclick="location.href='{{ route('tasks.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Tasks')}}</button>
                                <button onclick="location.href='{{ route('projects.index') }}'" class="w3-button w3-block w3-theme-l1 w3-left-align">
                                    <i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> {{__('Projects')}}</button>

                            @endif
                        </div>
                    </div>
                    <!-- End Accordion -->
                    <br>
                </div>
                <!-- End Left Column -->
                <!-- Middle Column -->
                <div class="w3-col m7">
                    <div class="w3-row-padding">
                        <div class="w3-col m12">
                            <div class="w3-card w3-round w3-white">
                                <div class="w3-container w3-padding">
                                    <p>@yield('content')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Middle Column -->
                <!-- Right Column -->
                <div class="w3-col m2">
                    <div class="w3-card w3-round w3-white w3-center w3-padding">
                        <div class="w3-container">
                            <h4>Current messages</h4>
                            <hr>
                            @include('flash::message')
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="w3-card w3-round w3-white w3-center w3-padding">
                        <div class="w3-container">
                            <h4>Tasks expires soon</h4>
                            <hr>
                            @if(!$tasks->isEmpty())
                                <ul class="tasks_expires">
                                @foreach($tasks as $task)
                                    @if($task->status == 'new' || $task->status == 'in_progress')
                                            @if($task->end < $date)
                                    <li class="overdue">
                                            @else
                                                <li>
                                                    @endif
                                        <a href="{{url("tasks/$task->id")}}">{{ $task->name }}</a> -
                                        <span class="task_expires_date">
                                            {{date_diff(DateTime::createFromFormat ( 'd/m/Y' , $task->end),
                                            DateTime::createFromFormat ("d-m-Y", $date))->format('%a')}}
                                            @if($task->end < $date)
                                                day(s) overdue
                                            @else
                                                day(s) left
                                            @endif
                                        </span>
                                    </li>
                                        @endif
                                @endforeach
                                </ul>
                            @else
                                <div>No tasks yet</div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- End Right Column -->
            </div>
        </div>
        @endguest
    </div>
    @include('layouts.partials._footer')

    <script src="{{ asset('js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="{{ asset('js/dropzone.js') }}"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
