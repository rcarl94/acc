<?php

$scope        = 'https://www.googleapis.com/auth/calendar.events';
$creds        = json_decode(getenv('GOOGLE_CREDS'));
$clientID     = $creds->web->client_id;
$clientSecret = $creds->web->client_secret;
$redirectURI  = 'https://randestin.com/oauth';
$authCode     = $_GET['code'];
 
if (empty($authCode)) {
    $params = array(
        'response_type' => 'code',
        'client_id' => $clientID,
        'redirect_uri' => $redirectURI,
        'access_type' => 'offline',
        'scope' => $scope,
        'approval_prompt' => 'force'
    );
 
    $oauthURL = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
    header("Location: $oauthURL");
} else {
    $tokenURL = 'https://accounts.google.com/o/oauth2/token';
    $postData = array(
        'code'          => $authCode,
        'client_id'     => $clientID,
        'client_secret' => $clientSecret,
        'redirect_uri'  => $redirectURI,
        'grant_type'    => 'authorization_code'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenURL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
    $tokenReturn = curl_exec($ch);
    $token = json_decode($tokenReturn);
    echo("Here is your Refresh Token for your application.  Do not loose this!\n\n");
    echo("Refresh Token = '" . $token->refresh_token . "';\n");
}
?>
