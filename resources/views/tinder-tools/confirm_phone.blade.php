@extends('tinder-tools.templates.template')
@section('content')
<div class="container text-center" style="margin-top:5%;">
    <div class="card text-center" style="width:50%;display:inline-block;">
        <div class="card-body bg-light" style="padding:2em;">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="form-signin" method="POST" action="{{action('TinderLoginController@confirm_login_phone')}}">
                {{ csrf_field() }}
                <div class="text-center mb-4">
                    <i class="fa fa-mobile-alt fa-4x"></i>
                </div>
                <div class="form-label-group">
                    <input type="text" name="code" class="form-control" placeholder="123456" maxlength="6" value="{{old("code")}}" required autofocus>
                    <label for="inputEmail">Código de confirmação</label>
                </div>
                <input type="hidden" name="phone" value="{{$phone ?? null}}">
                <input type="hidden" name="log_code" value="{{$log_code ?? null}}">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Confirmar</button>
                <p class="mt-5 mb-3 text-muted text-center">&copy; 2017-2018</p>
            </form>
        </div>
    </div>
</div>

@endsection