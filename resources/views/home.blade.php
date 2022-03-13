@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <button class="btn btn-primary" onclick="trig()">Broadcast</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    Echo.private('chatapp.2')
        .listen('NewMessageEvent', (e) => {
            console.log(e);
        });

    function trig() {
        axios.get('/test').then(function (resp) {
            console.log('ajax sent.')
        })
    }
</script>
@endsection
