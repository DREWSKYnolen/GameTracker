<?php
error_reporting(E_ALL);
require_once('utils.php');
require_once('dbConnection.php');
require_once('gameTypes.php');
?>
<html>
	<head>
	  <link href='styles.css' rel='stylesheet' type='text/css' />
	  <title>Games</title>
	  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	  <meta name="apple-mobile-web-app-capable" content="yes" />
	</head>

	<h1>Drew and Kaylee's game collection</h1>
	<form action="./playLog.php">
    	<input type="submit" value="Create a play log" />
	</form>
	<form action="./displayLog.php">
    	<input type="submit" value="View play logs" />
	</form>

	<body>
		<form action="addGame.php">
			<input type="submit" value="Add a game" />
		</form>


	<?php
	
	$query = "select * from `games` where `isActive` = 1";

	$results = mysqli_query($conn, $query);
	// echo var_dump($results);
		if (mysqli_num_rows($results)>0) {

			echo "<table>";
			echo "<tr><th>Game Title</th><th># of players</th><th>Type</th><th>Picture of box</th><th>Link to instructions</th><th>Date aquired</th></tr>\n";
			while($row = $results->fetch_assoc()) {
				$gameID = $row['game_id'];
                $gameTitle = $row['game_title'];
                $playerCount = $row['player_count'];
                $type = $row['type'];
                $boxPicLocation = $row['box_picture'];
                $boxThumb = $row['box_thumb'];
                if ($boxThumb == "") {
                	$boxThumb = "No picture :(";
                }
                else
                	$boxThumb = hyperlink($row["box_picture"], "<img src= '$boxThumb'/>");
                $instructionLink = $row['instruction_link'];
                if ($instructionLink != ""){
                	$instructionLink = hyperlink($instructionLink);
                }
                $dateAquired = $row['date_aquired'];
		                

		         echo "<tr><td>$gameTitle</td><td>$playerCount</td><td>$type</td><td>$boxThumb</a></td><td>$instructionLink</td><td>$dateAquired</td>";
		    }
		    echo "</table>";
		}//more than 0 rows
		mysqli_close($conn);
	?>

	</body>
</html>