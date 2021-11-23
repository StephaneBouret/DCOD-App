$(document).ready(function () {

    $('#portfolio-flters li').click(function (e) {
        e.preventDefault();
        var value = $(this).attr('data-filter');
        var li = $(this);
        li.parent().find('li').removeClass('filter-active');
        li.addClass('filter-active')

        if (value == 'all') {
            $('.filter').show('1000');
        } else {
            $(".filter").not('.' + value).hide('3000');
            $('.filter').filter('.' + value).show('3000');
        }
    });

});