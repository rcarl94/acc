<?php
  $creds = json_decode(getenv('GOOGLE_CREDS'));
  $SIGNIN_CLIENT_ID = $creds->web->client_id;
?>
<!DOCTYPE html>
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
      <div id="motto">
        <p>&#34;It better be as clean when you leave as it was when you got there.&#34;<br><font style="color:#777;text-shadow:none;"> - The Randy</font></p>
      </div>
      <!--div id="greeting">
        <div>
          <h4>A word from&#46;&#46;&#46;</h4>
          <h1><strong>Randy and Gretchen</strong></h1>
        </div>
        <div>
          <p>Custom message goes here</p>
        </div>
      </div-->
      <div id="photos">
        <img src="/images/fam-sunny.jpeg" />
        <img src="/images/fearless-leader.jpeg" />
        <img src="/images/sea-turtle.jpeg" />
        <img src="/images/joe-fish.jpeg" />
      </div>
    </div>
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/animatedModal.min.js"></script>
    <script src="/js/app.js"></script>
  </body>
</html>
