$(document).ready(function(){

    $('.like').click(function(){
        var id = $(this).attr('data-link');
        like(id);
     });

     $('.carousel').carousel({
        interval: false
      })

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
