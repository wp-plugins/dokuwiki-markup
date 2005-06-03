<?php
/*
Plugin Name: DokuWiki Markup
Plugin URI: http://doku.granny.homelinux.org/
Description: This plugin allows one to use the Dokuwiki Markup in an post by encapsulating it within a <wiki> tag
Author: Tiago Bilou
Version: 0.1
Author URI: http://alunos.uevora.pt/~l15243/
*/ 

require_once('parser.php');


function tb_parse_entry($content) {
    
    $needle = "<wiki>";
    
    if ( strstr($content, $needle) === FALSE) {
	return $content;
    }                                                                                    
    else{
	/* Replace </wiki> with <wiki> to use with explode */
	$content = str_replace("</wiki>", "<wiki>", $content);
	 
	$subStrings = explode("<wiki>", $content);
	
	if (count($subStrings) != 0)
	{
	    /* Non Wiki code... This does not get formated by wp */
	    echo $subStrings[0];
	    
	    /* Prepare the contents */
	    $textlines = split("\n",$subStrings[1]);
	    for ($l=0; $l<count($textlines); $l++){                                                                      
		/* Remove '\r' */
		$line = rtrim($textlines[$l]);
		$text = $text . $line."\n";
	    }
	
	    /* Parse the wiki code */
	    echo parse($text);
	    
	    /* Non wiki code */
	    echo $subStrings[2];
	    
	    $content="";
	    return $content;
	}
	
    }
}

/* Don't close tags... It breaks the <me@mail.com> */
remove_filter('content_save_pre','balanceTags', 50);

/* Add our filter */
add_filter('the_content', 'tb_parse_entry', 1);


?>
