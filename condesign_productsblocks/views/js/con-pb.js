$('.con-pb__products').each(function(){
    $(this).slick({
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
        vertical: true,
        prevArrow:"<button type=\"button\" class=\"btn slick-prev\"><span class=\"material-icons\">keyboard_arrow_up</span></button>",
        nextArrow:"<button type=\"button\" class=\"btn slick-next\"><span class=\"material-icons\">keyboard_arrow_down</span></button>"
    });
});