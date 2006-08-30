<? 
/* GLOBAL VARIABLES INITIALIZATION */

$insideitem = false;
$tag = "";
$title = "";
$url = "";
$date = "";
$waypoint = "";
$lat = "";
$long = "";
$output = array();
$filecontent = "";

function startElement($parser, $tagName, $attrs)
{
  global $insideitem, $tag;
   if ($insideitem){
     $tag = $tagName;
   } elseif ($tagName == "GEOCACHE") {
     $insideitem = true;
   } 
}

function endElement($parser, $tagName)
{
  global $insideitem, $tag, $title, $url, $date, $waypoint, $output, $lat, $long, $filecontent;

  if ($tagName == "GEOCACHE") {
    array_push($output, "<li><h3> <a href=\"javascript: toggle('".trim($waypoint)."');\" style=\"color: orange\">\n".
     trim($title)."</a></h3></li>\n<div id=\"msg".trim($waypoint)."\"><small>Cache <a href=\"".trim($url)."\">[".trim($waypoint)."]</a> Found in ".$date."</small> <br></div> <div id=\"".trim($waypoint)."\" style=\"display: none\"> </div>\n\n");
	

      //Category,SomethingElse, Name,Longitude,Latitude

      $filecontent = $filecontent."Geocaching,NestumMel,".trim($waypoint).",".trim($long).",".trim($lat).",0,".trim($title).",".trim($date)."\n";
      

      
      /* Reset Global Variables */
      $title = "";
      $url = "";
      $waypoint = "";
      $lat = "";
      $long = "";
      $insideitem = false;
      $tag = "";
      $date = "";
  }
}

function characterData($parser, $data) {
  global $insideitem, $tag, $title, $url, $date, $waypoint, $lat, $long;
    //echo "$tag --> [$data]<br>";
    if ($insideitem) {
	switch ($tag) {
	 case "TITLE":
	    $title .= $data;
	    break;
	 case "URL":
	    $url .= $data;
	    break;
	 case "DATE":
	    $date .= $data;
	    break;
	 case "ID":
	    $waypoint .= $data;
	    break;
	 case "LAT":
	    $lat .= $data;
	    break;
	 case "LONG":
	    $long .= $data;
	    break;
	}
    } 
}


function parse_geocaching($text){

  global $output, $filecontent;

  $xml_parser = xml_parser_create();
  xml_set_element_handler($xml_parser, "startElement", "endElement");
  xml_set_character_data_handler( $xml_parser, "characterData");
  
  while ($text){
    if (!xml_parse($xml_parser, $text)) {
      //      echo "Bailing out!";
      break;
      //die(sprintf("XML error: %s at line %d",
      // xml_error_string(xml_get_error_code($xml_parser)),
      // xml_get_current_line_number($xml_parser)));
   }
  }
    
    
    /* Write contents to file */
    $filename = '/tmp/gps_data.csv';
    
    // Let's make sure the file exists and is writable first.
    if (!$handle = fopen($filename, 'w')) {
	echo "Cannot open file ($filename)";
	exit;
    }	
    // Write $somecontent to our opened file.
    if (fwrite($handle, $filecontent) === FALSE) {
	echo "Cannot write to file ($filename)";
	exit;
    }
    //echo "Success, wrote ($somecontent) to file ($filename)";	
    fclose($handle);
    

  xml_parser_free($xml_parser);
   
  $str = getString($output);
  return "<html><body><ul>".$str."</ul></html>";
}

function getString($stack){
    $str ="";
    for ($i=count($stack);$i>0;$i--){
	$str .= $stack[$i];
    }
    return $str;
}

?>
