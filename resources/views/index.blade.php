<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../../../favicon.ico">
        <title>TESTE</title>
        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="container">
            <div class="row row-eq-height">

                @foreach($users as $user)
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="display:grid;margin-top:0.5em;margin-bottom:0.5em;">

                        <div class="card">
                            <!--
                            <img class="img-thumbnail rounded img-thumbnail mx-auto d-block" style="max-height:250px;" src="{{json_decode($user->photos)[0]->url ?? null}}">
                            -->
                            
                            <div id="carousel-{{$user->id}}" class="carousel slide" data-ride="carousel">
                    
                                <ol class="carousel-indicators">
                                    @if(!empty($user->photos) && count(json_decode($user->photos))>0)
                                        @foreach( json_decode($user->photos) as $imagem )
                                            <li data-target="#carousel-{{$user->id}}" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                                        @endforeach
                                    @else
                                        <li data-target="#carousel-{{$user->id}}" data-slide-to="0" class="active"></li>
                                    @endif
                                </ol>
                                <div class="carousel-inner" role="listbox">
                                    @if(!empty($user->photos) && count(json_decode($user->photos))>0)
                                        @foreach( json_decode($user->photos) as $imagem )
                                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                <img class="d-block img-fluid rounded img-thumbnail mx-auto d-block" style="max-height:400px;" src="{{ $imagem->url ?? null }}" alt="">
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="carousel-item active">
                                            <img class="d-block img-fluid rounded" src="http://support.yumpu.com/en/wp-content/themes/qaengine/img/default-thumbnail.jpg" alt="">
                                        </div>
                                    @endif
                                </div>
                                <a class="carousel-control-prev" href="#carousel-{{$user->id}}" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Anterior</span>
                                </a>
                                <a class="carousel-control-next" href="#carousel-{{$user->id}}" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Próxima</span>
                                </a>
                                
                            </div>
                            
                            <div class="card-body">
                                <span></span>
                                <h5 class="card-title">
                                    {{$user->name ?? null}}, 
                                    {{(Carbon\Carbon::today()->year - Carbon\Carbon::parse($user->birth_date)->year)  ?? null}}, 
                                    {{round(($user->distance_mi * 1.60934), 0) ?? null}} Km
                                </h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Bio:&nbsp;</strong>{{$user->bio ?? null}}</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>

    </body>

</html>