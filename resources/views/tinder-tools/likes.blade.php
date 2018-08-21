@extends('tinder-tools.templates.template')
@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Londrina+Outline" rel="stylesheet">
@endpush

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
            Somente os likes enviados desta plataforma
        </div>
        
        <div class="card-body">
            
            <div class="row row-eq-height">

                @if(isset($profiles) && $profiles->count()>0)

                    @foreach($profiles as $profile)
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" id="card-{{$profile->tinder_id}}" style="display:grid;margin-top:0.5em;margin-bottom:0.5em;">
    
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
                                                    <img class="d-block img-fluid rounded mx-auto d-block carousel-img" src="{{ str_replace('1080x1080','320x320',$imagem) }}" alt="">
                                                    <div class="carousel-caption d-none d-md-block over-carousel-div">
                                                        <img src="{{url('images/liked.png')}}">
                                                        </br><small>em 19/08/2018</small>
                                                    </div>
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

    @push ('scripts')
        <script src="{{url('js/jquery.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>
    @endpush
@endsection