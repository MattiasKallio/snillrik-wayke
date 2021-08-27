jQuery.noConflict();
jQuery(document).ready(function ($) {

    $(".snillrik-waykestandardbox-item").on("click",function(){

        let bilurl = $(this).attr("data-bilurl");
        window.location = bilurl;
    });

    $("#snillrik-waykesearch-reset").on("click",function(){
        
        $("#snillrik-waykesearch select").prop('selectedIndex',0);
        $("#snillrik-waykesearch select").css("color","#9e9e9e");

        window.location.href = $("#snillrik-waykesearch").attr('action');
    });

    $("#snillrik-waykesearch select").on("change",function(){ 

        if($(this).val()=="")
            $(this).css("color","#9e9e9e");
        else
            $(this).css("color","#000");
    });

    $("#snillrik-waykesearch select").on("change", function() {
        var query = $("#snillrik-waykesearch").serializeArray().filter(function (i) {
            if(i.value != '')
                return i.value;
        });
      
         window.location.href = $("#snillrik-waykesearch").attr('action') + (query ? '?' + $.param(query) : '');
    });

    $("#snillrik-waykesearch").on("submit", function(e) {
        e.preventDefault();
        var query = $(this).serializeArray().filter(function (i) {
            if(i.value != '')
                return i.value;
        });
      
        window.location.href = $(this).attr('action') + (query ? '?' + $.param(query) : '');
    });

});