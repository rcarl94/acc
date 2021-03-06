<?php
  header('X-Frame-Options: ALLOW-FROM https://calendar.google.com');
  $creds = json_decode(getenv('GOOGLE_CREDS'));
  $SIGNIN_CLIENT_ID = $creds->web->client_id;

  $cal_id = (getenv('PROFILE') == 'TEST' ? '5qgugkc9qkku4surq5qdbhtejk%40group.calendar.google.com' : 'r9nuhp3j159sbnlpf7tch9hq7g%40group.calendar.google.com');
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width">
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="<?php echo $SIGNIN_CLIENT_ID; ?>">
    <title>RanDestin</title>
    <link rel="shortcut icon" type="image/x-icon" href="/images/schedule.ico" />
    <link rel="stylesheet" type="text/css" href="/css/app.css" />
    <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css">
    <script src="https://use.fontawesome.com/23995a4842.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  </head>
  <body>
    <div class="g-signin2" id="signin" data-onsuccess="onSignIn"></div>
    <div id="topbar">
      <div class="overlay">
        <h1><a href="/">RanDestin</a></h1>
      </div>
    </div>
    <div id="nav">
      <button id="menu-toggle"><i class="fa fa-bars"></i><i class="fa fa-arrow-left"></i></button>
      <div id="view-nav-btn" class="navBtnContainer">
        <a href="/calendar" class="button"><i class="fa fa-calendar fa-2x"></i><span>View Calendar</span></a>
      </div>
      <div id="reserve-nav-btn" class="navBtnContainer">
        <a href="/new-reservation" class="button"><i class="fa fa-calendar-plus-o fa-2x"></i><span>Make a Reservation</span></a>
      </div>
      <div id="sbt-nav-btn" class="navBtnContainer">
        <a href="http://www.thesilverbeachtowersresort.com/" class="button"><i class="fa fa-question-circle-o fa-2x"></i><span>Silver Beach Towers</span></a>
      </div>
      <div id="approve-nav-btn" class="navBtnContainer locked">
        <a href="/approval" class="button"><i class="fa fa-check-circle-o fa-2x"></i><span>Pending Requests</span></a>
      </div>
      <div id="videos-nav-btn" class="navBtnContainer">
        <a href="/videos" class="button"><i class="fa fa-play-circle-o fa-2x"></i><span>Videos</span></a>
      </div>
      <div id="beachcam-nav-btn" class="navBtnContainer">
        <a href="http://gulfcoastbeachcams.com/cameras/thebackporch-destin" class="button"><i class="fa fa-video-camera fa-2x"></i><span>Beach Cam</span></a>
      </div>
      <div id="weather-nav-btn" class="navBtnContainer">
        <a href="/weather" class="button"><i class="fa fa-sun-o fa-2x"></i><span>Weather</span></a>
      </div>
    </div>
    <div id="main">
      <h2>Reservations</h2>
      <p>Go to "Make a Reservation" to request a reservation</p>
      <!--iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;src=2rtmtvb76ad0fkn5sib3cls00s%40group.calendar.google.com&amp;color=%231B887A&amp;ctz=America%2FChicago" style="border-width:0" width="100%" height="600" frameborder="0" scrolling="no"></iframe-->
      <iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=<?php echo $cal_id; ?>&amp;color=%231B887A&amp;ctz=America%2FChicago" style="border-width:0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/animatedModal.min.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
