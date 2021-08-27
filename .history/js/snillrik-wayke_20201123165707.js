jQuery.noConflict();
jQuery(document).ready(function ($) {

    $(".snillrik-standardbox-item").on("click",function(){

        let bilurl = $(this).attr("data-bilurl");
        window.location = bilurl;
    });

    $("#snillrik-search-reset").on("click",function(){
        
        $("#snillrik-search select").prop('selectedIndex',0);
        $("#snillrik-search select").css("color","#9e9e9e");

        window.location.href = $("#snillrik-search").attr('action');
    });

    $("#snillrik-search select").on("change",function(){ 

        if($(this).val()=="")
            $(this).css("color","#9e9e9e");
        else
            $(this).css("color","#000");
    });

    $("#snillrik-search select").on("change", function() {
        var query = $("#snillrik-search").serializeArray().filter(function (i) {
            if(i.value != '')
                return i.value;
        });
      
         window.location.href = $("#snillrik-search").attr('action') + (query ? '?' + $.param(query) : '');
    });

    $("#snillrik-search").on("submit", function(e) {
        e.preventDefault();
        var query = $(this).serializeArray().filter(function (i) {
            if(i.value != '')
                return i.value;
        });
      
        window.location.href = $(this).attr('action') + (query ? '?' + $.param(query) : '');
    });

});