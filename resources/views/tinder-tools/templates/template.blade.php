<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Victor Meireles - @IAmDinamite">
        <link rel="icon" href="">
        <title>🌶️🔥Tinder Tools {{$title or null}}</title>
        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        @stack('styles')
    </head>
    <body>
        <div class="container-fluid container-index">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <div class="alert alert-success alert-dismissable" style="margin-left:10px;margin-right:10px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('alert-' . $msg) }}
                    </div>
                @endif
            @endforeach
            @yield('content')
            <br/>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
        @stack ('scripts')
    </body>
</html>