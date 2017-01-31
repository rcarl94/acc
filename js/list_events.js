// Your Client ID can be retrieved from your project in the Google
// Developer Console, https://console.developers.google.com
var CLIENT_ID = '381768128087-43r547722rcs9gofl153gps80ap2l42p.apps.googleusercontent.com';
var SCOPES = ["https://www.googleapis.com/auth/calendar"];

/**
 * Check if current user has authorized this application.
 */
function checkAuth() {
  gapi.auth.authorize(
    {
      'client_id': CLIENT_ID,
      'scope': SCOPES.join(' '),
      'immediate': true
    }, handleAuthResult);
}

/**
 * Handle response from authorization server.
 *
 * @param {Object} authResult Authorization result.
 */
function handleAuthResult(authResult) {
  if (authResult && !authResult.error) {
    // load client library.
    loadCalendarApi();
  } else {
    // redirect to home page
    $("#requests").html("<h2>You must be signed in as the site manager to view this page. Navigating to home.</h2>");
    setTimeout(function() {
      window.location.replace("index.html");
    }, 2000);
  }
}

function runAuth() {
  gapi.auth.authorize({client_id: CLIENT_ID, scope: SCOPES, immediate: false}, handleAuthResult);
}

/**
 * Load Google Calendar client library. List upcoming events
 * once client library is loaded.
 */
function loadCalendarApi() {
  gapi.client.load('calendar', 'v3', listEvents);
}

function listEvents() {
  var request = gapi.client.calendar.events.list({
    'calendarId': 'lhp36uvdi0hindme1qahpmp948@group.calendar.google.com',
    'timeMin': (new Date()).toISOString(),
    'showDeleted': false,
    'singleEvents': true,
    'orderBy': 'startTime'
  });     

  request.execute(function(resp) {
    //$(".close-signin-modal").click();
    var events = resp.items;
    $("#main").prepend("<h2>" + events.length + " pending requests</h2>");
    if (events.length > 0) {
      for (i = 0; i < events.length; i++) {
        var event = events[i];
        var when = event.start.dateTime;
        if (!when) {
          when = event.start.date;
        }       
        $("#requests").append("<div class='request'><div class='request-info'><span>Name</span>" + event.summary + "<br><span>Dates</span>" + event.start.date + " to " + event.end.date + "<br><span>Additional Info</span>" + event.description + "</div><button class='approve'><i class='fa fa-check'></i><span>Approve</span></button><button class='deny'><i class='fa fa-ban'></i><span>Deny</span></button><div style='clear:both'></div></div>");
      }       
    } else {
      $("#requests").append('<h2>No pending requests</h2>');
    }       
  });
}
