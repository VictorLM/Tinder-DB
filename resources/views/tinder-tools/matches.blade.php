@extends('tinder-tools.templates.template')
@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Londrina+Outline" rel="stylesheet">
@endpush

@section('content')
    @push ('scripts')
        <script src="{{url('js/matches.js')}}"></script>
    @endpush

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
        </div>
        
        <div class="card-body">
            
            <div class="row row-eq-height">

                @if(isset($matches) && $matches->count()>0)

                    @foreach($matches as $match)
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 profile">
    
                            <div class="card">

                                <div id="carousel-{{$match->profile->id}}" class="carousel slide" data-ride="carousel">
                        
                                    <ol class="carousel-indicators">
                                        @if(!empty($match->profile->photos) && count(json_decode($match->profile->photos))>0)
                                            @foreach(json_decode($match->profile->photos) as $imagem )
                                                <li data-target="#carousel-{{$match->profile->id}}" data-slide-to="{{$loop->index}}" class="{{$loop->first ? 'active' : ''}}"></li>
                                            @endforeach
                                        @else
                                            <li data-target="#carousel-{{$match->profile->id}}" data-slide-to="0" class="active"></li>
                                        @endif
                                    </ol>
                                    <div class="carousel-inner" role="listbox">
                                        @if(!empty($match->profile->photos) && count(json_decode($match->profile->photos))>0)
                                            @foreach(json_decode($match->profile->photos) as $imagem )
                                                <div class="carousel-item {{$loop->first ? 'active' : ''}}">
                                                    <img class="d-block img-fluid rounded mx-auto d-block carousel-img" src="{{str_replace('1080x1080','320x320',$imagem)}}" alt="">
                                                    <div class="carousel-caption d-none d-md-block over-carousel-div">
                                                        <img src="{{url('images/its-a-match.png')}}">
                                                        </br><small>{{Carbon\Carbon::parse($match->created_at)->format('d/m/Y')}}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <a class="carousel-control-prev" href="#carousel-{{$match->profile->id}}" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true">
                                            <i class="fas fa-arrow-alt-circle-left fa-2x"></i>
                                        </span>
                                        <span class="sr-only">Anterior</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carousel-{{$match->profile->id}}" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true">
                                            <i class="fas fa-arrow-alt-circle-right fa-2x"></i>
                                        </span>
                                        <span class="sr-only">Próxima</span>
                                    </a>
                                    
                                </div>
                                
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <h6>
                                                @if(!$match->profile->gender)
                                                    <i class="fas fa-mars fa-lg" style="color:#3366cc;" title="Masculino"></i>
                                                @elseif($match->profile->gender)
                                                    <i class="fas fa-venus fa-lg" style="color:#ff33cc;" title="Feminino"></i>
                                                @else
                                                    <i class="fas fa-genderless fa-lg" title="Outros"></i>
                                                @endif
                                                <span class="nome">{{$match->profile->name ?? null}}</span>, 
                                                <span class="idade">{{(Carbon\Carbon::today()->year - Carbon\Carbon::parse($match->profile->birth_date)->year-1)  ?? null}}</span>, 
                                                {{round(($match->profile->distance_mi * 1.60934), 0) ?? null}} Km 
                                                <a href="https://www.google.com.br/maps/search/{{$match->logged_profile->lat ?? null}},{{$match->logged_profile->lon ?? null}}/" target="_blank">daqui</a>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            @if(!empty(json_decode($match->profile->teasers)))
                                                @foreach(json_decode($match->profile->teasers) as $teaser)
                                                    @if($teaser->type != "instagram" && $teaser->type != "artists")
                                                        <p class="teasers">{{$teaser->string}}</p>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="hr-card"/>
                                    
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <a href="https://www.google.com.br/maps/search/{{$match->logged_profile->lat ?? null}},{{$match->logged_profile->lon ?? null}}/" target="_blank"><i class="fas fa-map-marker-alt fa-2x" title="Localização"></i></a>&nbsp;
                                            @if(!empty($match->profile->instagram))
                                                <a href="https://www.instagram.com/{{$match->profile->instagram}}" target="_blank"><i class="fab fa-instagram fa-2x" title="Instagram"></i></a>&nbsp;
                                            @endif
                                            @if(!empty(json_decode($match->profile->spotify)))
                                                    @php
                                                        $artistas= null;
                                                    @endphp
                                                @foreach(json_decode($match->profile->spotify) as $artist)
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
                                                @if(!$match->logged_profile->gender)
                                                    <i class="fas fa-mars fa-lg" style="color:#3366cc;" title="Masculino"></i>
                                                @elseif($match->logged_profile->gender)
                                                    <i class="fas fa-venus fa-lg" style="color:#ff33cc;" title="Feminino"></i>
                                                @else
                                                    <i class="fas fa-genderless fa-lg" title="Outros"></i>
                                                @endif
                                                , {{(Carbon\Carbon::today()->year - Carbon\Carbon::parse($match->logged_profile->birth_date)->year)  ?? null}}
                                            </p>
                                            <p class="teasers text-right"><b>Região:</b> {{$match->logged_profile->city}}</p>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item text-justify bio"><strong>Bio:&nbsp;</strong>{{$match->profile->bio ?? null}}</li>
                                    </ul>
                                </div>
                            </div>
    
                        </div>
                    @endforeach

                @else
                    @push('scripts')
                        <script type="text/javascript">
                            var url = $(location).attr('href'),
                            parts = url.split("/"),
                            last_part = parts[parts.length-1];
                            if(last_part == "matches"){
                                first_access_matches();
                                $('#loading').modal({backdrop: 'static', keyboard: false});
                                $('#loading').modal('show');
                            }else{
                                $("#404-h1").text("Nenhum resultado encontrado.");
                            }
                        </script>
                    @endpush
                    <h2 id="404-h1" style="padding-left: 1em;padding-right: 1em;"></h2>
                @endif
            </div>

        </div>
        <div class="card-footer">
            @if(isset($matches) && $matches->count()>0)
                {!! $matches->appends(Request::only(['nome'=>'nome', 'bio'=>'bio', 'idade'=>'idade', 'genero'=>'genero', 'distancia'=>'distancia','instagram'=>'instagram', 'orderby'=>'orderby']))->links() !!}
            @endif
        </div>

    </div>
@endsection