$(document).ready(function() {
  var PWD = "and4kids";
  var login_cookie = "dcc_login";
  var ca = document.cookie.split(";");
  for (var i=0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(login_cookie) == 0) {
      if (c.substring(login_cookie.length+1,c.length) == 1) {
        $("#sbt-nav-btn").css("display","none !important");
        $("#approve-nav-btn").css("display",$(".navBtnContainer").css("display"));
        $("#approve-nav-btn").removeClass("locked");
        $("#signin").hide();
        $(".close-signin-modal").click();
      }
    }
  }

  $("#menu-toggle").click(function() {
    if ($("#menu-toggle i:first-child").css("display") != "none") {
      $("#menu-toggle i:first-child").fadeOut(function() {
        $("#menu-toggle i:nth-child(2)").fadeIn();
      });
      $("#nav").css("height","100%");
      $("#nav").css("width","100%");
      $(".navBtnContainer").css("display","block");
      $(".navBtnContainer.locked").css("display","none");
    } else {
      $("#menu-toggle i:nth-child(2)").fadeOut(function() {
        $("#menu-toggle i:first-child").fadeIn();
      });
      $(".navBtnContainer").css("display","none");
      $("#nav").css("height","auto");
      $("#nav").css("width","auto");
    }
  });

  $("#submit-signin").click(function() {
    if ($("#pwd").val() == PWD) {
      $("#sbt-nav-btn").css("display","none !important");
      $("#approve-nav-btn").css("display",$(".navBtnContainer").css("display"));
      $("#approve-nav-btn").removeClass("locked");
      $("#signin").hide();
      $(".close-signin-modal").click();
      var d = new Date();
      d.setTime(d.getTime + (60*60*1000));
      document.cookie = login_cookie + "=1;expires=" + d.toUTCString(); + ";";
    }
  });

  
  $("#signin").animatedModal({
    modalTarget: "signin-modal",
    color: "rgba(255,255,255,0.8)",
    afterOpen: function() {
    },
    afterClose: function() {
    }
  });

  $("#request-submit").click(function() {
    checkAuth();
  });
});
