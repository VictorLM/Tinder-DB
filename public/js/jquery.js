$(document).ready(function(){
    //VERIFICA OS LIKES E SUPERS RESTANTES
    likes_remaining();
    //PEGAS AS RECS A CADA 30 SEGUNDOS
    setInterval(function(){recs();}, 30000);//VOLTAR ESSA FUNÇÃO
    //BOTÃO LIKE
    $('.like').click(function(){
        var id = $(this).attr('data-link');
        like(id);
    });
    //BOTÃO SUPER LIKE
    $('.super-like').click(function(){
        var id = $(this).attr('data-link');
        super_like(id);
    });
    //BOTÃO PASS
    $('.pass').click(function(){
        var id = $(this).attr('data-link');
        pass(id);
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

function first_access() {
    $.ajax({
        type: "GET",
        url: '/tinder-tools/first-access',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: "html",
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(response){
        }
    })
    location.reload();
};

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
    //console.log(jQuery.parseJSON(response));
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
        //console.log(jQuery.parseJSON(response));
        $("#likes_remaining").text(jQuery.parseJSON(response)['rating']['likes_remaining']);
        $("#super_likes_remaining").text(jQuery.parseJSON(response)['rating']['super_likes']['remaining']);
    }else{
        alert("Erro ao checar seu perfil. A página será reinciada.");
        location.reload();
    }
}

function like(id) {
    if($("#likes_remaining").text() > 0){
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
    }else{
        alert("Seu limite de Likes foi atingido! Contas free do Tinder tem um limite de 100 likes a cada 12 horas. Volte mais tarde. =)");
        likes_remaining();
    }
};

function like_response(response, id) {
    //CHECAR NO CONTROLLER SE HOUVE ERRO NA RESPOSTA
    if(jQuery.parseJSON(response).success){
        $("#loader-"+id).hide(200);
        $("#liked-"+id).show(200);
        $("#card-"+id).delay(2000).hide(200);
        $("#likes_remaining").text($("#likes_remaining").text()-1);
    }else{
        $("#loader-"+id).hide(200);
        $("#div-loader-"+id).hide(200);
        alert("ERRO! ATUALIZE A PÁGINA OU TENTE NOVAMENTE MAIS TARDE.");
    }
}

function super_like(id) {
    //DEPLOY CAMPO SUPER LIKES REMANINGGGGGGGGGG
    if($("#super_likes_remaining").text() > 0){
        $.ajax({
            type: "GET",
            url: '/tinder-tools/super-like/'+id,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "html",
            beforeSend: function() {
                $("#div-loader-"+id).show(200);
                $("#loader-"+id).show(200);
            },
            complete: function() {
            },
            success: function(response){
                super_like_response(response, id);
            }
        })
    }else{
        alert("Seu limite de Super Likes foi atingido! Contas free do Tinder tem direito a apenas 1 Super Like grátis. =(");
        likes_remaining();
    }
};

function super_like_response(response, id) {
    //CHECAR NO CONTROLLER SE HOUVE ERRO NA RESPOSTA
    if(jQuery.parseJSON(response).success){
        $("#loader-"+id).hide(200);
        $("#liked-"+id).show(200);
        $("#card-"+id).delay(2000).hide(200);
        $("#super_likes_remaining").text($("#likes_remaining").text()-1);
    }else{
        $("#loader-"+id).hide(200);
        $("#div-loader-"+id).hide(200);
        alert("ERRO! ATUALIZE A PÁGINA OU TENTE NOVAMENTE MAIS TARDE.");
    }
}

function pass(id) {
    $.ajax({
        type: "GET",
        url: '/tinder-tools/pass/'+id,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "html",
        beforeSend: function() {
            $("#div-loader-"+id).show(200);
            $("#loader-"+id).show(200);
        },
        complete: function() {
        },
        success: function(response){
            pass_response(response, id);
        }
    })
};

function pass_response(response, id) {
    //CHECAR NO CONTROLLER SE HOUVE ERRO NA RESPOSTA
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