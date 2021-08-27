jQuery.noConflict();
jQuery(document).ready(function($) {

    $(".snillrik-waykestandardbox-item").on("click", function() {

        let bilurl = $(this).attr("data-bilurl");
        window.location = bilurl;
    });

    $("#snillrik-waykesearch-reset").on("click", function() {

        $("#snillrik-waykesearch select").prop('selectedIndex', 0);
        $("#snillrik-waykesearch select").css("color", "#9e9e9e");

        window.location.href = $("#snillrik-waykesearch").attr('action');
    });

    $("#snillrik-waykesearch select").on("change", function() {

        if ($(this).val() == "")
            $(this).css("color", "#9e9e9e");
        else
            $(this).css("color", "#000");
    });

    $("#snillrik-waykesearch select").on("change", function() {
        var query = $("#snillrik-waykesearch").serializeArray().filter(function(i) {
            if (i.value != '')
                return i.value;
        });

        window.location.href = $("#snillrik-waykesearch").attr('action') + (query ? '?' + $.param(query) : '');
    });

    $("#snillrik-waykesearch").on("submit", function(e) {
        e.preventDefault();
        let qonly = "";
        var query = $(this).serializeArray().filter(function(i) {
            if (i.value != '') {
                if (i.name == "q" && i.value != "")
                    qonly = i.value + "*";
                return i.value;
            }
        });
        if (qonly != "") {
            window.location.href = $(this).attr('action') + "?q=" + qonly;
        } else {
            window.location.href = $(this).attr('action') + (query ? '?' + $.param(query) : '');
        }

    });

    $("body").on('change', '.snillrik-waykesearch-rangewrap [type=range]', function() {
        let thisval = $(this).val();
        let textinput = $(this).parent().find("input").first();
        if ($(this).attr("max") == thisval)
            thisval += "+";

        textinput.val(thisval);

        if ($(this).parent().hasClass("snillrik-waykesearch-rangewrap-min")) {
            let textinput2 = $(this).parent().siblings(".snillrik-waykesearch-rangewrap-max").find("input").first();
            let sliderval = $(this).parent().siblings(".snillrik-waykesearch-rangewrap-max").find("input").last();
            let valet = textinput2.val();

            if (parseInt(thisval) > parseInt(valet)) {
                textinput2.val(thisval);
                sliderval.val(thisval);
            }
        } else if ($(this).parent().hasClass("snillrik-waykesearch-rangewrap-max")) {
            let textinput2 = $(this).parent().siblings(".snillrik-waykesearch-rangewrap-min").find("input").first();
            let sliderval = $(this).parent().siblings(".snillrik-waykesearch-rangewrap-min").find("input").last();
            let valet = textinput2.val();

            if (parseInt(thisval) < parseInt(valet)) {
                textinput2.val(thisval);
                sliderval.val(thisval);
            }
        }

        var query = $("#snillrik-waykesearch").serializeArray().filter(function(i) {
            if (i.value != '')
                return i.value;
        });

        window.location.href = $("#snillrik-waykesearch").attr('action') + (query ? '?' + $.param(query) : '');
    });

});