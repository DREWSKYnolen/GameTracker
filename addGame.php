<?php
error_reporting(E_ALL);
require_once('utils.php');
require_once('gameTypes.php');
?>

<html>
	<head>	
		<title>Add a game</title>		
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
	</head>
	<body>

	<h1>Add a game</h1>

	<body>

		<form action="addGame-handler.php" method="post" enctype="multipart/form-data">
			<p>Game Title:<br>
				<input type="text" name="game_title" required/>
			</p>

			<p>Player Count:<br>
				<input type="text" name="player_count" reqired/>
			</p>

			<p>Type:<br>
				<select name="type" required>
					<option disabled selected value style="display:none"> -- select an option -- </option>
					<?php
						for ($i = 0; $i < count($gameTypes); $i++)
						{
							echo "<option value='$gameTypes[$i]'>$gameTypes[$i]</option>";
						}
					?>
				</select>
				<br>
			</p>

			<p>Picture of box:<br>
				<input type="file" name="boxPic" accept="image/*"/>
			</p>

			<p>Link to instructions online:<br>
				<input type="text" name="instructions"/>
			</p>

			<p>Date Aquired:<br/>
			<input type="date" name="date_aquired" value="<?php echo date('Y-m-d'); ?>"/>
			</p>

			<p>
			<input type="submit" value="Submit"/>
			</p>
		</form>
	</body>
</html>