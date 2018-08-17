@extends('tinder-tools.templates.template')
@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Londrina+Outline" rel="stylesheet">
@endpush

@section('nav-bar')
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#F0F0F0;border-bottom: solid 1px lightgrey;">
        <div class="container">
            <a class="navbar-brand">🌶️🍆😏 <span class="badge badge-primary" title="Beta version">BETA</span> 👌💋🔥</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExample07">
                <ul class="navbar-nav mr-auto">
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
                        <a class="nav-link" href="/tinder-tools/likes"><i class="fab fa-hotjar"></i> <b>Matches</b></a>
                    </li>
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
                <div class="my-2 my-md-0 text-center" style="line-height:1;">
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
                        <a href="" onclick="return false;" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="{{Session::get('tinder-tools')['name'] ?? null}}, {{$idade_logged}}, {{$gender_logged}}" data-content="Última localização: {{Session::get('tinder-tools')['city'] ?? null}}<br/>em {{Carbon\Carbon::parse(Session::get('tinder-tools')['ping_time'])->format('d/m/Y') ?? null}}<br/>Likes restantes: XXX" data-html="true">
                            <i class="fas fa-user"></i>
                            {{Session::get('tinder-tools')["name"] ?? null}}
                        </a>
                        <span>/</span>
                        <a href="/tinder-tools/logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
                        <br/><small>Likes restantes: <b><span id="likes_remaining">0</span></b></small>
                    @else
                        <a href="/tinder-tools/login">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <div class="card" style="margin-top:1em;">
        <div class="card-header">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="text-center row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 d-none d-xl-block">
                    <img class="img-fluid index-max-height" src="{{url('images/ISWYDT.gif')}}">
                    <img class="img-fluid index-max-height" src="{{url('images/IYKWIM-MR-BEAN.jpg')}}">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                    <span class="title-emoji d-none d-lg-inline d-xl-inline">🌶️</span>
                    <h1 class="display-1 titulo inline-headers">Tinder Tools</h1>
                    <span class="title-emoji d-none d-lg-inline d-xl-inline">🔥</span>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 d-none d-none d-xl-block">
                    <img class="img-fluid index-max-height" src="{{url('images/LENNY-FACE.gif')}}">
                </div>
            </div>
            <hr class="hr-card"/>
            <form class="" method="POST" id="form" action="{{action('TinderController@search')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2 inline-input">
                        <input type="text" class="form-control" name="nome" placeholder="Nome" maxlength="20" value="@if(isset($nome) && !empty($nome)){{$nome}}@else{{old('nome')}}@endif">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2 inline-input">
                        <input type="text" class="form-control" name="bio" placeholder="Bio" maxlength="50" value="@if(isset($bio) && !empty($bio)){{$bio}}@else{{old('bio')}}@endif">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-1 inline-input">
                        <input type="number" class="form-control" name="idade" placeholder="Idade" min="18" maxlength="2" value="@if(isset($idade) && !empty($idade)){{$idade}}@else{{old('idade')}}@endif">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-1 inline-input">
                        <select name="genero" class="form-control" form="form">
                            @if(isset($genero) && !empty($genero))
                                <option value="" disabled>Gênero</option>
                            @else
                                <option value="" disabled selected>Gênero</option>
                            @endif
                            <option value="hm" @if(isset($genero) && $genero=="hm") selected @endif>Homem > Mulher</option>
                            <option value="hh" @if(isset($genero) && $genero=="hh") selected @endif>Homem > Homem</option>
                            <option value="mh" @if(isset($genero) && $genero=="mh") selected @endif>Mulher > Homem</option>
                            <option value="mm" @if(isset($genero) && $genero=="mm") selected @endif>Mulher > Mulher</option>
                            <option value="outros" @if(isset($genero) && $genero=="outros") selected @endif>Outros</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2 inline-input">
                        <select name="instagram" class="form-control" form="form">
                            @if(isset($instagram) && !empty($instagram))
                                <option value="" disabled>Instagram</option>
                            @else
                                <option value="" disabled selected>Instagram</option>
                            @endif
                            <option value="instagrams" @if(isset($instagram) && $instagram=="instagrams") selected @endif>Com Instagram</option>
                            <option value="instagramn" @if(isset($instagram) && $instagram=="instagramn")) selected @endif>Sem Instagram</option>
                            <option value="todos" @if(isset($instagram) && $instagram=="todos") selected @endif>Todos</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2 inline-input">
                        <select name="orderby" class="form-control" form="form">
                            @if(isset($orderby) && !empty($orderby))
                                <option value="" disabled>Ordenar por</option>
                            @else
                                <option value="" disabled selected>Ordenar por</option>
                            @endif
                            <option value="nomeaz" @if(isset($orderby) && $orderby=="nomeaz") selected @endif>Nome - A > Z</option>
                            <option value="nomeza" @if(isset($orderby) && $orderby=="nomeza") selected @endif>Nome - Z > A</option>
                            <option value="idade01" @if(isset($orderby) && $orderby=="idade01") selected @endif>Idade - Menor > Maior</option>
                            <option value="idade10" @if(isset($orderby) && $orderby=="idade10") selected @endif>Idade - Maior > Menor</option>
                            <option value="distancia01" @if(isset($orderby) && $orderby=="distancia01")) selected @endif>Distância - Menor > Maior</option>
                            <option value="distancia10" @if(isset($orderby) && $orderby=="distancia10") selected @endif>Distância - Maior > Menor</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2 inline-input">
                        <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
                        <a href="{{url('/tinder-tools')}}">
                            <button type="button" class="btn btn-danger"><i class="fas fa-times-circle"></i></button>
                        </a>
                    </div>
                    
                </div>

            </form>
        </div>

        <div class="card-body">
            
            <div class="row row-eq-height">

                @if(isset($profiles) && $profiles->count()>0)

                    @foreach($profiles as $profile)
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" id="card-{{$profile->tinder_id}}" style="display:grid;margin-top:0.5em;margin-bottom:0.5em;">
    
                            <div class="card">

                                <div class="spinner-div" id="div-loader-{{$profile->tinder_id}}">
                                    <div class="spinner" id="loader-{{$profile->tinder_id}}">
                                    </div>
                                    <div class="liked" id="liked-{{$profile->tinder_id}}">
                                        <i class="fas fa-check fa-7x" style="color:#00ff00;"></i>
                                    </div>
                                </div>
                                <!--
                                <div id="carousel-{{$profile->id}}" class="carousel slide" data-ride="carousel">
                        
                                    <ol class="carousel-indicators">
                                        @if(!empty($profile->photos) && count(json_decode($profile->photos))>0)
                                            @foreach( json_decode($profile->photos) as $imagem )
                                                <li data-target="#carousel-{{$profile->id}}" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                                            @endforeach
                                        @else
                                            <li data-target="#carousel-{{$profile->id}}" data-slide-to="0" class="active"></li>
                                        @endif
                                    </ol>
                                    <div class="carousel-inner" role="listbox">
                                        @if(!empty($profile->photos) && count(json_decode($profile->photos))>0)
                                            @foreach( json_decode($profile->photos) as $imagem )
                                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                    <img class="d-block img-fluid rounded mx-auto d-block carousel-img" src="{{ str_replace('1080x1080','320x320',$imagem) }}" alt="">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <a class="carousel-control-prev" href="#carousel-{{$profile->id}}" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true">
                                            <i class="fas fa-arrow-alt-circle-left fa-2x"></i>
                                        </span>
                                        <span class="sr-only">Anterior</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carousel-{{$profile->id}}" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true">
                                            <i class="fas fa-arrow-alt-circle-right fa-2x"></i>
                                        </span>
                                        <span class="sr-only">Próxima</span>
                                    </a>
                                    
                                </div>
                                -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <h6>
                                                @if(!$profile->gender)
                                                    <i class="fas fa-mars fa-lg" style="color:#3366cc;" title="Masculino"></i>
                                                @elseif($profile->gender)
                                                    <i class="fas fa-venus fa-lg" style="color:#ff33cc;" title="Feminino"></i>
                                                @else
                                                    <i class="fas fa-genderless fa-lg" title="Outros"></i>
                                                @endif
                                                <span class="nome">{{$profile->name ?? null}}</span>, 
                                                <span class="idade">{{(Carbon\Carbon::today()->year - Carbon\Carbon::parse($profile->birth_date)->year-1)  ?? null}}</span>, 
                                                {{round(($profile->distance_mi * 1.60934), 0) ?? null}} Km 
                                                <a href="https://www.google.com.br/maps/search/{{$profile->logged_profile->lat ?? null}},{{$profile->logged_profile->lon ?? null}}/" target="_blank">daqui</a>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            @if(!empty($profile->teasers))
                                                @foreach(json_decode($profile->teasers) as $teaser)
                                                    @if($teaser->type != "instagram" && $teaser->type != "artists")
                                                        <p class="teasers">{{$teaser->string}}</p>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="hr-card"/>
                                    <!--
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center actions-div">
                                            <a href="/#/{{$profile->tinder_id}}" class="float-left"><i class="fas fa-times-circle fa-3x action-icon" style="color:red;" title="Passar"></i></a>
                                            <a href="" class=""><i class="fas fa-star fa-3x action-icon" title="Super Like"></i></a>
                                            <a class="like float-right" id="{{$profile->tinder_id}}" data-link="{{$profile->tinder_id}}"><i class="fas fa-heart fa-3x action-icon" title="Gostar" style="color:green;"></i></a>
                                        </div>
                                    </div>
                                    <hr class="hr-card"/>
                                    -->
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <a href="https://www.google.com.br/maps/search/{{$profile->logged_profile->lat ?? null}},{{$profile->logged_profile->lon ?? null}}/" target="_blank"><i class="fas fa-map-marker-alt fa-2x" title="Localização"></i></a>&nbsp;
                                            @if(!empty($profile->instagram))
                                                <a href="https://www.instagram.com/{{$profile->instagram}}" target="_blank"><i class="fab fa-instagram fa-2x" title="Instagram"></i></a>&nbsp;
                                            @endif
                                            @if(!empty(json_decode($profile->spotify)))
                                                    @php
                                                        $artistas= null;
                                                    @endphp
                                                @foreach(json_decode($profile->spotify) as $artist)
                                                    @php
                                                        $artistas.= $artist->name."<br/>";
                                                    @endphp
                                                @endforeach
                                                <a href="" onclick="return false;" tabindex="0" data-toggle="popover" data-trigger="focus" title="Spotify" data-content="{{$artistas}}" data-html="true" style="outline: none;">
                                                    <i class="fab fa-spotify fa-2x" title="Spotify"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <p class="teasers text-right"><b>Interesse: </b>
                                                @if(!$profile->logged_profile->gender)
                                                    <i class="fas fa-mars fa-lg" style="color:#3366cc;" title="Masculino"></i>
                                                @elseif($profile->logged_profile->gender)
                                                    <i class="fas fa-venus fa-lg" style="color:#ff33cc;" title="Feminino"></i>
                                                @else
                                                    <i class="fas fa-genderless fa-lg" title="Outros"></i>
                                                @endif
                                                , {{(Carbon\Carbon::today()->year - Carbon\Carbon::parse($profile->logged_profile->birth_date)->year)  ?? null}}
                                            </p>
                                            <p class="teasers text-right"><b>Região:</b> {{$profile->logged_profile->city}}</p>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item text-justify bio"><strong>Bio:&nbsp;</strong>{{$profile->bio ?? null}}</li>
                                    </ul>
                                </div>
                                <div class="card-footer" style="margin:0;padding:0.2em;">
                                    <p class="updated_at">
                                        <a href="" onclick="return false;" class="update_profile text-left" id="update_profile_{{$profile->tinder_id}}" data-link="{{$profile->tinder_id}}">Atualizar <i class="fas fa-sync-alt" title="Atualizar"></i></a>
                                        <span class="float-right"><i>Última atualização: {{Carbon\Carbon::parse($profile->updated_at)->format('d/m/Y')}}</i></span>
                                    </p>
                                </div>
                            </div>
    
                        </div>
                    @endforeach

                @else
                    <h2 style="padding-left: 1em;padding-right: 1em;">Nenhum resultado encontrado.</h2>
                @endif
            </div>

        </div>
        <div class="card-footer">
            @if(isset($profiles) && $profiles->count()>0)
                {!! $profiles->appends(Request::only(['nome'=>'nome', 'bio'=>'bio', 'idade'=>'idade', 'genero'=>'genero', 'distancia'=>'distancia','instagram'=>'instagram', 'orderby'=>'orderby']))->links() !!}
            @endif
        </div>

    </div>

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
                    E-mail, @IAmDinamite, etc
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

    @push ('scripts')
        <script src="{{url('js/jquery.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>
    @endpush
@endsection