<?php
    require_once __DIR__ . '/google-api-php-client-2.2.3/vendor/autoload.php';

    $client = new Google_Client();

    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        echo 'Auth code: ';
        var_dump($_GET['code']);
        echo 'Access token: ';
        var_dump($token);
        die;
    }

    $client->setApplicationName('randestin');
    $client->addScope(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    //$client->setPrompt('select_account consent');
    $client->setRedirectUri('https://randestin.com/oauth');

    // Request authorization from the user
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    /*
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    // Check to see if there was an error.
    if (array_key_exists('error', $accessToken)) {
        throw new Exception(join(', ', $accessToken));
    }
    */
/*
$cScope = 'https://www.googleapis.com/auth/calendar';
$cClientID      =   '381768128087-fvkcbktqfcrmndtj9tbks7kt6lhh4cq4.apps.googleusercontent.com';
$cClientSecret  =   'Yac8T9RFAAVcSYXD00vN0mbt';
$cRedirectURI   =   'urn:ietf:wg:oauth:2.0:oob';
  
$cAuthCode      =   '4/AAAFrsvPC_3XNQLM6KakvKs8JXIwNANaZgtVcCEiDW7IgRI3LmSaC60';
 
if (empty($cAuthCode)) {
    $rsParams = array(
                        'response_type' => 'code',
                        'client_id' => $cClientID,
                        'redirect_uri' => $cRedirectURI,
                        'access_type' => 'offline',
                        'scope' => $cScope,
                        'approval_prompt' => 'force'
                     );
 
    $cOauthURL = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($rsParams);
    echo("Go to\n$cOauthURL\nand enter the given value into this script under \$cAuthCode\n");
    exit();
} // ends if (empty($cAuthCode))
elseif (empty($cRefreshToken)) {
    $cTokenURL = 'https://accounts.google.com/o/oauth2/token';
    $rsPostData = array(
                        'code'          =>   $cAuthCode,
                        'client_id'     =>   $cClientID,
                        'client_secret' =>   $cClientSecret,
                        'redirect_uri'  =>   $cRedirectURI,
                        'grant_type'    =>   'authorization_code',
                        );
    $ch = curl_init();
  
    curl_setopt($ch, CURLOPT_URL, $cTokenURL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rsPostData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
    $cTokenReturn = curl_exec($ch);
    $oToken = json_decode($cTokenReturn);
    echo("Here is your Refresh Token for your application.  Do not loose this!\n\n");
    echo("Refresh Token = '" . $oToken->refresh_token . "';\n");
} // ends
*/
?>
