<?php
    function setupClient($force) {
        $access = getenv('ACCESS_TOKEN');

        if (!$access || $force) {
            // Refresh the token if possible
            $creds = json_decode(getenv('GOOGLE_CREDS'));
            $refresh = $creds->web->refresh_token;
            if ($refresh) {
                $access = getAccessToken($refresh);
            } else {
                throw new Exception("Refresh token is missing");
            }
            putenv("ACCESS_TOKEN=$access");
        }
    }

    /* code below was adapted from http://cornempire.net/2012/01/15/part-3-oauth2-and-configuring-your-application-with-google/
    */
    function createCalPost($name, $email, $startdate, $enddate, $addinfo) {
        $arg_list = func_get_args();
        foreach($arg_list as $key => $arg){
            $arg_list[$key] = urlencode($arg);
        }
        $postargs = new stdClass();
        $postargs->start = new stdClass();
        $postargs->start->date = $startdate;
        $postargs->end = new stdClass();
        $postargs->end->date = $enddate;
        $postargs->summary = $name;
        $postargs->description = $addinfo;
        // add user email as attendee
        //$postargs->attendees = array(json_decode('{"email":"' . $email . '","optional":"true"}'));
        return json_encode($postargs);
    }

    function getAccessToken($rtok) {
        $tokenURL = 'https://accounts.google.com/o/oauth2/token';
        $creds = json_decode(getenv('GOOGLE_CREDS'));
        $postData = array(
            'client_secret' => $creds->web->client_secret,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $creds->web->refresh_token,
            'client_id'     => $creds->web->client_id
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

    function sendGetRequest($request){
        setupClient(false);
        $session = curl_init($request);
        curl_setopt($session, CURLOPT_HTTPGET, true);
        curl_setopt($session, CURLOPT_HEADER, false); 
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLINFO_HEADER_OUT, false);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization:  Bearer ' . getenv('ACCESS_TOKEN'), 'X-JavaScript-User-Agent: randestin'));
         
        $response = curl_exec($session);
         
        curl_close($session); 
        return $response;
    }

    function sendPostRequest($postargs, $cal){
        setupClient(false);
        $request = 'https://www.googleapis.com/calendar/v3/calendars/' . $cal . '/events?sendNotifications=true&pp=1';
         
        $session = curl_init($request);
         
        curl_setopt($session, CURLOPT_POST, true); 
        curl_setopt($session, CURLOPT_POSTFIELDS, $postargs); 
        curl_setopt($session, CURLOPT_HEADER, false); 
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLINFO_HEADER_OUT, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . getenv('ACCESS_TOKEN'), 'X-JavaScript-User-Agent: randestin'));
         
        $response = curl_exec($session);
         
        curl_close($session); 
        return $response;
    }

    function sendDeleteRequest($event_id, $cal) {
        setupClient(false);
        $request = 'https://www.googleapis.com/calendar/v3/calendars/' . $cal . '/events/' . $event_id;
        $postargs = ''; 
        $session = curl_init($request);
         
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($session, CURLOPT_POSTFIELDS, $postargs); 
        curl_setopt($session, CURLOPT_HEADER, false); 
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($session, CURLOPT_VERBOSE, true);
        curl_setopt($session, CURLINFO_HEADER_OUT, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:  application/json','Authorization:  Bearer ' . getenv('ACCESS_TOKEN'), 'X-JavaScript-User-Agent: RanDestin'));
         
        $response = curl_exec($session);
         
        curl_close($session); 
        return $response;
    }
  ?>
