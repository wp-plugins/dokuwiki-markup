<?php
/*
Plugin Name: DokuWiki Markup
Plugin URI: http://doku.granny.homelinux.org/
Description: This plugin allows one to use the Dokuwiki Markup in an post by encapsulating it within a <wiki> tag
Author: Tiago Bilou
Version: 0.1
Author URI: http://alunos.uevora.pt/~l15243/
*/ 


/* ---------- YOU CAN CHANGE THIS IF YOU WANT -------- */

global $OpenTag;
global $CloseTag;

$OpenTag = "<wiki>";
$CloseTag = "</wiki>";



/* --------- Do Not edit anything beyond this point ------ 
             ( unless you know what you're doing )         */
 



require_once('parser.php');

function tb_parse_entry($content) {

    extract($GLOBALS);
    
    if ( strstr($content, $OpenTag) === FALSE) {
	return $content;
    }                                                                                    
    else{

	$first = strpos($content, $OpenTag);
	$last = strrpos($content, $CloseTag);
	$TagSize = strlen($OpenTag);

	/* Non Wiki code... This does not get formated by wp */
	echo substr($content, 0, $first);
	
	/* Prepare the contents */
	$wikicode = substr($content, ($first+$TagSize), ($last-$TagSize));
	$textlines = split("\n",$wikicode);
	for ($l=0; $l<count($textlines); $l++){                                                                      
	    /* Remove '\r' */
	    $line = rtrim($textlines[$l]);
	    $text = $text . $line."\n";
	}
	
	/* Parse the wiki code */
	echo parse($text);
	    
	/* Non wiki code */
	echo substr($content, $last);
	
	$content="";
	return $content;
	
    }
}

/* Don't close tags... It breaks the <me@mail.com> */
remove_filter('content_save_pre','balanceTags', 50);

/* Add our filter */
add_filter('the_content', 'tb_parse_entry', 1);


?>
