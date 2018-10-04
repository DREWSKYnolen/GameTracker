<?php
error_reporting(E_ALL);
require_once('dbConnection.php');
require_once('utils.php');
require_once('gameTypes.php');

echo var_dump($_FILES);

$gameTitle = sqlSanitize($conn,$_POST['game_title']);
$playerCount = sqlSanitize($conn,$_POST['player_count']);
$type = sqlSanitize($conn,$_POST['type']);
$gameTitle = sqlSanitize($conn,$_POST['game_title']);
$instructions = sqlSanitize($conn,$_POST['instructions']);
$dateAquired = sqlSanitize($conn,$_POST['date_aquired']);
$picName = sqlSanitize($conn,$_FILES['boxPic']['name']);
$picType = pathinfo($picName, PATHINFO_EXTENSION);

$insert = "INSERT INTO `games`(`game_id`, `game_title`, `player_count`, `type`, `instruction_link`, `date_aquired`, `isActive`) VALUES (DEFAULT,'$gameTitle','$playerCount','$type','$instructions','$dateAquired', 1)";
$result = mysqli_query($conn, $insert);


// echo $insert . "<br>";
$gameID = mysqli_insert_id($conn);

$uploaddir = './box_images/';
$uploadfile = $uploaddir . "$gameID-$gameTitle.$picType";
// echo "$uploadfile";

$thumbDir = './box_images/thumbs/';
$thumbfile = $thumbDir . "$gameID-$gameTitle.$picType";

if (move_uploaded_file($_FILES['boxPic']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
    correctImageOrientation($uploadfile);
    make_thumb($uploadfile, $thumbfile, 100);
    $linkInsert = "UPDATE `games` SET `box_picture`='$uploadfile', `box_thumb`='$thumbfile' WHERE `game_id`=$gameID";
    // echo $linkInsert;
    $result = mysqli_query($conn, $linkInsert);
    // echo $result;
} else {
    echo "Possible file upload attack!\n";
}
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
	$playerCount = sqlSanitize($conn,$_POST['player_count']);
	$gameType = sqlSanitize($conn,$_POST['type']);
	$dateAquired = sqlSanitize($conn,$_POST['date_aquired']);


	mysqli_close($conn);


	header("Location: ./");  //Redirect browser 
	exit();
?>

	</body>
</html>