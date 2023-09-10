if ($(window).width() > 767) {
  $("#navbar").removeClass("bg-primary");
  $("#navbar .nav-item a").addClass("btn-outline-dark");
  $("#navbar .nav-item a").removeClass("btn-outline-light");
}
$(document).ready(function () {
  $(".endeos-unslider").unslider({
    speed: 800,
    delay: 2000,
    keys: true,
    dots: true,
    fluid: true,
  });
  if ($(window).width() > 767) {
    if ($(window).scrollTop() > 1) {
      if (!$("#navbar").hasClass("nav-sticky")) {
        $("#navbar").addClass("nav-sticky");
        $("#navbar").addClass("bg-primary");
        $("#navbar .nav-item a").addClass("btn-outline-light");
        $("#navbar .nav-item a").removeClass("btn-outline-dark");
      }
    }
    $(window).bind("scroll", function () {
      if ($(window).scrollTop() > 1) {
        if (!$("#navbar").hasClass("nav-sticky")) {
          $("#navbar").addClass("nav-sticky");
          $("#navbar").addClass("bg-primary");
          $("#navbar .nav-item a").addClass("btn-outline-light");
          $("#navbar .nav-item a").removeClass("btn-outline-dark");
        }
      } else {
        $("#navbar").removeClass("nav-sticky");
        $("#navbar").removeClass("bg-primary");
        $("#navbar .nav-item a").addClass("btn-outline-dark");
        $("#navbar .nav-item a").removeClass("btn-outline-light");
      }
    });
  }
});
