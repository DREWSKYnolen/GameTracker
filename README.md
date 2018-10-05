# GameTracker
Web-based database with GUI for management of games owned (video games, card games, board games, etc)

A dbConnection.php file will need to be created with database connection properties and schema. Example:

---START EXAMPLE CODE---

$dbHostname = "/*database hostname*/";
$dbUsername = "/*database Username*";
$dbPassword = "/*database password*/";
$dbSchema = "/*database name*/";

/*
$conn is a 'global variable' so it does not need to be initialized in every file.
All files that require dbConnection.php will be able to use the $conn variable.
It is also recommended to create a function in this file or in the utils.php file that you can call for sanitization purposes.
*/
$conn = mysqli_connect($dbHostname, $dbUsername, $dbPassword, $dbSchema);

---END EXAMPLE CODE---

A directory will need to be created within the "game" directory called 'box_images'.
A directory inside of 'box_images' needs to be created called 'thumbs'.
The box_images directory will hold the full-sized images of the box uploaded.
The thumbs subdirectory will hold thumbnail versions of the box images.

Games
|_ box_images
   |_ thumbs
