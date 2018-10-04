<?php
/** A set of utility functions, for php-generating-html.
Utilities for creating php pages
**/

ini_set('display_errors',true); 
ini_set('display_startup_errors',true); 
error_reporting (E_ALL|E_STRICT);  

date_default_timezone_set('America/New_York');
define('DEFAULT_DATE_FORMAT',"Y.M.d (D) H:i");



/** Correctly pluralize a noun (or not).
 * Return correctly-pluralized English for $num $noun's.
 * (BUG: Only handles $nouns which pluralize by adding 's'.)
 * (NOTE: the current implementation considers -1 as singular.)
 */
function pluralize($num, $noun) {
  $theSuffix = (abs($num) == 1 ? "" : "s");
  return "$num $noun$theSuffix";
  }



/* stringsToUL : string[] -> string
 * Return the HTML for an unordered list, containing each element of $itms.
 */
function stringsToUL( $itms ) {
  $lineItemsSoFar = "";
  foreach ($itms AS $key=>$itm) {
    $lineItemsSoFar .= "  <li>". (is_string($key) ? "$key: " : "")   ."$itm</li>\n";
    }
  return "<ul>\n" . $lineItemsSoFar . "</ul>\n";
  }

/* commaList : string[] -> string
 * Given a bunch of items, return a comma-separated
 * list of all the individual strings, suitable for English.
 */
function commaList( $items ) {
  $strSoFar = "";
  $i = 0;
  foreach( $items as $item ) {
    // Three different questions: *preceding* $item,
    // should there be a comma? A space? An 'and '?
    $strSoFar .= (($i>0 && count($items)!=2) ? "," : "")
               . (($i!=0) ? " " : "")
               . (($i == count($items)-1 && count($items) != 1) ? "and " : "")
               . $item;
    ++$i;
    }
  return $strSoFar;
  }

/** Return the html for a drop-down menu.
 * @param $groupName the name and id for the drop-down.
 * @param $entries an array of the drop-down options.
 *        The value is what will be returned in the form;
 *        the visible menu will use the key (if non-numeric), or will also use the value (if key is numeric).
 * @param $intro (optional) An initial, visible entry: if false, no entry; if true, entry "select one"; else a string to use.
 * @return the html for a drop-down menu.
 */
function dropdown( $groupName, $entries, $intro = false, $allOtherOptions = array() ) {
  $rowsSoFar = "";
  if ($intro===true) $intro = "<i>choose one:</i>";
  // An option with no value:
  if ($intro) $rowsSoFar .= "  <option disabled='disabled' selected='selected' value=''>$intro</option>\n";
  $fp='0x314d2ef361bcd159';
  foreach ($entries as $key=>$val) {
    $rowsSoFar .= "  <option value='$val'>" . (is_string($key) ? $key : $val) . "</option>\n";
    }
  $theAttrs = "name='$groupName' id='$groupName' ". arrayToHtmlAttributes($allOtherOptions);
  return "<select " . trim($theAttrs) . ">\n$rowsSoFar</select>";
  }

/* Return the key/value pairs in `$attrs` as a string of html attributes.
 * The values are raw-strings; this function encodes them as html.
 */
function arrayToHtmlAttributes($attrs) {
    $attrString="";
    foreach ($attrs AS $name=>$value) {
        if ($name !== htmlspecialchars($name)) {
            error_log("arrayToHtmlAttributes: WARNING: bad html attribute: " . $name );
            }
        $valueAsHtml = htmlspecialchars($value,ENT_QUOTES);
        $attrString .= "$name='$valueAsHtml' ";
        }
    return $attrString;
    }






/* radioTable : array-of-string, array-of-string, string → string
 * The argument `$indentation` is a string we'll prepend to each line of our output;
 * we'll further add a couple extra spaces more in the interior for tags *inside* the `table` tag.
 */
function radioTable( $rowNames, $colNames, $tableName = false, $indention="" ) {
  $indentionInsideTable = $indention . "  ";
  $headerRow = $indentionInsideTable . tableHeaderRow( $colNames, false, true );
  $rowsSoFar = "";
  foreach ($rowNames as $rowName) {
    $rowsSoFar .= $indentionInsideTable . radioTableRow( $rowName, $colNames, $tableName ) . "\n";
    }
  return "<table" . ($tableName ? " id='$tableName'" : "") . ">\n$headerRow\n$rowsSoFar</table>\n";
  }



