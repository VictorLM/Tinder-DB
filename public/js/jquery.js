$(document).ready(function(){
    recs();
    
    $('.like').click(function(){
        var id = $(this).attr('data-link');
        like(id);
    });
    //BS CAROUSSEL NOT AUTO CHANGE
    $('.carousel').carousel({
        interval: false
    })
    //SPOTIFY POPOVER
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
    //MARK.JS//
    if($( "input[name='nome']" ).val() != ""){
        var context = document.querySelectorAll(".nome");
        var instance = new Mark(context);
        instance.mark($( "input[name='nome']" ).val());
    }
    if($( "input[name='bio']" ).val() != ""){
        var context = document.querySelectorAll(".bio");
        var instance = new Mark(context);
        instance.mark($( "input[name='bio']" ).val());
    }
    if($( "input[name='idade']" ).val() != ""){
        var context = document.querySelectorAll(".idade");
        var instance = new Mark(context);
        instance.mark($( "input[name='idade']" ).val());
    }
    //FIM MARK.JS//
});

function like(id) {
    $.ajax({
        type: "GET",
        url: './like/'+id,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "html",
        beforeSend: function() {
            $("#div-loader-"+id).show(200);
            $("#loader-"+id).show(200);
        },
        complete: function() {
        },
        success: function(response){
            like_response(response, id);
        }
    })
};

function like_response(response, id) {
    //console.log(jQuery.parseJSON(response));
    if(jQuery.parseJSON(response).success){
        $("#loader-"+id).hide(200);
        $("#liked-"+id).show(200);
        $("#card-"+id).delay(2000).hide(200);
    }else{
        $("#loader-"+id).hide(200);
        $("#div-loader-"+id).hide(200);
        alert("ERRO! ATUALIZE A PÁGINA OU TENTE NOVAMENTE MAIS TARDE.");
    }
}

function recs() {
    $.ajax({
        type: "POST",
        url: './tinder-tools/recs',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: "html",
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(response){
            recs_response(response);
        }
    })
};

function recs_response(response) {
    console.log(jQuery.parseJSON(response));
}
