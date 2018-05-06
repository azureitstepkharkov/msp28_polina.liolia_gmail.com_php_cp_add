@extends('layouts.app')

@section('title', '| Welcome')
@section('content')
@guest
    <body onload="loadFunc()">
    <div class="w3-container w3-content" style="text-align: center; margin: 10% auto" id="guest">
        <img src="{{url('./img/Welcome.png')}}">
    </div>
    </body>
@else
        <div class="w3-container w3-content" style="text-align: center; margin: 10% auto">
            <img src="{{url('./img/Welcome.png')}}">
        </div>
@endguest
    <script>
        function loadFunc() {
            setTimeout(function tick() {
                location.href='{{ route('login') }}'
            }, 2000);
        }
    </script>
@endsection
