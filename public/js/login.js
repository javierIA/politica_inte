(function($) {
    $('.login').on('click', function() {
        $('.login-model').fadeIn(400);
    });

    $('.login-close-switch').on('click', function() {
        $('.login-model').fadeOut(400,function(){
            $('#search-input').val('');
        });
    });

    $("body").on("click", ".languageDropdown > li > ul > li > a", function(e) {
        var $this = $(this);
        e.preventDefault();
        $this.parent("li").siblings("li").removeClass("active");
        $this.parent("li").addClass("active");
    });
})(jQuery);