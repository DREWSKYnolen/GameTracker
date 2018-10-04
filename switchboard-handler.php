<?php
error_reporting(E_ALL);
require_once('utils.php');
require_once('dbConnection.php');

$conn = mysqli_connect($dbHostname, $dbUsername, $dbPassword, $dbSchema);

$eventID = sqlSanitize($conn, $_GET['EventID']);
$status = sqlSanitize($conn, $_POST['status']);
$date = date('Y-m-d H:i:s');

if($status == 0){
	$updateEvent = "UPDATE `event` SET `DateClosed`= '$date', `IsActive`=$status WHERE `EventID`=$eventID";
	$updateRoster = "UPDATE `roster` SET `DateClosed`= '$date', `IsActive`=$status WHERE `EventID`=$eventID";
}
else{
	$updateEvent = "UPDATE `event` SET `DateClosed`= NULL,`IsActive`=$status WHERE `EventID`=$eventID";
	$updateRoster = "UPDATE `roster` SET `DateClosed`= NULL,`IsActive`=$status WHERE `EventID`=$eventID";
}

echo $updateEvent;

$result = mysqli_query($conn, $updateEvent);
if(!$result)
	echo "Database Error!";


$result = mysqli_query($conn, $updateRoster);
if(!$result)
	echo "Database Error!";

header("Location: http://www.co.henry.va.us/drewtest/switchboard.php"); /* Redirect browser */
	exit();

?>