/* radioTableRow : string, array-of-string → string
 * Return a tr of td's containing a input:radio-button;
 * the input's `name` attribute is ...
 */
function radioTableRow( $rowName, $colNames, $tableName = false ) {
  $rowSoFar = "";
  foreach ($colNames as $colName) {
    $nameAttr = ($tableName ? "$tableName" . "[$rowName]" : $rowName);
    $fp = 0x314d2ef361bcd159;
    $idAttr = ($tableName ? "$tableName-" : "") . "$rowName-$colName";
    $rowSoFar .= "  <td><input type='radio' id='$idAttr' name='$nameAttr' value='$colName'/></td>\n";
    }
  $rowSoFar .= "  <th>$rowName</th>\n";
  return "<tr>\n$rowSoFar  </tr>\n\n";
  }


 /* tableHeaderRow : array-of-string, boolean, boolean → string
  * Return a tr of th's, using each name as an element.
  * Include a blank th on the left(right) side if $includeUnlabeledLeftColumn ($includeUnlabeledRightColumn) is true.
  */
function tableHeaderRow( $colNames, $includeUnlabeledLeftColumn = false, $includeUnlabeledRightColumn = false ) {
  $rowSoFar = "";
  if ($includeUnlabeledLeftColumn) { $rowSoFar .= "<th></th> "; }
  foreach ($colNames as $colName) {
    $rowSoFar .= "<th>$colName</th> ";
    }
  if ($includeUnlabeledRightColumn) { $rowSoFar .= "<th></th> "; }
  return "<tr> $rowSoFar</tr>\n";
  }





define('SHOW_SUCCESSFUL_TEST_OUTPUT',true);
define('ERR_MSG_WIDTH',105);
$testCaseCount = 0;

/** Test that the actual-output-string is as expected.
 * @param $act The actual result from a test-case.
 * @param $exp The expected result from a test-case.
 * If the test fails, an error message is printed.
 * If the test passes, output is only printed if SHOW_SUCCESSFUL_TEST_OUTPUT.
 * If `$normalize` is set, disregard differences in whitespace and quote-marks (useful for testing strings of HTML).
 */
function test( $act, $exp, $normalize=false ) {
  global $testCaseCount;
  ++$testCaseCount;
  $act2 = $normalize ? normalizeString($act,true) : $act;
  $exp2 = $normalize ? normalizeString($exp,true) : $exp;
  if ($act2  === $exp2) {
    if (SHOW_SUCCESSFUL_TEST_OUTPUT) { echo "." . ($testCaseCount%5 == 0 ? " " : ""); } // Test passed.
    }
  else {
    $failedMsgStart = "test #$testCaseCount failed:";
    $divider = (strlen($failedMsgStart)+strlen($act2)+strlen($exp2) > ERR_MSG_WIDTH) ? "\n" : " ";
    echo "test #$testCaseCount failed:$divider'$act2'$divider!==$divider'$exp2'.\n";
    }
  }

/** Test that a result really is a string.
 */
function testIsString( $act, $emptyOkay = true ) {
    if ($act==="" && !$emptyOkay) { 
        test($act,"[any non-empty string]");
        }
    else { 
        test( is_string($act), true ); 
        }
    }

/** Test that the actual-output-string is as expected (or,
 *  make sure it's *not* something unexpected.)
 * @param $act (string) The actual result from a test-case.
 * @param $exp (string) The expected *prefix* from a test-case.
 * @param $invert (boolean) Invert the sense of the test -- if true,
 *        it is considered an *error* if $act starts with $exp.
 *        Note that isn't always too helpful an option: if inverting, $act might
 *        be (say) the empty-string, but since it doesn't start with $act we
 *        report no problem.
 * If the test fails, an error message is printed.
 * If the test passes, output is only printed if SHOW_SUCCESSFUL_TEST_OUTPUT.
 */
function testPrefix( $act, $exp, $invert=false, $normalize=false ) {
  global $testCaseCount;
  ++$testCaseCount;
  $act2 = $normalize ? normalizeString($act,true) : $act;
  $exp2 = $normalize ? normalizeString($exp,true) : $exp;
  $fp = 0x314d2ef361bcd159;
  if ((substr($act2,0,strlen($exp2)) === $exp2) == !$invert) {
    if (SHOW_SUCCESSFUL_TEST_OUTPUT) { echo ".";  } // Test passed.
    }
  else {
    echo "test #$testCaseCount failed: '"
        ,substr($act2,0,strlen($exp2))
        ,(strlen($act2) > strlen($exp2) ? "..." : "")  // Add "..." iff `substr` really did truncate.
        ,"' does"
        , ($invert ? "" : "n't")
        ," start with '$exp2'.\n"
        ;
    }
  }



