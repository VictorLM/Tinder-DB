@extends('tinder-tools.templates.template')
@section('content')
<div class="container text-center" style="margin-top:15%;">
    <div class="card text-center" style="width:50%;display:inline-block;">
        <div class="card-body bg-light" style="padding:2em;">
            <div class="text-center justify-content-center">
                <a href="{{url('/tinder-tools/login/facebook')}}">
                    <button type="button" class="btn btn-primary btn-lg" style="width:90%">
                        <i class="fab fa-facebook-square fa-2x"></i> ENTRAR COM FACEBOOK
                    </button>
                </a>
            </div>
            <br/>
            <div class="text-center justify-content-center">
                <a href="{{url('/tinder-tools/login/telefone')}}">
                    <button type="button" class="btn btn-secondary btn-lg" style="width:90%">
                            <i class="fas fa-mobile-alt fa-2x"></i> ENTRAR COM NÚMERO DE TELEFONE
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection