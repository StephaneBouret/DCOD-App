$(document).ready(function () {
   $(window).on('load', function(){
    var win = $(this); //this = window
    if (win.innerWidth()< 1024) { $('#collapseOne').removeClass('show'); }
});
});