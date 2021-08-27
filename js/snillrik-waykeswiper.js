jQuery(document).ready(function ($) {
    var swiper = new Swiper('.swiper-container',{
        el: '.swiper-container',
        initialSlide: 0,
        spaceBetween: 10,
        slidesPerView: swipeparams.slidesperview,
        centeredSlides: true,
        slideToClickedSlide: true,
        effect: swipeparams.effect,
        
        scrollbar: {
          el: '.swiper-scrollbar',
        },
        mousewheel: {
          enabled: false,
        },
        keyboard: {
          enabled: true,
        },

        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
    });

/*     $(".snillrik_flashifybox").on("click",function(){
        let url = $(this).find(".flashify_url").attr("href");
        //console.log(url);
        window.location = url;
    }); */
});