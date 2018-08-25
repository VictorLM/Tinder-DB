@extends('tinder-tools.templates.template')

@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Londrina+Outline" rel="stylesheet">
@endpush

@section('bg-fotos')
    @include('tinder-tools.templates.img-bg')
@endsection

@section('content')
    <div class="container text-center">
        <div class="card text-center card-login-fb">
            <div class="card-header">
                <span class="title-emoji d-none d-lg-inline d-xl-inline">🌶️</span>
                <h1 class="display-4 titulo inline-headers">Tinder Tools</h1>
                <span class="title-emoji d-none d-lg-inline d-xl-inline">🔥</span>
            </div>
            <div class="card-body bg-light">
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
                    </div>
                    <br/>
                    <input type="hidden" name="phone" value="{{$phone ?? null}}">
                    <input type="hidden" name="log_code" value="{{$log_code ?? null}}">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Confirmar</button>
                </form>
                <a href="/tinder-tools/login"><i class="fas fa-arrow-left"></i> <b>Voltar</b></a>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{url('js/js-bg.js')}}"></script>
    @endpush
@endsection