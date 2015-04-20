<?php
require_once 'functions.php';
require_once 'android_client_functions.php';
$function = new Functions();
$client = new AndroidClient();
$function->Connect();

$method = mysql_real_escape_string($_POST['method']);
$userID = mysql_real_escape_string($_POST['userID']);
$activityID = mysql_real_escape_string($_POST['activityID']);
$share = mysql_real_escape_string($_POST['share']);
$json = $_POST['json'];

switch ($method)
{
	case "register":
		$client->Register($json);
		break;
	case "add_actividad":
		$client->AddActividad($userID, $json);
		break;
	case "load_actividad":
		$client->LoadActividad($userID);
		break;
	case "update_actividad":
		$client->UpdateActividad($userID, $activityID, $json);
		break;
	case "delete_actividad":
		$client->DeleteActividad($userID, $activityID);
		break;
	case "share_actividad":
		$client->ShareActividad($userID, $activityID, $share);
		break;
	default:
		echo 'error';
}
$function->Disconnect();
?>