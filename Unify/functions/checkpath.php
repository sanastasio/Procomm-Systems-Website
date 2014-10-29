<?php
	
	include_once('geturi.php');
	
	$pageurl = $_SERVER['HTTP_REFERER']; //incoming page url
	$scripturl = $_SERVER['HTTP_HOST'] . uri();
	
	function rewriteurl($pageurl) {
		
		$rwurl = false;
		
		$rewrites = array('/\/$/', '/.$/' );
		$exts = array( "$0.php", "$0.html", "$0.htm", "$0.cfm", "$0.phtml", "$0.asp", "$0.aspx" );
		
		foreach ($rewrites as $rw) {
			
			if (preg_match($rw, $pageurl)) {
			
				foreach ( $exts as $ext ) {
				
					if ( @file_exists( preg_replace($rw, $ext, $pageurl) ) ) { 
						$rwurl = preg_replace($rw, $ext, $pageurl);
						break 2;
					}
				
				}
			
			}
		
		}
		
		return $rwurl;
	
	}
	
	if (stristr($pageurl, $_SERVER['HTTP_HOST'])) {
		
		$path = $_SESSION['un1fyPath'];
		$scripturl = explode("/$path/", $scripturl);
		
		$dirname = str_replace( '\\', '/', dirname(__FILE__) );
		
		$pageurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? $dirname . str_replace("https://$scripturl[0]/", '/../../', $pageurl) : $dirname . str_replace("http://$scripturl[0]/", '/../../', $pageurl); // check for https protocol
		
		$pagepath = explode('/', $pageurl);		
		array_pop($pagepath);
		$pagepath = implode('/', $pagepath);
		
		if (!@file_exists("$pageurl") && !is_dir($pageurl)) $pageurl = rewriteurl($pageurl);
		elseif (is_dir($pageurl)) {
			
			$fileendings = array ("index.html", "index.shtml", "index.htm", "index.php", "index.cfm", "index.phtml", "index.asp", "index.aspx", "default.html", "default.shtml", "default.htm", "default.php", "default.cfm", "default.phtml", "default.asp", "default.aspx"); // edit this if you have a custom default page
			
			foreach ($fileendings as $value) { 
								
				if (@file_exists("$pageurl$value")) {
					$pageurl = "$pageurl$value";
					break;
				}
				
			}
		
		}
		
	}
	
?>