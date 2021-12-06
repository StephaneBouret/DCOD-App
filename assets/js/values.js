$(document).ready(function () {

    $(window).on('scroll', function() {
        // console.log( $(this).scrollTop() );
        var ourValues = $('.animate.fadeInUp');
        if ($(window).scrollTop() > 250) {
            ourValues.removeClass('revealed');
        }
        var ourValuesFindings = $('.findings-content');
        if ($(window).scrollTop() > 950) {
            ourValuesFindings.removeClass('displayed');
        }
    });
});