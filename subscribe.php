<?php
require_once("../../class2.php");
$acPref = e107::pref('activecampaign');
require_once('acHelper.php');

if(empty($acPref['api_url']) || empty($acPref['api_key']))
{
	echo "<div class='alert alert-danger'>Misconfigured API</div>";
	exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST")
{
	exit;
}


if(!$test = acHelper::test())
{
	echo "<div class='alert alert-danger'>Sorry, there is a problem on our end. Please try again later.</div>";
	exit;

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

$listid = 1; // default list number to subscribe to.

$save = [];
$save["first_name"] = $_POST['first_name'];
$save["last_name"]  = $_POST['last_name'];
$save["email"]      = $_POST['email'];
$save["p"]          = array($listid => $listid);
$save["status"]     = array($listid => 1);
$save["instantresponders"] = array($listid => 1);

if(!empty($acPref['tags']))
{
	$save['tags'] = explode(',',$acPref['tags']);
}

$response = acHelper::addContact($save);

if($response->success)
{
	echo "<div class='alert alert-success'>All done!</b></div>";
//	echo "<div class='alert alert-success'>Almost done! Please check your email inbox to confirm your subscription with <b>".$save['email']."</b></div>";
}
else
{
	echo "<div class='alert alert-danger'>".$response->result_message."</div>";
}







