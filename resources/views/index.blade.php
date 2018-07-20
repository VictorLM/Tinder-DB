<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Victor Meireles - @IAmDinamite">
        <link rel="icon" href="">
        <title>Tinder-DB</title>
        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
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

            <div class="card" style="margin-top:1em;">
                <div class="card-header">

                    <div class="text-center row">
                        <div class="col-4">
                            <img class="img-fluid index-max-height" src="{{url('images/IYKWIM-MR-BEAN-1.png')}}">
                            <img class="img-fluid index-max-height" src="{{url('images/IYKWIM-MR-BEAN-2.png')}}">
                        </div>
                        <div class="col-4">
                            <h1 class="display-2 font-weight-normal">Tinder-DB</h1>
                        </div>
                        <div class="col-4">
                            <img class="img-fluid index-max-height" src="{{url('images/IYKWIM-MR-BEAN-1.png')}}">
                            <img class="img-fluid index-max-height" src="{{url('images/IYKWIM-MR-BEAN-2.png')}}">
                        </div>
                    </div>

                    ROTA JA LIKADOS, ROTA MATCHES, ON CLICK LIKE AJAX E IF RESPONSE TRUE DELETE DIV BY ID
                    <hr/>
                    <form class="" method="POST" id="form" action="{{action('ClientController@search')}}">
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
                                    <option value="masculino" @if(isset($genero) && $genero=="masculino") selected @endif>Masculino</option>
                                    <option value="feminino" @if(isset($genero) && $genero=="feminino") selected @endif>Feminino</option>
                                    <option value="outros" @if(isset($genero) && $genero=="outros") selected @endif>Outros</option>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2 inline-input">
                                <input type="number" class="form-control" name="distancia" placeholder="Distância (Km)" maxlength="2" value="@if(isset($distancia) && !empty($distancia)){{$distancia}}@else{{old('distancia')}}@endif">
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
                                <a href="{{url('/')}}">
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
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="display:grid;margin-top:0.5em;margin-bottom:0.5em;">
            
                                    <div class="card">
                                        
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
                                                            <img class="d-block img-fluid rounded img-thumbnail mx-auto d-block" style="max-height:400px;" src="{{ $imagem->processedFiles[1]->url ?? null }}" alt="">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <a class="carousel-control-prev" href="#carousel-{{$profile->id}}" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Anterior</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carousel-{{$profile->id}}" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Próxima</span>
                                            </a>
                                            
                                        </div>
                                        
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                                    <h5 class="card-title">
                                                        @if(!$profile->gender)
                                                            <i class="fas fa-mars fa-lg" style="color:#3366cc;"></i>
                                                        @elseif($profile->gender)
                                                            <i class="fas fa-venus fa-lg" style="color:#ff33cc;"></i>
                                                        @else
                                                            <i class="fas fa-genderless fa-lg"></i>
                                                        @endif
                                                        {{$profile->name ?? null}}, 
                                                        {{(Carbon\Carbon::today()->year - Carbon\Carbon::parse($profile->birth_date)->year-1)  ?? null}}, 
                                                        {{round(($profile->distance_mi * 1.60934), 0) ?? null}} Km
                                                    </h5>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    @if(!empty($profile->instagram) && $profile->instagram != "null")
                                                        <a href="https://www.instagram.com/{{json_decode($profile->instagram)->username ?? null}}" target="_blank"><i class="fab fa-instagram fa-2x"></i></a>&nbsp;
                                                    @endif
                                                    @if(!empty($profile->spotify) && $profile->spotify != "null")
                                                        <a href="https://www.instagram.com/{{json_decode($profile->spotify)->username ?? null}}" target="_blank"><i class="fab fa-spotify fa-2x"></i></a>&nbsp;
                                                    @endif
                                                    <a href="https://www.google.com.br/maps/search/{{$profile->logged_profile->lat ?? null}},{{$profile->logged_profile->lon ?? null}}/" target="_blank"><i class="fas fa-map-marker-alt fa-2x"></i></a>
                                                </div>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item text-justify"><strong>Bio:&nbsp;</strong>{{$profile->bio ?? null}}</li>
                                            </ul>
                                        </div>
                                    </div>
            
                                </div>
                            @endforeach
        
                        @else
                            <h2>Nenhum resultado encontrado.</h2>
                        @endif
                    </div>

                </div>
                <div class="card-footer">
                    @if(isset($profiles) && $profiles->count()>0)
                        {!! $profiles->appends(Request::only(['nome'=>'nome', 'bio'=>'bio', 'idade'=>'idade', 'genero'=>'genero', 'distancia'=>'distancia', 'orderby'=>'orderby']))->links() !!}
                    @endif
                </div>
            </div>
            <br/>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>

    </body>

</html>