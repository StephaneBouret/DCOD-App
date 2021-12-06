$(document).ready(function () {

  $(window).scroll(startCounter);
  function startCounter() {
    if ($(window).scrollTop()>100) {
      $(window).off("scroll", startCounter);
      $('.facts-box .counter').each(function () {
        $(this).prop('Counter', 0).animate({
          Counter: $(this).text()
        }, {
          duration: 4000,
          easing: 'swing',
          step: function (now) {
            $(this).text(Math.ceil(now));
          }
        });
      });
    };
  };

  $(window).scroll(function (event) {
    var scroll = $(window).scrollTop();
    console.log(scroll)
});

  // jQuery(function ($) {
  //   // $('.icon-box .counter h3').each(function () {
  //   //     $(this).prop('Counter', 0).animate({
  //   //       Counter: $(this).text()
  //   //     }, {
  //   //       duration: 4000,
  //   //       easing: 'swing',
  //   //       step: function (now) {
  //   //         $(this).text(Math.ceil(now));
  //   //       }
  //   //     });
  //   //   });
  //   // $('.icon-box hr').animate({width: '100%'}, {
  //   //   duration: 4000,
  //   //   easing: 'swing'}
  //   // );
  //   $('.facts-box .counter').each(function () {
  //     $(this).prop('Counter', 0).animate({
  //       Counter: $(this).text()
  //     }, {
  //       duration: 4000,
  //       easing: 'swing',
  //       step: function (now) {
  //         $(this).text(Math.ceil(now));
  //       }
  //     });
  //   });
  // });
});