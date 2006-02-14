<?php
/*
Plugin Name: DokuPress
Plugin URI: http://doku.granny.homelinux.org/
Description: This plugin allows one to use the Dokuwiki Markup in an post by encapsulating it within a <wiki> tag
Author: Tiago Bilou
Version: 0.2
Author URI: https://granny.homelinux.org/CryForHelp/

*/

/*  Copyright 2005  Tiago Bilou  (email : tiagobilou [at] netcabo [dot] pt)
 * 
 *     This program is free software; you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation; either version 2 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program; if not, write to the Free Software
 *     Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * */

/* ---------- YOU CAN CHANGE THIS IF YOU WANT -------- */

global $OpenTag;
global $CloseTag;

$OpenTag = "<wiki>";
$CloseTag = "</wiki>";

/* --------- Do Not edit anything beyond this point ------ 
             ( unless you know what you're doing )         */

/* Include DokuWiki's Parser */
require_once('parser.php');

function tb_parse_entry($content) {

  extract($GLOBALS);
  /* No wiki tags found */
  if ( strstr($content, $OpenTag) === FALSE) {
    return $content;
  } else {
    /* Take care of the wiki code */
    $before_wiki = strpos($content, $OpenTag);
    $after_wiki = strrpos($content, $CloseTag);
    $TagSize = strlen($OpenTag);
    
    /* Non Wiki code... This does not get formated by wp */
    echo substr($content, 0, $before_wiki);
    
    /* Prepare the contents */
    $wikicode = substr($content, ($before_wiki+$TagSize), ($after_wiki-$before_wiki-$TagSize));
    $textlines = split("\n",$wikicode);
    for ($l=0; $l<count($textlines); $l++){                                                                      
      /* Remove '\r' */
      $line = rtrim($textlines[$l]);
      $text = $text . $line."\n";
    }
    
    echo parse($text);

    /* Non Wiki code (found after the </wiki> tag */
    echo substr($content, $after_wiki);
    
    $content="";
    return $content;
  }
}
/* Don't close tags... It breaks the <me@mail.com> */
remove_filter('content_save_pre','balanceTags', 50);

/* Add our filter */
add_filter('the_content', 'tb_parse_entry', 1);


?>
