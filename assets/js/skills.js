$(document).ready(function () {

    $(window).scroll(animateProgressBar);

    function animateProgressBar() {
        if ($(window).scrollTop() > 700) {
            $(window).off("scroll", animateProgressBar);
            var skills = {
                wt: 100
            };

            $.each(skills, function (key, value) {
                var skillbar = $("." + key);

                skillbar.animate({
                        width: value + "%"
                    },
                    500
                );
            });
        }
    }
});