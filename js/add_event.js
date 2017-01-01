// Your Client ID can be retrieved from your project in the Google
// Developer Console, https://console.developers.google.com
var CLIENT_ID = '676176422503-dd9b8ur7quv02165c9m0106ffjtr69pn.apps.googleusercontent.com';
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
  var authorizeDiv = document.getElementById('authorize-div');
  if (authResult && !authResult.error) {
    // Hide auth UI, then load client library.
    authorizeDiv.style.display = 'none';
    loadCalendarApi();
  } else {
    // Show auth UI, allowing the user to initiate authorization by
    // clicking authorize button.
    authorizeDiv.style.display = 'inline';
  }
}

/**
 * Initiate auth flow in response to user clicking authorize button.
 *
 * @param {Event} event Button click event.
 */
function handleAuthClick(event) {
  gapi.auth.authorize(
    {client_id: CLIENT_ID, scope: SCOPES, immediate: false},
    handleAuthResult);
  return false;
}

/**
 * Load Google Calendar client library. List upcoming events
 * once client library is loaded.
 */
function loadCalendarApi() {
  gapi.client.load('calendar', 'v3', addEvent);
}

function addEvent() {
  var event = {
    'summary': 'Request from ' + $('#name').val(),
    'description': $('#add-info').val(),
    'attendees': [
      {
        'displayName': $('#name').val(),
        'email': $('#email').val()
      }
    ],
    'start': {
      'date': $('#start-date').val(),
    },
    'end': {
      'date': $('#end-date').val(),
    }
  };
  var request = gapi.client.calendar.events.insert({
    'calendarId': 'lhp36uvdi0hindme1qahpmp948@group.calendar.google.com',
    'resource': event
  });

  request.execute(function(e) {
    $('#request-result').html("Request for " + e.summary + " has been submitted");
    $('#result-btn').click();
    $('#request-form').trigger('reset');
  });
}
