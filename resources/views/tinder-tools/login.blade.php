@extends('tinder-tools.templates.template')

@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Londrina+Outline" rel="stylesheet">
@endpush

@section('bg-fotos')
    @include('tinder-tools.templates.img-bg')
@endsection

@section('content')
    <div class="container text-center">
        <div class="card text-center card-login">
            <div class="card-header">
                <span class="title-emoji d-none d-lg-inline d-xl-inline">🌶️</span>
                <h1 class="display-4 titulo inline-headers">Tinder Tools</h1>
                <span class="title-emoji d-none d-lg-inline d-xl-inline">🔥</span>
            </div>
            <div class="card-body bg-light">
                <div class="text-center justify-content-center">
                    <a class="login-btn-link" href="{{url('/tinder-tools/login/facebook')}}">
                        <button type="button" class="btn btn-primary btn-lg btn-block">
                            <i class="fab fa-facebook-square fa-2x"></i> ENTRAR COM FACEBOOK
                        </button>
                    </a>
                </div>
                <br/>
                <div class="text-center justify-content-center">
                    <a class="login-btn-link" href="{{url('/tinder-tools/login/telefone')}}">
                        <button type="button" class="btn btn-secondary btn-lg btn-block">
                            <i class="fas fa-mobile-alt fa-2x"></i> ENTRAR COM TELEFONE
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{url('js/js-bg.js')}}"></script>
    @endpush
@endsection