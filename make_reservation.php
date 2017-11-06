<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* code below was adapted from http://cornempire.net/2012/01/15/part-3-oauth2-and-configuring-your-application-with-google/ */
session_start();
function getAccessToken(){
    $tokenURL = 'https://accounts.google.com/o/oauth2/token';
    $postData = array(
        'client_secret'=>'Yac8T9RFAAVcSYXD00vN0mbt',
        'grant_type'=>'refresh_token',
        'refresh_token'=>'1/PHPqVqbNe1AmQkjE5K-Ogn9eJkXKgxoJZP1IfW2Euxo',
        'client_id'=>'381768128087-fvkcbktqfcrmndtj9tbks7kt6lhh4cq4.apps.googleusercontent.com'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenURL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
    $tokenReturn = curl_exec($ch);
    $token = json_decode($tokenReturn);
    $accessToken = $token->access_token;
    return $accessToken;
}

function createPostArgsJSON($name,$email,$startdate,$enddate,$addinfo){
    $arg_list = func_get_args();
    foreach($arg_list as $key => $arg){
        $arg_list[$key] = urlencode($arg);
    }
    //$addinfo = "Email: " . $email . " - " . $addinfo;
    $postargs = <<<JSON
{
 "start": {
  "date": "{$startdate}"
 },
 "end": {
  "date": "{$enddate}"
 },
 "summary": "{$name}",
 "description": "{$addinfo}",
 "attendees": [
  {
   "email": "{$email}",
   "optional": "true"
  }
 ]
}
JSON;
    return $postargs;
}
 
function sendGetRequest($token,$request){
    global $APIKEY;
     
    $session = curl_init($request);
    curl_setopt ($session, CURLOPT_HTTPGET, true);
    curl_setopt($session, CURLOPT_HEADER, false); 
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLINFO_HEADER_OUT, false);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization:  Bearer ' . $token,'X-JavaScript-User-Agent: RanDestin'));
     
    $response = curl_exec($session);
     
    curl_close($session); 
    return $response;
}

function sendPostRequest($postargs,$token, $cal){
    global $APIKEY;
    $request = 'https://www.googleapis.com/calendar/v3/calendars/' . $cal . '/events?sendNotifications=true&pp=1&key=' . $APIKEY;
     
    $session = curl_init($request);
     
    // Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true); 
    // Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs); 
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false); 
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($session, CURLOPT_VERBOSE, true);
    curl_setopt($session, CURLINFO_HEADER_OUT, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:  application/json','Authorization:  Bearer ' . $token,'X-JavaScript-User-Agent: RanDestin'));
     
    $response = curl_exec($session);
     
    curl_close($session); 
    return $response;
}
/* under construction
function isTimeBooked($startdate,$enddate,$cal){
    global $APIKEY;
    $token = getAccessToken();
    $result = sendGetRequest($token, 'https://www.googleapis.com/calendar/v3/calendars/' . $cal . '/events?timeMax=' . $enddate . '&timeMin=' . $startdate . '&fields=items(end%2Cstart%2Csummary)&pp=1&key=' . $APIKEY);
    echo $result;
    if(strlen($result) > 5){
        return true;
    }
    else{
        return false;
    }
}
*/

$thecal = 'test';
$message = "";
$calendars = array(
    'requests' => array(
        'cid'     => 'requests',
        'name'    => 'RanDestin Requests',
        'id'      => 'lhp36uvdi0hindme1qahpmp948@group.calendar.google.com',
        'advance' => '0'
    ),
    'test' => array(
        'cid'     => 'test',
        'name'    => 'Test',
        'id'      => '0c4uu14q53o4n9te9k66vufmn4@group.calendar.google.com',
        'advance' => '0'
    )
);
$APIKEY = 'AIzaSyCOIxu7rd-NJKRHlVC-4sZjc08IGnmGL9Y';
 
