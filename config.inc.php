<?
// global config file
error_reporting(0);

$inc = dirname(__FILE__) . '/';

require_once($inc . "/curl/curl.php");
require_once($inc . "/vr_cloud.inc.php");

// url to the api
define("VR_API_URL", 'https://www.vr.org/vapi/');

// api key ( see https://www.vr.org/api/ )
define("VR_API_KEY", '');

?>
