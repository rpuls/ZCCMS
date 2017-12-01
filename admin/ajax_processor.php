<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE);
ini_set("display_errors", 1);
include("../includes/mysqli.php");
include("../includes/library.php");
require ("../includes/settings.php");
require_once  ('../includes/facebook-php-sdk/Facebook/autoload.php');

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\Entities\AccessToken;
use Facebook\GraphObject;
use Facebook\GraphUser;

session_start();
$FacebookSession = safe_get_GET_OR_POST('fb_st');

// login helper with redirect_uri
$base_site_URL = "http://zitecraft.com";

//$scope="profile,manage_pages";
$loginUrl = "https://www.facebook.com/dialog/oauth?client_id=185364761673335&redirect_uri=". urlencode("http://zitecraft.com/lookup/lookup.php?scope=$scope");

$fb = new Facebook\Facebook([
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.4',    
]);

try {
 	$response = $fb->get('/me?fields=id,name,picture', $FacebookSession);
 	$user = $response->getGraphUser();
 	$FB_user_pic = $user['picture']['url'];
 	$FB_name = $user['name'];
 	$FB_userid = $user['id'];
} catch(FacebookRequestException $e) {
    header('HTTP/1.1 503');
    echo 'Graph returned an error: ' . $e->getMessage();
    die();
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    header('HTTP/1.1 503');
    echo 'line 60: Facebook SDK returned an error: ' . $e->getMessage();
    die();
}


if (!isset( $FacebookSession ) || $FacebookSession === null ) {
	// no session exists, go back to the lookup page
    header('HTTP/1.1 307');
    echo '/lookup/lookup.php';
	exit;
}

if(($FB_userid == null) || ($FB_userid == 0) || (!$FB_userid)){
	error_log("empty FB_userid in manage_form_processor.php ");
    header('HTTP/1.1 307');
    echo '/lookup/lookup.php'; 
	exit;
}

$FB_page_id = filter_n(safe_get_GET_OR_POST('fb_id'));

if(($FB_page_id == null) || ($FB_page_id == 0) || (!$FB_page_id)){
	error_log("empty FB_page_id in ajax_processor.php ");
	header('HTTP/1.1 307');
    echo '/lookup/lookup.php';
	exit;
}


$is_this_the_page_admin = true;
$is_this_the_global_admin = true;

try {
    $page_url_graph = '/'.$FB_page_id.'?fields=id,name,picture&redirect=false';
    $response = $fb->get($page_url_graph, $FacebookSession);
    $page_object = $response->getGraphPage();
    $FB_page_pic = $$page_object['picture']->data->url;
    if($page_object['id'] != $FB_page_id){ 
        $is_this_the_page_admin = false;
    }
} catch(FacebookRequestException $e) {
    // the enduser is trying to get access to another page by changing the id parameter. Naughty! Give a cryptic error and exit.
    header('HTTP/1.1 503');
    echo "Something went very wrong. Error code: " . $e->getCode();
    exit();
}

// ----------- If this user is trying to edit a page he may not, exit ungracefully.
if(!$is_this_the_page_admin){
    error_log("FBUser $FB_userid tried to edit site ". $FB_page_id ." , probably editing the id paramter.");
    header('HTTP/1.1 403');
    echo("You are not alowed to edit this page. 1");
    exit();
}




$site_Style_id = 1;
$placeSQL= "SELECT * from tblPlaces where FB_ID=$FB_page_id";
$res = mysqli_query($mysqli_link, $placeSQL);
if((mysqli_num_rows($res) <1) OR !($ArPlaceDetails = mysqli_fetch_assoc($res))) {
    error_log("FBUser $FB_userid tried to edit site ". $FB_page_id ." that does not seem to exist.");
    header('HTTP/1.1 403');
	echo("You are not alowed to edit this page.");
	exit();
}else{
	$site_Style_id = safe_get_Arrayelement($ArPlaceDetails, 'StyleID');
}

$zite_id = safe_get_GET_OR_POST('zite_id');

if(($ArPlaceDetails['FB_ID'] != $FB_page_id) OR ($ArPlaceDetails['ID'] != $zite_id)){
    error_log("FBUser $FB_userid tried to edit site ". safe_get_GET_OR_POST('fb_iid').", $zite_id , but the id's in the database do not seem to match up.");
    header('HTTP/1.1 403');
	echo("You are not alowed to edit this page. \n" . $ArPlaceDetails['FB_ID'] . " \n x " . $FB_page_id . " \n x " . $zite_id);
	exit();
}

// ------------------------ END OF AUTHORIZATION! ------------------------

$action = $_POST['action'];

if($action == 'layout'){
    $pos = 1;
    foreach($_POST as $key => $value) {
        if (strstr($key, 'se')){
            $values = [];
            foreach($value as $se) {
                $values[] = $se;
            }
            $color1 = validateColor($values[1]);
            $color2 = validateColor($values[2]);
            $color3 = validateColor($values[3]);
            
            $stmt = $mysqli_link->prepare("REPLACE INTO tblSvgElement(PlaceID, SVG, SVGPosition, Path1Color, Path2Color, Path3Color) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("iissss", $zite_id, $values[0], $pos, $color1, $color2, $color3);
            $stmt->execute();
            $pos +=1;
        }
    }
    $stmt->close();
    $mysqli_link->close();
}

function validateColor($input){
    if(!preg_match("/^(\#[\da-f]{3}|\#[\da-f]{6}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)\)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/",$input)){
        return '#000000';
    }
    return $input;
}

?>