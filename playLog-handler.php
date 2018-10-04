<?php
error_reporting(E_ALL);
require_once('dbConnection.php');
require_once('utils.php');
?>

<html>
<br/>
	<head>
		
	</head>
	<body>

	<h1>Add game handler</h1>
	<hr/>

<?php

$gameTitle = sqlSanitize($conn,$_POST['game_title']);
$players = sqlSanitize($conn,$_POST['players']);
$timePlayed = sqlSanitize($conn,$_POST['time']);
$rounds = sqlSanitize($conn,$_POST['rounds']);
$location = sqlSanitize($conn,$_POST['location']);
$drinking = sqlSanitize($conn,$_POST['drinking']);
$date = sqlSanitize($conn,$_POST['date']);

$insert = "INSERT INTO `play_log`(`log_id`, `game_title`, `players`, `time_played`, `rounds_played`, `location`, `drinking`, `date`) VALUES (DEFAULT,'$gameTitle','$players','$timePlayed','$rounds','$location',$drinking,'$date')";
$result = mysqli_query($conn, $insert);

	mysqli_close($conn);


	header("Location: ./displayLog.php");  //Redirect browser 
	exit();
?>

	</body>
</html>