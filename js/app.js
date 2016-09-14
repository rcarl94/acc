$(document).ready(function() {
  $("#menu-toggle").click(function() {
    if ($("#menu-toggle i:first-child").css("display") == "inline-block") {
      $("#menu-toggle i:first-child").fadeOut(function() {
        $("#menu-toggle i:nth-child(2)").fadeIn();
      });
      $("#nav").css("height","100%");
      $("#nav").css("width","100%");
      $(".navBtnContainer").css("display","block");
      $(".navBtnContainer").css("display","block");
    } else {
      $("#menu-toggle i:nth-child(2)").fadeOut(function() {
        $("#menu-toggle i:first-child").fadeIn();
      });
      $(".navBtnContainer").hide();
      $("#nav").css("height","auto");
      $("#nav").css("width","auto");
    }
  });
});
