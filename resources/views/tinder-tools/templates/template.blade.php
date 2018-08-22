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
        <link href="{{asset('css/style.css')}}" rel="stylesheet">
        @stack('styles')
    </head>
    <body>
        @yield('bg-fotos')
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#F0F0F0;border-bottom: solid 1px lightgrey;">
            <div class="container">
                <a class="navbar-brand">🌶️🍆😏 <span class="badge badge-primary" title="Beta version">BETA</span> 👌💋🔥</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample07">
                    <ul class="navbar-nav mr-auto">
                        @if(Session::has('tinder-tools'))
                            <li class="nav-item active">
                                <a class="nav-link" href="/tinder-tools"><i class="fas fa-search"></i> <b>Busca</b></a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="/tinder-tools/likes"><i class="fas fa-thumbs-up"></i> <b>Likes</b></a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="/tinder-tools/super-likes"><i class="fas fa-heart"></i> <b>Super Likes</b></a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="/tinder-tools/matches"><i class="fab fa-hotjar"></i> <b>Matches</b></a>
                            </li>
                        @endif
                        <li class="nav-item active">
                            <a class="nav-link" href="#sobre" data-toggle="modal">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contato" data-toggle="modal">Contato</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#reportar" data-toggle="modal">Reportar Erro</a>
                        </li>
                    </ul>
                    <div class="my-2 my-md-0" style="line-height:1;">
                        @if(Session::has('tinder-tools'))
                            @php
                                $gender_logged = null;
                                $idade_logged = (Carbon\Carbon::today()->year - Carbon\Carbon::parse(Session::get('tinder-tools')["birth_date"])->year)  ?? null;
                            @endphp
                            @if(!Session::get('tinder-tools')["gender"])
                                @php $gender_logged = '<i class="fas fa-mars fa-lg" style="color:#3366cc;" title="Masculino"></i>'; @endphp
                            @elseif(Session::get('tinder-tools')["gender"])
                                @php $gender_logged = '<i class="fas fa-venus fa-lg" style="color:#ff33cc;" title="Feminino"></i>'; @endphp
                            @else
                                @php $gender_logged = '<i class="fas fa-genderless fa-lg" title="Outros"></i>'; @endphp
                            @endif
                            <a href="" onclick="return false;" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="{{Session::get('tinder-tools')['name'] ?? null}}, {{$idade_logged}}, {{$gender_logged}}" data-content="Última localização: {{Session::get('tinder-tools')['city'] ?? null}}<br/>em {{Carbon\Carbon::parse(Session::get('tinder-tools')['ping_time'])->format('d/m/Y') ?? null}}<br/>" data-html="true">
                                <i class="fas fa-user"></i>
                                {{Session::get('tinder-tools')["name"] ?? null}}
                            </a>
                            <span>/</span>
                            <a href="/tinder-tools/logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
                            <br/><small>Likes restantes: <b><span id="likes_remaining">0</span></b></small>
                            <br/><small>Super Likes restantes: <b><span id="super_likes_remaining">0</span></b></small>
                        @else
                            <a href="/tinder-tools/login">Login</a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-fluid container-index">

            @yield('content')
            <br/>

            <!-- MODALS -->
            <!-- Sobre -->
            <div class="modal fade" id="sobre" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Sobre</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Esse é um projeto open, colabore, não quebre a aplicação, contato @IAmDinamite, etc
                            <p>Créditos: <a href="https://github.com/fbessez/Tinder" target="_blank">github.com/fbessez/Tinder</a> </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contato -->
            <div class="modal fade" id="contato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Contato</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            victor.meireles.dev@gmail.com, @IAmDinamite, etc
                        </div>
                    </div>
                </div>
            </div>
            <!-- Reportar Erro -->
            <div class="modal fade" id="reportar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Reportar Erro</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            E-mail, @IAmDinamite, etc
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
        @stack ('scripts')
    </body>
</html>