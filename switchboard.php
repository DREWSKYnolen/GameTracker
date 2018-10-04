<?php
error_reporting(E_ALL);
require_once('dbConnection.php');
require_once('utils.php');
?>
<head>
  <link href='styles.css' rel='stylesheet' type='text/css' />
  <title>Switchboard</title>
</head>
<h1>Make changes to games</h1><br>

<form action="./">
    <input type="submit" value="Home" />
	</form>
<hr/>

<?php

$query = "SELECT * FROM `event` ORDER BY DateCreated DESC";
$results = mysqli_query($conn, $query);

echo "<table id='switchboardTable'>";
echo "<tr><th id='switchboardTable'>EventID</th><th id='switchboardTable'>Situation Name</th><th id='switchboardTable'>Date Opened</th><th id='switchboardTable'>Date Closed</th><th id='switchboardTable'>Active</th><th id='switchboardTable'>Inactive</th><th id='switchboardTable'>Submit</th></tr>";
while($row = $results->fetch_assoc()){
	$eventID = $row['EventID'];
	echo "<tr id='switchboardTR'>";
	echo "<td id='switchboardTD'> " . $row['EventID'] . "</td>\n";
	echo "<td id='switchboardTD'> " . $row['EventName'] . "</td>\n";
	echo "<td id='switchboardTD'> " . dropTimeFromDate($row['DateCreated']) . "</td>\n";
	if ($row['DateClosed'] == NULL){
		echo "<td id='switchboardTD'>NOT CLOSED</td>\n";
	}
	else{
		echo "<td id='switchboardTD'> " . dropTimeFromDate($row['DateClosed']) . "</td>\n";
	}
	if($row['IsActive'] == 1){
		// echo "<td id='switchboardTD'>1</td>\n";
		// echo "<td id='switchboardTD'>0</td>\n";
		// echo "<td id='switchboardTD'>test</td>\n";
		echo "<form action='switchboard-handler.php?EventID=$eventID' method='post'>";
		echo "<td id='switchboardTD'><input type='radio' name='status' value='1' checked='checked'/></td>\n";
		echo "<td id='switchboardTD'><input type='radio' name='status' value='0'/></td>\n";
		echo "<td id='switchboardTD'><input type='submit' value='Submit'/></td>\n";
		echo "</form>";
	}
	else{
		echo "<form action='switchboard-handler.php?EventID=$eventID' method='post'>";
		echo "<td id='switchboardTD'><input type='radio' name='status' value='1'/></td>\n";
		echo "<td id='switchboardTD'><input type='radio' name='status' value='0' checked='checked'/></td>\n";
		echo "<td id='switchboardTD'><input type='submit' value='Submit'/></td>\n";
		echo "</form>";
	}

}

?>
</table>