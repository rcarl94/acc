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

  $("#submit-signin").click(function() {
    if ($("#pwd").val() == "and4kids") {
      $("#sbt-nav-btn").hide();
      $("#approve-nav-btn").css("display","inline-block");
      $("#signin").hide();
      $(".close-signin-modal").click();
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

  function listUpcomingEvents() {
    gapi.client.load('calendar', 'v3', listUpcomingEvents);

    var request = gapi.client.calendar.events.list({
      'calendarId': 'destincondocalendar@outlook.com',
      'timeMin': (new Date()).toISOString(),
      'showDeleted': false,
      'singleEvents': true,
      'orderBy': 'startTime'
    });

    request.execute(function(resp) {
      var events = resp.items;

      if (events.length > 0) {
        for (i = 0; i < events.length; i++) {
          var event = events[i];
          var when = event.start.dateTime;
          if (!when) {
            when = event.start.date;
          }
          $("#requests").append("<div class='request'><span>Name</span>" + event.summary + "<br><span>Dates</span>" + event.start.date + " - " + event.end.date + "<br><span>Additional Info</span>" + event.description + "</div>");
        }
      } else {
        $("#requests").append('No upcoming events found.');
      }

    });
  }
});
