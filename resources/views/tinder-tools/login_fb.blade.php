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
            <form class="form-signin" method="POST" action="{{action('TinderLoginController@login_fb_post')}}">
                {{ csrf_field() }}
                <div class="text-center mb-4">
                    <i class="fab fa-facebook-square fa-4x" style="color:blue;"></i>
                </div>
                <div class="form-label-group">
                <input type="email" name="email" class="form-control" placeholder="E-mail" maxlength="50" value="{{old("email")}}" required autofocus>
                    <label for="inputEmail">E-mail</label>
                </div>
                <div class="form-label-group">
                    <input type="password" name="senha" class="form-control" placeholder="Senha" maxlength="50" required>
                    <label for="inputPassword">Senha</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
                <p class="mt-5 mb-3 text-muted text-center">&copy; 2017-2018</p>
            </form>
        </div>
    </div>
</div>

@endsection