  <?php
    /* code below was adapted from http://cornempire.net/2012/01/15/part-3-oauth2-and-configuring-your-application-with-google/ */
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
        $postargs->attendees = array(json_decode('{"email":"' . $email . '","optional":"true"}'));
        return json_encode($postargs);
    }

    function getAccessToken(){
        $tokenURL = 'https://accounts.google.com/o/oauth2/token';
        $postData = array(
            'client_secret'=>'Yac8T9RFAAVcSYXD00vN0mbt',
            'grant_type'=>'refresh_token',
            'refresh_token'=>'1/vA0KtTnHTKL-KkIdrMXPVwGumcqSAi_BLvIB-zdjvMQ',
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

    function sendGetRequest($token,$request){
        $session = curl_init($request);
        curl_setopt($session, CURLOPT_HTTPGET, true);
        curl_setopt($session, CURLOPT_HEADER, false); 
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLINFO_HEADER_OUT, false);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization:  Bearer ' . $token,'X-JavaScript-User-Agent: RanDestin'));
         
        $response = curl_exec($session);
         
        curl_close($session); 
        return $response;
    }

    function sendPostRequest($api_key, $postargs, $token, $cal){
        $request = 'https://www.googleapis.com/calendar/v3/calendars/' . $cal . '/events?sendNotifications=true&pp=1&key=' . $api_key;
         
        $session = curl_init($request);
         
        curl_setopt($session, CURLOPT_POST, true); 
        curl_setopt($session, CURLOPT_POSTFIELDS, $postargs); 
        curl_setopt($session, CURLOPT_HEADER, false); 
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLINFO_HEADER_OUT, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:  application/json','Authorization:  Bearer ' . $token,'X-JavaScript-User-Agent: RanDestin'));
         
        $response = curl_exec($session);
         
        curl_close($session); 
        return $response;
    }

    function sendDeleteRequest($event_id, $token, $cal){
        $request = 'https://www.googleapis.com/calendar/v3/calendars/' . $cal . '/events/' . $event_id;
        $postargs = ''; 
        $session = curl_init($request);
         
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($session, CURLOPT_POSTFIELDS, $postargs); 
        curl_setopt($session, CURLOPT_HEADER, false); 
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($session, CURLOPT_VERBOSE, true);
        curl_setopt($session, CURLINFO_HEADER_OUT, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:  application/json','Authorization:  Bearer ' . $token,'X-JavaScript-User-Agent: RanDestin'));
         
        $response = curl_exec($session);
         
        curl_close($session); 
        return $response;
    }
  ?>
