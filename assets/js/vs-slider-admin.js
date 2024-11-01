(function ($) {
    $(function () {
       $('.cgt-nav-tabs').click(function(){
        $('.cgt-nav-tabs').removeClass('active');
        $(this).addClass('active');
        var board_id = 'vs-section-'+$(this).attr('id');
        $('.vs-section').hide();
        $('#'+board_id).show();
       });
	});
}(jQuery));
