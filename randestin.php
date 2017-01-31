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
 "summary": "$name",
 "description": "$addinfo"
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
    $request = 'https://www.googleapis.com/calendar/v3/calendars/' . $cal . '/events?pp=1&key=' . $APIKEY;
     
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

$thecal = 'requests';
if(isset($_GET['cal'])){
    $thecal = addslashes($_GET['cal']);
}
$message = "";
$calendars = array(
    'requests' => array('cid' => 'requests', 'name' => 'RanDestin Requests', 'id' => 'lhp36uvdi0hindme1qahpmp948@group.calendar.google.com', 'advance' => '0')
);
$APIKEY = 'AIzaSyCOIxu7rd-NJKRHlVC-4sZjc08IGnmGL9Y';
 
if(isset($_POST['submit']) && $_POST['submit'] == 'Submit Request'){
    /*
     * Check to see if everything was filled out properly.
     */
    if(date('Ymd') > date('Ymd',strtotime($_POST['startdate']))){
        $message = 'You cannot make a booking in the past.  Please check your date.';
    }
    /*
     * Check to see if we are alowed to book this far in advance.
     */
    elseif(date('Ymd',strtotime($_POST['startdate'])) > date('Ymd',strtotime('+' . $calendars[$_POST['calendar']]['advance'],strtotime($_POST['startdate'])))){
        $message = 'You cannot book that far into the future.  You can only book ' . $calendars[$_POST['calendar']]['advance'] . ' in the future.  Please try again.';
        //$message .= date('Ymd',strtotime($_POST['startdate'])) . ' > ' . date('Ymd',strtotime('+' . $calendars[$_POST['calendar']]['advance'],strtotime($_POST['startdate'])));
    }
    /*
     * Check and see if a booking already exists.
     */
    /*
    elseif(isTimeBooked($_POST['startdate'],$_POST['enddate'],$calendars[$_POST['calendar']]['id'])){
        $message = 'Some of the dates you requested are not available. See the current reservations <a href="calendar_view.html">here</a>.';
    }
    */
    /*
     * Everything is good, submit the event.
     */
    else{
        $postargs = createPostArgsJSON($_POST['name'],$_POST['email'],$_POST['startdate'],$_POST['enddate'],$_POST['addinfo']);
        $token = getAccessToken();
        $result = sendPostRequest($postargs,$token,$calendars[$_POST['calendar']]['id']);
        //echo '<pre>' . $result . '</pre>';
    }
}
?>

<html>
<head>
 
</head>
<body>
 
<div class="callist">
<?php
    foreach($calendars as $cal){
        echo '<a href="randestin.php?cal=' . $cal['cid'] . '">' . $cal['name'] . '</a> | ';
    }
?>
</div>
 
<?php
    if(strlen($message) > 1){
        echo '<div class="message">';
        echo $message;
        echo '</div>';
    }
?>
 
<iframe src="https://www.google.com/calendar/embed?mode=WEEK&amp;showTitle=1&amp;showCalendars=0&amp;height=1000&amp;wkst=2&amp;bgcolor=%23FFFFFF&amp;src=<?php echo $calendars[$thecal]['id']; ?>&amp;color=%232952A3&amp;ctz=America%2FSt_Johns" style=" border-width:0 " width="800" height="600" frameborder="0" scrolling="no" id="califrame" onload="document.getElementById('califrame').contentWindow.scrollTo(0,document.getElementById('califrame').contentWindow.document.body.scrollHeight)"></iframe>
<form action="randestin.php?cal=<?php echo $thecal; ?>" method="post" name="booking">
    <input type="hidden" readonly="true" value="<?php echo $thecal; ?>" name="calendar"></input>
    Court: <input type="text" readonly="true" value="<?php echo $calendars[$thecal]['name']; ?>" name="calendarname"></input><br />
    Title of Booking: <input type="text" value="Booking for ...." name="name"></input><br />
    Start Date: <input type="text" value="<?php echo date('m-d-Y'); ?>" id="startdate" name="startdate"></input><br />
    End Date: <input type="text" value="<?php echo date('Y-m-d'); ?>" id="enddate" name="enddate"></input><br />
    Add Info: <input type="text" value="" name="addinfo"></input><br />
    <input type="submit" name="submit" value="Submit Request"></input>
</form>
</body>
</html>
