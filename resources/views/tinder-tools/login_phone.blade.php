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
            <form class="form-signin" method="POST" action="{{action('TinderLoginController@login_phone_post')}}">
                {{ csrf_field() }}
                <div class="text-center mb-4">
                    <i class="fa fa-mobile-alt fa-4x"></i>
                </div>
                <div class="form-label-group">
                    <input type="phone" name="phone" class="form-control" placeholder="(99) 99999-9999" maxlength="15" value="{{old("phone")}}" required autofocus>
                    <label for="inputEmail">Telefone</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Enviar</button>
                <p class="mt-5 mb-3 text-muted text-center">&copy; 2017-2018</p>
            </form>
        </div>
    </div>
</div>

@endsection