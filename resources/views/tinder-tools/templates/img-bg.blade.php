<div id="fotos" class="images-bg">
    <div class="row">
        @if(!empty($profiles) && count($profiles)>0)
            @foreach($profiles as $profile)
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2 col-img-bg">
                <img src="{{str_replace('1080x1080','320x320',json_decode($profile->photos)[0])}}">
            </div>
            @endforeach
        @endif
    </div>
</div>