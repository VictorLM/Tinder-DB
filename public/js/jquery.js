$(document).ready(function(){
    recs();
    likes_remaining();
    //setInterval(function(){recs();}, 10000);
    //BOTÃO LIKE
    $('.like').click(function(){
        var id = $(this).attr('data-link');
        like(id);
    });
    //BS CAROUSSEL NOT AUTO CHANGE
    $('.carousel').carousel({
        interval: false
    })
    //PROFILE E SPOTIFY POPOVER
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
    $('.popover').popover({
        trigger: 'focus',
        container: 'body'
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
    /*
    //JSCROLL//
    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });
    //FIM JSCROLL//
    */
});

function recs() {
    $.ajax({
        type: "GET",
        url: '/tinder-tools/recs',
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
//SE NÃO FOR USAR A RESPONSE, APAGAR DEPOIS
function recs_response(response) {
    //CHECAR SE HOUVE ERRO NA RESPOSTA
    console.log(jQuery.parseJSON(response));
}

function likes_remaining() {
    $.ajax({
        type: "GET",
        url: '/tinder-tools/likes-remaining',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: "html",
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(response){
            likes_remaining_response(response);
        }
    })
};

function likes_remaining_response(response) {
    if(response != 'erro'){
        console.log(jQuery.parseJSON(response)['rating']['likes_remaining']);
        $("#likes_remaining").text(jQuery.parseJSON(response)['rating']['likes_remaining']);
    }else{
        alert("Erro ao checar seu perfil. A página será reinciada.");
        location.reload();
    }
}
/*
function like(id) {
    $.ajax({
        type: "GET",
        url: '/tinder-tools/like/'+id,
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
    //CHECAR SE HOUVE ERRO NA RESPOSTA
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
*/
