<?php
/*
Dynamically creates the landing page for the EOC project files page
*/
//This is a test comment
error_reporting(E_ALL);
require_once('utils.php');
// require_once('dbConnection.php');

$title="Files";
function noEndInPhp( $str ) { return !preg_match('/.php$/',$str); }
function   endInPhp( $str ) { return  preg_match('/.php$/',$str); }
?>

<html>
    <head><link href='../../../ixml.css' rel='stylesheet' type='text/css' />
        <title><?php echo $title;?></title>
    </head>
    <body>
        <h2><?php echo $title;?></h2>
        
        <?php
            $files = array_diff( scandir("."), array(".","..") );
            $filesToShow = array_filter( $files, "endInPhp" );
            echo toUL( array_map( "hyperlink", $filesToShow ) );
         ?>
    </body>
</html>
