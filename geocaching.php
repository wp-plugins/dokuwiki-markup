<? 

$insideitem = false;
$tag = "";
$title = "";
$url = "";
$date = "";
$waypoint = "";
$output = "<html>\n<ul>";

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
  global $insideitem, $tag, $title, $url, $date,$waypoint, $output;

  if ($tagName == "GEOCACHE") {
    $output = $output . "<li><a href=\"javascript: toggle('".trim($waypoint)."');\">\n";
    $output = $output . "<h3 style=\"color:blue\">".trim($title)."</h3></a></li>\n<div id=\"msg".trim($waypoint)."\">
Cache <a href=\"".trim($url)."\">[".trim($waypoint)."]</a> Found in ".$date." <br><i>(Click on title to see photos)</i></div> <div id=\"".trim($waypoint)."\" style=\"display: none\"> </div>\n\n";
    
    /* Reset Global Variables */
    $title = "";
    $url = "";
    $waypoint = "";
    $insideitem = false;
    $tag = "";
    $date = "";
  }
}

function characterData($parser, $data) {
  global $insideitem, $tag, $title, $url, $date, $waypoint;
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
       }
   } 
}


function parse_geocaching($text){

  global $output;

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
  xml_parser_free($xml_parser);

  return $output."</ul></html>";
}


?>