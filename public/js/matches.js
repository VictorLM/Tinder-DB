$(document).ready(function(){
    //VERIFICA OS LIKES E SUPERS RESTANTES
    likes_remaining();
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
});
 function first_access_matches() {
    $.ajax({
        type: "GET",
        url: '/tinder-tools/ajax-matches',
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
        $("#likes_remaining").text(jQuery.parseJSON(response)['rating']['likes_remaining']);
        $("#super_likes_remaining").text(jQuery.parseJSON(response)['rating']['super_likes']['remaining']);
    }else{
        alert("Erro ao checar seu perfil. A página será reinciada.");
        location.reload();
    }
}