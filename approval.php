<?php
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
    //var_dump($tokenReturn);
    $accessToken = $token->access_token;
    return $accessToken;
}

function createPostArgsJSON($name,$email,$startdate,$enddate,$addinfo){
    $arg_list = func_get_args();
    foreach($arg_list as $key => $arg){
        $arg_list[$key] = urlencode($arg);
    }
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

function sendPostRequest($postargs, $token, $cal){
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

function sendDeleteRequest($eventid, $token, $cal){
    global $APIKEY;
    $request = 'https://www.googleapis.com/calendar/v3/calendars/' . $cal . '/events/' . $eventid;
    $postargs = ''; 
    $session = curl_init($request);
     
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, "DELETE");
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

$thecal = 'requests';
$message = "";
$calendars = array(
    'requests' => array('cid' => 'requests', 'name' => 'RanDestin Requests', 'id' => 'lhp36uvdi0hindme1qahpmp948@group.calendar.google.com', 'advance' => '0'),
    'public' => array('cid' => 'public', 'name' => 'RanDestin', 'id' => '2rtmtvb76ad0fkn5sib3cls00s@group.calendar.google.com', 'advance' => '0')
);
$APIKEY = 'AIzaSyCOIxu7rd-NJKRHlVC-4sZjc08IGnmGL9Y';
$token = getAccessToken();

if(isset($_POST['submit-approve'])){
    $RequestSignature = md5($_SERVER['REQUEST_URI'] . print_r($_POST, true));
    if ($_SESSION['LastRequest'] == $RequestSignature) {
      header('Location: '.$_SERVER['PHP_SELF']);
      die;
    } else {
        /*
         * Check to see if everything was filled out properly.
         */
        if(date('Ymd') > date('Ymd',strtotime($_POST['a-start-date']))){
            $message = 'You cannot make a booking in the past.  Please check your date.';
        }
        /*
         * Check to see if we are alowed to book this far in advance.
         */
        elseif(date('Ymd',strtotime($_POST['a-start-date'])) > date('Ymd',strtotime('+' . $calendars[$thecal]['advance'],strtotime($_POST['a-start-date'])))){
            $message = 'You cannot book that far into the future.  You can only book ' . $calendars[$thecal]['advance'] . ' in the future.  Please try again.';
            //$message .= date('Ymd',strtotime($_POST['a-start-date'])) . ' > ' . date('Ymd',strtotime('+' . $calendars[$thecal]['advance'],strtotime($_POST['a-start-date'])));
        }
        /*
         * Check and see if a booking already exists.
         */
        /*
        elseif(isTimeBooked($_POST['a-start-date'],$_POST['end-date'],$calendars[$thecal]['id'])){
            $message = 'Some of the dates you requested are not available. See the current reservations <a href="calendar_view.html">here</a>.';
        }
        */
        /*
         * Everything is good, submit the event.
         */
        else{
            $_SESSION['LastRequest'] = $RequestSignature;
            $request = "https://www.googleapis.com/calendar/v3/calendars/" . $calendars[$thecal]['id'] . "/events/" . $_POST['submit-approve'];
            $response = sendGetRequest($token, $request);
            $response = json_decode($response, true);
            $response['start']['date'] = $_POST['a-start-date'];
            $response['end']['date'] = $_POST['a-end-date'];

            $postargs = createPostArgsJSON($response['summary'],$response['attendees'][0]['email'],$response['start']['date'],$response['start']['date'],$response['description']);
            /*
            // send confirmation email
            $subject = "RanDestin Request";
            $headers = "MIME-Version: 1.0" . "\r\n" . "Content-type: text/html; charset=iso-8859-1" . "\r\n" . "From: destincondocalendar@outlook.com" . "\r\n" . "Reply-To: rdanderson1965@gmail.com" . "\r\n" . "X-Mailer: PHP/" . phpversion();
            $message = "You have requested to stay at the Anderson's condo in Silver Beach Towers from " . $_POST['a-start-date'] . " to " . $_POST['end-date'] . ". If you have any questions, please contact Randy at rdanderson1965@gmail.com.";
            $message = wordwrap($message, 70, "\r\n");
            $didSend = mail($_POST['email'],$subject,$message,$headers);
            if (!$didSend) {
              $result = "There was a problem sending confirmation to the email you entered. Please <a href='make_reservation.php'>re-submit</a> your request.";
            }
            */
            $result = sendPostRequest($postargs,$token,$calendars['public']['id']);
            $delresult = sendDeleteRequest($_POST['submit-approve'],$token,$calendars['requests']['id']);
            if (!empty($delresult))
              $result = 'ERROR';
        }
    }
} else if (isset($_POST['submit-deny'])) {
    $RequestSignature = md5($_SERVER['REQUEST_URI'] . print_r($_POST, true));
    if ($_SESSION['LastRequest'] == $RequestSignature) {
      header('Location: '.$_SERVER['PHP_SELF']);
      die;
    } else {
        $_SESSION['LastRequest'] = $RequestSignature;
        $request = "https://www.googleapis.com/calendar/v3/calendars/" . $calendars[$thecal]['id'] . "/events/" . $_POST['submit-deny'];
        $response = sendGetRequest($token, $request);
        $response = json_decode($response, true);
        $denyname = $response['summary'];
        $denyemail = $response['attendees'][0]['email'];
        $denyresult = sendDeleteRequest($_POST['submit-deny'],$token,$calendars['requests']['id']);
        if (empty($denyresult))
          $denyresult = 'SUCCESS';
    } 
}
// get all requests
$request = "https://www.googleapis.com/calendar/v3/calendars/" . $calendars[$thecal]['id'] . "/events";
$response = sendGetRequest($token, $request);
$response = json_decode($response, true);
$requests = $response['items'];
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width">
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="381768128087-43r547722rcs9gofl153gps80ap2l42p.apps.googleusercontent.com">
    <title>RanDestin</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/schedule.ico" />
    <link rel="stylesheet" type="text/css" href="css/app.css" />
    <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto:400,900" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css">
    <script src="https://use.fontawesome.com/23995a4842.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  </head>
  <body>
    <div class="g-signin2" id="signin" data-onsuccess="onSignIn"></div>
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
      <div id="approve-nav-btn" class="navBtnContainer">
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
      <div id='alert-bar' style='display:<?php echo empty($result) ? 'none':'block'; ?>;background:<?php echo ($result=='ERROR') ? '#f2c1c0':'#9be4b9'; ?>'>
<?php 
  if (!empty($result)) {
    if ($result == 'ERROR')
      echo 'There was a problem removing request: ' . $delresult;
    else {
      $json_result = json_decode($result,true);
      if ($json_result['status'] == 'confirmed')
        echo $json_result['summary'] . "'s dates have been moved to the reservations calendar.";
    }
  } else if (!empty($denyresult)) {
    if ($denyresult == 'SUCCESS')
      echo $denyname . "'s request was denied. Email " . $denyemail . " to suggest different dates.";
  }
?>
      </div>
<?php
    echo '<h1>' . count($requests) . ' pending request(s)</h1>';
    if (!empty($requests)) {
      foreach ($requests as $r) {
        echo "<div class='request' id='" . $r['id'] . "'>
                <div class='request-info'>
                  <div style='float:left;width:70px;'>Name<br>Email<br>Dates</div>
                  <div style='float:left'>
                    <span class='req-name'>" . $r['summary'] . "</span><br>
                    <span class='req-email'>" . $r['attendees'][0]['email'] . "</span><br>
                    <span><font class='req-start-date'>" . $r['start']['date'] . "</font> to <font class='req-end-date'>" . $r['end']['date'] . "</font></span><br><br>
                  </div>
                  <div class='req-add-info'>
                    Additional Info
                    <br><span style='margin-left:0'>" . $r['description'] . "</span>
                  </div>
                </div>
                <div class='button-container'> 
                  <button type='button' class='approve'><i class='fa fa-check'></i><span>Approve</span></button>
                  <button type='button' class='deny'><i class='fa fa-ban'></i><span>Deny</span></button>
                </div>
                <div style='clear:both'></div>
              </div>";
      }
    } 
?>
      <a href="#approve-modal" id="approve-modal-btn" style="display:none"></a>
      <form id="approve-form" name="approve-form" method="post" action="approval.php">
        <div id="approve-modal">
          <div style="text-align:right;padding-right:30px"><a id="close-modal-btn" class="close-approve-modal">&times;</a></div>
          <div class="modal-content">
            <div class="panel">
              Would you like to approve <span id="a-name"></span>'s request for <input id="a-start-date" name="a-start-date" type="date"></input> to <input id="a-end-date" name="a-end-date" type="date"></input>? 
              <div>
                <button type='submit' name='submit-approve' id="submit-approve">Yes</button>
                <button class="close-approve-modal" id="cancel-approve">No</button>
              </div>
            </div>  
          </div>  
        </div> 
      </form>

      <a href="#deny-modal" id="deny-modal-btn" style="display:none"></a>
      <form id="deny-form" name="deny-form" method="post" action="approval.php">
        <div id="deny-modal">
          <div style="text-align:right;padding-right:30px"><a id="close-modal-btn" class="close-deny-modal">&times;</a></div>
          <div class="modal-content">
            <div class="panel">
              Would you like to deny <span id="d-name"></span>'s request for <span id="d-start-date"></span> to <span id="d-end-date"></span>? 
              <div>
                <button type='submit' name='submit-deny' id="submit-deny">Yes</button>
                <button class="close-deny-modal" id="cancel-deny">No</button>
              </div>
            </div>  
          </div>  
        </div> 
      </form>
    </div>
    <script src="js/animatedModal.min.js"></script>
    <script src="js/app.js"></script>
    <script type="text/javascript">
      unlock();

      $("#approve-modal-btn").animatedModal({
        modalTarget: 'approve-modal',
        color: 'rgba(255,255,255,0.8)'
      });

      $("#deny-modal-btn").animatedModal({
        modalTarget: 'deny-modal',
        color: 'rgba(255,255,255,0.8)'
      });

      $(".approve").click(function(event) {
        var $req = $(event.target);
        $req = $req.parents(".request");
        $("#approve-modal .modal-content span#a-name").html($req.find(".req-name").html());
        $("#approve-modal .modal-content input#a-start-date").val($req.find(".req-start-date").html());
        $("#approve-modal .modal-content input#a-end-date").val($req.find(".req-end-date").html());
        $("#approve-modal .modal-content #submit-approve").val($req.attr("id"));
        $("#approve-modal-btn").click();
      });

      $(".deny").click(function(event) {
        var $req = $(event.target);
        $req = $req.parents(".request");
        $("#deny-modal .modal-content span#d-name").html($req.find(".req-name").html());
        $("#deny-modal .modal-content span#d-start-date").html($req.find(".req-start-date").html());
        $("#deny-modal .modal-content span#d-end-date").html($req.find(".req-end-date").html());
        $("#deny-modal .modal-content #submit-deny").val($req.attr("id"));
        $("#deny-modal-btn").click();
      });
    </script>
  </body>
</html>
