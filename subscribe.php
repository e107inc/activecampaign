<?php
require_once("../../class2.php");
require_once("vendor/autoload.php");
use ActiveCampaign;

$acPref = e107::pref('activecampaign');

if(empty($acPref['api_url']) || empty($acPref['api_key']))
{
	echo "<div class='alert alert-danger'>Misconfigured API</div>";
	exit;

}

if ($_SERVER["REQUEST_METHOD"] !== "POST")
{
	exit;
}


$listid = 1;

$ac = new ActiveCampaign($acPref['api_url'], $acPref['api_key']);
$result = (int) $ac->credentials_test();

if(!$result)
{
	echo "System misconfigured. Contact Administrator";
}

if(empty($_POST["first_name"]))
{
	echo "<div class='alert alert-danger'>First name is missing!</div>";
	exit;
}
if(empty($_POST["last_name"]))
{
	echo "<div class='alert alert-danger'>Last name is missing!</div>";
	exit;
}
if(empty($_POST["email"]))
{
	echo "<div class='alert alert-danger'>Email is missing!</div>";
	exit;
}
$tp = e107::getParser();

$save = [];

$save["first_name"] = $tp->filter($_POST['first_name']);
$save["last_name"]  = $tp->filter($_POST['last_name']);
$save["email"]      = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
$save["p"]          = array($listid => $listid);
$save["status"]     = array($listid => 1);
$save["instantresponders"] = array($listid => 1);

if(!empty($acPref['tags']))
{
	$save['tags'] = explode(',',$acPref['tags']);
}



// Need to add a custom field value? Include it below.
//$_POST["field"] = array("%PERS_1%,0" => "Custom field value");
/** @var  $response */
$response = $ac->api("contact/sync", $save);

if($response->success)
{
	echo "<div class='alert alert-success'>Almost done! Please check your email inbox to confirm your subscription with <b>".$save['email']."</b></div>";
}
else
{
	echo "<div class='alert alert-danger'>".$response->result_message."</div>";
}

if(getperms('0') && deftrue('e_DEBUG'))
{
	echo "Debug (visible only to you): ";
	$ac->dbg($response);
}





