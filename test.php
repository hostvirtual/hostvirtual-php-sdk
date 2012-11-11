<?
// config 
include "config.inc.php";

// create
$vr = new vr_cloud(VR_API_KEY);

// retrieve list of servers
$servers = $vr->servers();

// print
print_r($servers);

?>
