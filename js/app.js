$(document).ready(function() {
  // adjust for safari
  var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);
  if (isSafari) {
    $("#nav a.button i").css("display","none");
    $("#nav a.button span").css("margin-top","8px");
  }

  /*
  var ca = document.cookie.split(";");
  for (var i=0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(login_cookie) == 0) {
      if (c.substring(login_cookie.length+1,c.length) == 1) {
        $("#reserve-nav-btn").css("display","none");
        $("#approve-nav-btn").css("display",$(".navBtnContainer").css("display"));
        $("#approve-nav-btn").removeClass("locked");
        $("#signin").hide();
        $(".close-signin-modal").click();
      }
    }
  }
  */

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
  
  /* 
  $("#submit-signin").click(function() {
    if ($("#pwd").val() == PWD) {
      $("#reserve-nav-btn").css("display","none");
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
      $("#pwd").focus();
    },
    afterClose: function() {
    }
  });
  */

  $("#result-btn").animatedModal({
    modalTarget: "result-modal",
    color: "rgba(255,255,255,0.8)",
  });

  $("#request-submit").click(function() {
    checkAuth();
  });

});

function checkUser() {
  if (gapi.auth2) {
    if (gapi.auth2.isSignedIn.get() == true) {
    var profile = auth2.currentUser.get().getBasicProfile();
      if (profile.getEmail() == "rcarl94@gmail.com") {
        unlock();
      }
    }
  }
}

function onSignIn(googleUser) {
  var profile = googleUser.getBasicProfile();
  if (profile.getEmail() == "rcarl94@gmail.com") {
    unlock();
  }
}

function unlock() {
  $("#reserve-nav-btn").css("display","none");
  $("#approve-nav-btn").css("display",$(".navBtnContainer").css("display"));
  $("#approve-nav-btn").removeClass("locked");
}
