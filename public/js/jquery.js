$(document).ready(function(){

    $('.like').click(function(){
        var id = $(this).attr('data-link');
        like(id);
        //console.log(id);
        //$(this).children().css("color", "grey");
        //$(this).children().css("cursor", "auto");
     });

});

function like(id) {
    $.ajax({
        type: "GET",
        url: './like/'+id,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "html",
        beforeSend: function() {
        },
        complete: function() {
            $("#loader"+id).show();
        },
        success: function(response){
            like_response(response, id);
            $("#loader"+id).hide();
        }
    })
};

function like_response(response, id) {
    console.log(jQuery.parseJSON(response));
    
    if(jQuery.parseJSON(response).success){
        alert("SUCESSO! IMPLEMENTAR LOADER QUE COBRE TODO O CARD, E IF SUCESSO, MOSTRA UM ICON DE CHECK VERDE ANTES DE DAR HIDE NO CARD");
        $("#card-"+id).hide(300);
    }else{
        alert("ERRO! IMPLEMENTAR TRATATIVA.");
    }
    
}
