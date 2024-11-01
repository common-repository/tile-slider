jQuery(document).ready(function($){
    $(".tile-slideshow").each(function(){
        $(this).tileSlider(window[$(this).attr('id')]);
    })
})