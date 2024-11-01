(function ($) {
    var $vSliderEffect = false;
    $vSliderEffect = vslider.effect;
    if($vSliderEffect == 'fade'){
        $vSliderEffect = true
    }else{
        $vSliderEffect = false;
    }
    var $vSliderDelay = parseInt(vslider.delay);
    var $cSliderDuration = parseInt(vslider.duration);
    var $vSliderHeight = parseInt(vslider.height);
    $( window ).resize(function() {
        if( $( window ).width() > 1180 ){
            $('#vs_slider').css('max-height', $vSliderHeight);  
        }
    });
    $(function () {
       $('#vs_slider').slick({
          dots: true,
          infinite: true,
          speed: $vSliderDelay,
          slidesToShow: 1,
          autoplay: true,
          autoplaySpeed: $cSliderDuration,
          arrows: true,
          fade: $vSliderEffect
        });
	});
}(jQuery));