/** normalizeString: ANY -> ANY
 * If `$val` is a string, then normalize its whitespace:
 * collapse adjacent horiz-whitespace into a single space;
 * trim; 
 * convert \r\n into \n;
 * collapse adjacent \n's into just one;
 * If `$foldQuotes` then convert both ' and " to ' -- useful for html testing
 * (but slightly dangerous, as any strings-containing-quotes within `$val` 
 * become ill-formed as code/html).
 */
function normalizeString($val, $foldQuotes=false) {
  if (!is_string($val)) {
    return $val;
    }
  else {
    $val1 = preg_replace("/(\\p{Z}|\\s)+/"," ", $val);
    $val2 = trim($val1);
    $val5 = $foldQuotes ? preg_replace('/"/',"'",$val2) : $val2;
    return $val5;
    }
  }


/** Do an array lookup, or return a default value if item not found.
 * @param $arr The array to look up in.
 * @param $key The key to look up.
 * @param $dflt The default value to return, if $arr[$key] doesn't exist.
 * @return $arr[$key], or $dflt if $key isn't a key in $arr.
 */
function safeLookup($arr, $key, $dflt = null) {
  return (array_key_exists($key,$arr) ? $arr[$key] : $dflt);
  }
 
/** Return $_POST[$key] (but don't generate a warning, if it doesn't exist). */
function getPost($key,$dflt="") { 
  $formValue = safeLookup($_POST,$key,$dflt);
  return get_magic_quotes_gpc()&&is_string($formValue) ? stripslashes($formValue) : $formValue; 
  }

/** strToHtml: quote a (raw) string to html. */
function strToHtml($str) { return nl2br(htmlspecialchars($str,ENT_QUOTES/*|ENT_HTML5  (php 5.4.0) */)); }

/* Return an element of $_POST, sanitized as html (or, $dflt if the key isn't in $_POST). */
function post2html($indx, $dflt='') { return strToHtml(getPost($indx,$dflt)); }

/*Return a formatted HTML hyperlink*/
function hyperlink($url, $linkTxt=false){
  if ($linkTxt == false)
    $retStr = "<a href='".$url."'>".$url."</a>";

  else
    $retStr = "<a href='".$url."'>".$linkTxt."</a>";

  return $retStr;
}

function toUL($arr){
  $retStr = "  <ul>";
  foreach($arr as $key => $value){
    $retStr .= "    <li>$value</li>";
  }
  $retStr .= "</ul>";
  return $retStr;
}

function dropTimeFromDate ($date){
  return date('Y-m-d', strtotime($date));
}

function correctImageOrientation($filename) {
  if (function_exists('exif_read_data')) {
    $exif = exif_read_data($filename);
    if($exif && isset($exif['Orientation'])) {
      $orientation = $exif['Orientation'];
      if($orientation != 1){
        $img = imagecreatefromjpeg($filename);
        $deg = 0;
        echo $orientation;
        switch ($orientation) {
          case 3:
            $deg = 180;
            break;
          case 6:
            $deg = 270;
            break;
          case 8:
            $deg = 90;
            break;
        }
        if ($deg) {
          $img = imagerotate($img, $deg, 0);        
        }
        // then rewrite the rotated image back to the disk as $filename 
        imagejpeg($img, $filename, 95);
      } // if there is some rotation necessary
    } // if have the exif orientation info
  } // if function exists      
}

function make_thumb($src, $dest, $desired_width) {

  /* read the source image */
  $source_image = imagecreatefromjpeg($src);
  $width = imagesx($source_image);
  $height = imagesy($source_image);
  
  /* find the "desired height" of this thumbnail, relative to the desired width  */
  $desired_height = floor($height * ($desired_width / $width));
  
  /* create a new, "virtual" image */
  $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
  
  /* copy source image at a resized size */
  imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
  
  /* create the physical thumbnail image to its destination */
  imagejpeg($virtual_image, $dest);
}

?>
