<?php
  $user = $_SERVER['REMOTE_USER'];
  echo '$user';
  if (strcmp($user,"rdanderson") {
    echo "<script type='text/javascript'>",
         "$('#signin').hide();",
         "$('.close-signin-modal').click();",
         "</script>";
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width">
    <title>Destin Condo Calendar</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/schedule.ico" />
    <link rel="stylesheet" type="text/css" href="css/app.css" />
    <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto|Muli" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css">
    <script src="https://use.fontawesome.com/23995a4842.js"></script>
    <!-- google calendar
    <script src="https://apis.google.com/js/client.js?onload=checkAuth"></script>
    -->
  </head>
  <body onload="">
    <a href="#signin-modal" class="button" id="signin">Sign In</a>
    <div id="topbar">
      <!--
      <video width="100%" autoplay loop muted>
        <source src="view.mp4" type="video/mp4"></source>
      </video>
      -->
      <div class="overlay">
        <h1><a href="index.html">Destin Condo Calendar</a></h1>
      </div>
    </div>
    <div id="nav">
      <button id="menu-toggle"><i class="fa fa-bars"></i><i class="fa fa-arrow-left"></i></button>
      <div id="view-nav-btn" class="navBtnContainer">
        <a href="calendar_view.html" class="button"><i class="fa fa-calendar fa-2x"></i><span>View Calendar</span></a>
      </div>
      <div id="reserve-nav-btn" class="navBtnContainer">
        <a href="make_reservation.html" class="button"><i class="fa fa-calendar-plus-o fa-2x"></i><span>Make a Reservation</span></a>
      </div>
      <div id="sbt-nav-btn" class="navBtnContainer">
        <a href="http://www.thesilverbeachtowersresort.com/" class="button"><i class="fa fa-question-circle-o fa-2x"></i><span>Silver Beach Towers</span></a>
      </div>
      <div id="approve-nav-btn" class="navBtnContainer locked">
        <a href="approval.html" class="button"><i class="fa fa-check-circle-o fa-2x"></i><span>Approve Requests</span></a>
      </div>
      <div id="videos-nav-btn" class="navBtnContainer">
        <a href="videos.html" class="button"><i class="fa fa-play-circle-o fa-2x"></i><span>Videos</span></a>
      </div>
      <div id="beachcam-nav-btn" class="navBtnContainer">
        <a href="http://gulfcoastbeachcams.com/cameras/thebackporch-destin" class="button"><i class="fa fa-video-camera fa-2x"></i><span>Beach Cam</span></a>
      </div>
      <div id="weather-nav-btn" class="navBtnContainer">
        <a href="destin_weather.html" class="button"><i class="fa fa-sun-o fa-2x"></i><span>Weather</span></a>
      </div>
    </div>
    <div id="main">
      <div id="motto">
        <p>&#34;It better be as clean when you leave as it was when you got there.&#34;<br><font style="color: #666666;text-shadow: none;"> - The Randy</font></p>
      </div>
      <div id="greeting">
        <div>
          <h4>A word from&#46;&#46;&#46;</h4>
          <h1><strong>Randy and Gretchen</strong></h1>
        </div>
        <div>
          <p>There have been several shark sightings in the water near the beach so I advise any looking to stay here in the next several weeks to exercise caution when swimming. Also, I have put a copy of the SBT event schedule on the fridge for your convenience. Please remember, we have but one rule for your stay, you must complete one puzzle and take a picture. Failure to comply will result in banishment from the condo.</p>
        </div>
      </div>
      <div id="photos">
        <img src="images/fam-sunny.jpeg" />
        <img src="images/fearless-leader.jpeg" />
        <img src="images/sea-turtle.jpeg" />
        <img src="images/joe-fish.jpeg" />
      </div>
    </div>
    <footer>The Anderson's Condo Calendar &copy; Ryan Carl, 2016</footer>
	
    <div id="signin-modal">
     <div style="text-align:right;padding-right:30px"><a id="close-modal-btn" class="close-signin-modal">&times;</a></div>
      <div class="modal-content">
        <div class="panel">
          <!--
          <label for="un">Username
            <input id="un" type="text" />
          </label>
          -->
          <label for="pwd">Password
            <input id="pwd" type="password" />
          </label>
          <button id="submit-signin">Sign In</button>
        </div>
      </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/animatedModal.min.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>