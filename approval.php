<?php
    require 'util.php';
    session_start();

    date_default_timezone_set('UTC');

    $SIGNIN_CLIENT_ID = getenv('SIGNIN_CLIENT_ID');

    $req_cal = 'requests' . (getenv('PROFILE') == 'TEST' ? '-test' : '');
    $public_cal = 'public' . (getenv('PROFILE') == 'TEST' ? '-test' : '');
    $calendars = array(
        'requests' => array('cid' => 'requests', 'name' => 'RanDestin Requests', 'id' => 'lhp36uvdi0hindme1qahpmp948@group.calendar.google.com', 'advance' => '0'),
        'requests-test' => array('cid' => 'requests-test', 'name' => 'RanDestin Requests Test', 'id' => 'nmk0mqsknqaomgj9dl8ju65jcs@group.calendar.google.com', 'advance' => '0'),
        'public' => array('cid' => 'public', 'name' => 'RanDestin', 'id' => 'r9nuhp3j159sbnlpf7tch9hq7g@group.calendar.google.com', 'advance' => '0'),
        'public-test' => array('cid' => 'public-test', 'name' => 'RanDestin Test', 'id' => '5qgugkc9qkku4surq5qdbhtejk@group.calendar.google.com', 'advance' => '0')
    );

    function isTimeBooked($startdate, $enddate, $cal){
        $result = sendGetRequest('https://www.googleapis.com/calendar/v3/calendars/' . $cal . '/events?timeMax=' . $enddate . '&timeMin=' . $startdate . '&fields=items(end%2Cstart%2Csummary)&pp=1');
        if(strlen($result) > 5){
            return true;
        }
        else{
            return false;
        }
    }

    $RequestSignature = md5($_SERVER['REQUEST_URI'] . print_r($_POST, true));
    if (isset($_SESSION['LastRequest']) && $_SESSION['LastRequest'] == $RequestSignature) {
      header('Location: /approval');
      die;
    }

    $message = "";

    if(isset($_POST['submit-approve'])){
        /*
        // Check and see if a booking already exists.
        if (isTimeBooked($_POST['a-start-date'],$_POST['end-date'],$calendars[$req_cal]['id'])){
            $message = 'Some of the dates you requested are not available. See the current reservations <a href="calendar_view.html">here</a>.';
        }
        */
        // Everything is good, submit the event.
        //else {
            $_SESSION['LastRequest'] = $RequestSignature;
            $request = "https://www.googleapis.com/calendar/v3/calendars/" . $calendars[$req_cal]['id'] . "/events/" . $_POST['submit-approve'];
            $response = sendGetRequest($request);
            $response = json_decode($response, true);
            $response['start']['date'] = $_POST['a-start-date'];
            $response['end']['date'] = $_POST['a-end-date'];

            $postargs = createCalPost($response['summary'],$response['attendees'][0]['email'],$response['start']['date'],$response['end']['date'],$response['description']);
            // add to public calendar
            $result = sendPostRequest($postargs, $calendars[$public_cal]['id']);
            // remove from requests calendar
            $delresult = sendDeleteRequest($_POST['submit-approve'],$calendars[$req_cal]['id']);
            if (!empty($delresult))
                $result = 'ERROR';
        //}
    } else if (isset($_POST['submit-deny'])) {
        $_SESSION['LastRequest'] = $RequestSignature;
        $request = "https://www.googleapis.com/calendar/v3/calendars/" . $calendars[$req_cal]['id'] . "/events/" . $_POST['submit-deny'];
        $response = sendGetRequest($request);
        $response = json_decode($response, true);
        $denyname = $response['summary'];
        $denyresult = sendDeleteRequest($_POST['submit-deny'], $calendars[$req_cal]['id']);
        if (empty($denyresult))
          $denyresult = 'SUCCESS';
    }
    // get all requests
    $request = "https://www.googleapis.com/calendar/v3/calendars/" . $calendars[$req_cal]['id'] . "/events";
    $response = sendGetRequest($request);
    $response = json_decode($response, true);
    $requests = $response['items'];
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
    <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto:400,900" rel="stylesheet">
    <script src="js/jquery-3.3.1.min.js"></script>
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
      <div id="approve-nav-btn" class="navBtnContainer">
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
<?php 
    if (!empty($result)) {
      echo "<div id='alert-bar' style='background:" . ($result == 'ERROR' ? '#f2c1c0':'#9be4b9') . "'>";
      if ($result == 'ERROR')
        echo 'There was a problem removing request: ' . $delresult;
      else {
        $json_result = json_decode($result,true);
        if ($json_result['status'] == 'confirmed')
          echo $json_result['summary'] . "'s dates have been moved to the reservations calendar";
      }
    } else if (!empty($denyresult)) {
      if ($denyresult == 'SUCCESS')
        echo $denyname . "'s request was denied";
    }
?>
      </div>
<?php
    echo '<h2 style="text-align:center">' . count($requests) . ' pending request(s)</h2>';
    if (!empty($requests)) {
      foreach ($requests as $request) {
        echo "<div class='request' id='" . $request['id'] . "'>
                <div class='request-info'>
                  <div style='float:left;width:70px;'>Name<br>Dates</div>
                  <div style='float:left'>
                    <span class='req-name'>" . $request['summary'] . "</span><br>
                    <!--span class='req-email'>" . $request['attendees'][0]['email'] . "</span><br-->
                    <span><font class='req-start-date'>" . $request['start']['date'] . "</font> to <font class='req-end-date'>" . $request['end']['date'] . "</font></span><br><br>
                  </div>
                  <!-- not available in request any longer...
                   div class='req-add-info'>
                    Additional Info
                    <br><span style='margin-left:0'>" . //$request['description'] . 
                    "</span>
                  </div-->
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
      <form id="approve-form" name="approve-form" method="post" action="/approval">
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
      <form id="deny-form" name="deny-form" method="post" action="/approval">
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
