<?php
error_reporting(E_ALL);
require_once('utils.php');
require_once('dbConnection.php');
?>

<html>
	<head>	
		<title>Add a play log</title>		
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
	</head>
	<body>

	<h1>Add a play log</h1>

	<body>

		<form action="playLog-handler.php" method="post">
			<p>Game Title:<br>
				<input type="text" name="game_title" required/>
			</p>

			<p>Who played?<br>
				<input type="text" name="players" reqired/>
			</p>

			<p>Time played:<br>
				<input type="text" name="time">
			</p>

			<p>Rounds played:<br>
				<input type="text" name="rounds">
			</p>

			<p>Location played:<br>
				<input type="text" name="location">
			</p>

			<p>Drinking?<br>
				<input type="radio" name="drinking" value="1">Yes<br>
				<input type="radio" name="drinking" value="0">No<br>
			</p>

			<p>Date played:<br/>
				<input type="date" name="date" value="<?php echo date('Y-m-d'); ?>"/>
			</p>

			<p>
			<input type="submit" value="Submit"/>
			</p>
		</form>
	</body>
</html>