if (isset($_POST['submit'])) {
    $RequestSignature = md5($_SERVER['REQUEST_URI'] . print_r($_POST, true));
    if ($_SESSION['LastRequest'] == $RequestSignature) {
      header('Location: ' . $_SERVER['PHP_SELF']);
      die;
    } else {
        /*
         * Check to see if everything was filled out properly.
         */
        if (date('Ymd') > date('Ymd',strtotime($_POST['start-date']))) {
            $message = 'You cannot make a booking in the past.  Please check your date.';
        }
        /*
         * Check to see if we are alowed to book this far in advance.
        elseif (date('Ymd',strtotime($_POST['start-date'])) > date('Ymd',strtotime('+' . $calendars[$thecal]['advance'],strtotime($_POST['start-date'])))){
            $message = 'You cannot book that far into the future.  You can only book ' . $calendars[$thecal]['advance'] . ' in the future.  Please try again.';
        }
        */
        /*
         * Check and see if a booking already exists.
         */
        /*
        elseif (isTimeBooked($_POST['start-date'],$_POST['end-date'],$calendars[$thecal]['id'])) {
            $message = 'Some of the dates you requested are not available. See the current reservations <a href="calendar_view.html">here</a>.';
        }
        */
        /*
         * Everything is good, submit the event.
         */
        else {
            $_SESSION['LastRequest'] = $RequestSignature;
            $postargs = createPostArgsJSON($_POST['name'],$_POST['email'],$_POST['start-date'],$_POST['end-date'],$_POST['add-info']);
            $token = getAccessToken();
            $result = sendPostRequest($postargs,$token,$calendars[$thecal]['id']);
        }
    }
}
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width">
    <title>RanDestin</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/schedule.ico" />
    <link rel="stylesheet" type="text/css" href="css/app.css" />
    <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/23995a4842.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css">
    <!--<script src="https://apis.google.com/js/client.js"></script>-->
  </head>
  <body>
    <div id="topbar">
      <div class="overlay">
        <h1><a href="index.html">RanDestin</a></h1>
      </div>
    </div>
    <div id="nav">
      <button id="menu-toggle"><i class="fa fa-bars"></i><i class="fa fa-arrow-left"></i></button>
      <div id="view-nav-btn" class="navBtnContainer">
        <a href="calendar_view.html" class="button"><i class="fa fa-calendar fa-2x"></i><span>View Calendar</span></a>
      </div>
      <div id="reserve-nav-btn" class="navBtnContainer">
        <a href="make_reservation.php" class="button"><i class="fa fa-calendar-plus-o fa-2x"></i><span>Make a Reservation</span></a>
      </div>
      <div id="sbt-nav-btn" class="navBtnContainer">
        <a href="http://www.thesilverbeachtowersresort.com/" class="button"><i class="fa fa-question-circle-o fa-2x"></i><span>Silver Beach Towers</span></a>
      </div>
      <div id="approve-nav-btn" class="navBtnContainer locked">
        <a href="approval.php" class="button"><i class="fa fa-check-circle-o fa-2x"></i><span>Pending Requests</span></a>
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
      <h2 id="request-result">
<?php 
  if (!empty($result)) {
    $json_result = json_decode($result,true);
    if ($json_result['status'] == 'confirmed') {
      echo 'Request Submitted';
?>
        <span style="display:block;font-size:16px;margin-top:20px;line-height:1.5;">
<?php
  echo "Name: " . $json_result['summary'] . "<br>Email: " . $json_result['attendees'][0]['email'] . "<br>Dates: " . $json_result['start']['date'] . " to " . $json_result['end']['date']; 
  echo "<p>Thank you for using RanDestin.com to request condo time. Please check your inbox (possibly spam) for confirmation of your request. You will receive an email when your request is approved.</p>";
?>
        </span>
<?php
    } else {
      if (!empty($message))
		echo $message;
	  else
		echo 'An error occurred in processing your request. Please go back and <a href="make_reservation.php">re-submit</a>.';
    }
?>
      </h2>
<?php
    } else {
?>
      <script type="text/javascript">
        $("#request-result").hide();
      </script>
      <h3 style="text-align:left">Please fill out the form below and submit your request</h3>
      <hr>
      <form id="request-form" name="request-form" action="make_reservation.php" method="post"> 
        <div>
          <label for="name">Name
            <input id="name" name="name" type="text" required="required" placeholder="First Last"></input>
          </label>
        </div>
        <div>
          <label for="email">Email
            <input id="email" name="email" type="email" required="required" placeholder="email@example.com"></input>
          </label>
        </div>
        <div>
          <label for="start-date" required="required">Arrive
            <input id="start-date" name="start-date" type="date"></input>
          </label>
        </div>
        <div>
          <label for="end-date" required="required">Leave
            <input id="end-date" name="end-date" type="date"></input>
          </label>
        </div>
        <label id="add-info-label" for="add-info" style="font-size:18px">Additional information, comments, or concerns
          <br>
          <textarea style="margin-top:15px" id="add-info" name="add-info"></textarea>
        </label>
        <input type="submit" id="request-submit" name="submit" value="Submit Request"></input>
      </form>
    </div>
<?php
    }
?>
    <script src="js/animatedModal.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
