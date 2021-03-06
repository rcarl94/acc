var ADMIN_EMAIL = "rdanderson1965@gmail.com";
//var ADMIN_EMAIL = "rcarl94@gmail.com";

$(document).ready(function() {
  // adjust for safari
  var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);
  if (isSafari) {
    $("#nav a.button i").css("display","none");
    $("#nav a.button span").css("margin-top","8px");
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
  
  $("#result-btn").animatedModal({
    modalTarget: "result-modal",
    color: "rgba(255,255,255,0.8)",
  });

  // Safari date picker
  if ( $('[type="date"]').prop('type') != 'date' ) {
    $('[type="date"]').datepicker({
      dateFormat: "yy-mm-dd",
      changeYear: true
    });
  }
});

function onSignIn(googleUser) {
  var profile = googleUser.getBasicProfile();
  if (profile.getEmail() == ADMIN_EMAIL) {
    unlock();
  }
  checkUser(profile);
}

function checkUser(profile) {
  var path = window.location.pathname;
  var thisPage = path.substring(path.lastIndexOf('/') + 1);
  if (thisPage == "approval" && profile.getEmail() != ADMIN_EMAIL) {
    // redirect to home page
    $("#main").html("<h2>You must be signed in as the site manager to view this page. Navigating to home.</h2>");
    setTimeout(function() {
      window.location.replace("/");
    }, 3000);
  }
}

function unlock() {
  $("#reserve-nav-btn").css("display","none");
  $("#approve-nav-btn").css("display",$(".navBtnContainer").css("display"));
  $("#approve-nav-btn").removeClass("locked");
}
