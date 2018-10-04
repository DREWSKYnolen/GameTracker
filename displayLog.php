<?php
error_reporting(E_ALL);
require_once('dbConnection.php');
require_once('utils.php');
?>

<html>
	<head>
	  <link href='styles.css' rel='stylesheet' type='text/css' />
	  <title>Game Logs</title>
	  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	  <meta name="apple-mobile-web-app-capable" content="yes" />
	</head>

	<h1>Log of games played</h1>
	<form action="./playLog.php">
    	<input type="submit" value="Create a play log!" />
	</form>

	<body>
	<?php
	
	$query = "SELECT * from `play_log` ORDER BY `log_id` DESC";

	$results = mysqli_query($conn, $query);
	// echo var_dump($results);
		if (mysqli_num_rows($results)>0) {

			echo "<table>";
			echo "<tr><th>Game Title</th><th>Players</th><th>Time played</th><th># of rounds</th><th>Location</th><th>Drinking?</th><th>Date</th></tr>\n";
			while($row = $results->fetch_assoc()) {
                $gameTitle = $row['game_title'];
                $players = $row['players'];
                $timePlayed = $row['time_played'];
                $rounds = $row['rounds_played'];
                $location = $row['location'];
                $drinking = $row['drinking'];
                if ($drinking == 1)
                	$drinking = "Yes";
                else
                	$drinking = "No";
                $date = $row['date'];
		                

		         echo "<tr><td>$gameTitle</td><td>$players</td><td>$timePlayed</td><td>$rounds</a></td><td>$location</td><td>$drinking</td><td>$date</td>";
		    }
		    echo "</table>";
		}//more than 0 rows
		mysqli_close($conn);
	?>

	</body>
</html>