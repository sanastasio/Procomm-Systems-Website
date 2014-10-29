<?php

	function thecleaner($stuff) {
						
		global $message, $encode, $doctype;
		
		$code = array();
		$phpreg = '/<\?(.|\n|\r)+?\?>/';
		$aspreg = '/<%(.|\n|\r)+?\%>/';
		$jsreg = '/<script\s[^>]*>\s*[^<](.|\n)*?<\/\s*script>/';
		$prereg = '/<pre[^>]*>(.|\n)*?<\/\s*pre>/';
		$codereg = '/<code[^>]*>(.|\n)*?<\/\s*code>/';
		$cssreg = '/<style[^>]*>(.|\n)*?<\/\s*style>/';
		
		$stufflen = strlen($stuff);
		
		try {
		
			//grab php
			preg_match_all($phpreg, $stuff, $code['PHP']);
			$stuff = preg_replace($phpreg, '----||PHPGO3SHERE||----', $stuff);
			
			//grab asp
			preg_match_all($aspreg, $stuff, $code['ASP']);
			$stuff = preg_replace($aspreg, '----||ASPGO3SHERE||----', $stuff);
			
			//grab on-page js
			preg_match_all($jsreg, $stuff, $code['JS']);
			$stuff = preg_replace($jsreg, '----||JSGO3SHERE||----', $stuff);
			
			//grab on-page CSS
			preg_match_all($cssreg, $stuff, $code['CSS']);
			$stuff = preg_replace($cssreg, '----||CSSGO3SHERE||----', $stuff);
		
		}
		catch (Exception $e) {
		
			$message = '<strong>Error:</strong> There may be too much on page Javascript or CSS. Please try moving them to external files and/or validating your HTML.';
			output();
			
		}
		
		$stuff = stripslashes($stuff);
		$stuff = trim($stuff);
		
		//grab pre
		preg_match_all($prereg, $stuff, $code['PRE']);
		$stuff = preg_replace($prereg, '----||PREGO3SHERE||----', $stuff);
		
		//grab code
		preg_match_all($codereg, $stuff, $code['CODE']);
		$stuff = preg_replace($codereg, '----||CODEGO3SHERE||----', $stuff);
		
		//fix uppercase tags
		$stuff = preg_replace_callback('/<(\/|)[A-Z]+[A-Z0-9]*\b/', create_function( '$matches', 'return strtolower($matches[0]);' ), $stuff);
		
		//fix attribute quotes
		$stuff = preg_replace('/(<(?!meta)[^>]*\s[A-Za-z]+=)([^>\'\"\s]+)(\s*[^>]*)/', '$1"$2"$3', $stuff);
		
		echo $doctype;
		
		// fix self-closing tags
		if (preg_match('/XHTML/', $doctype)) {
			$stuff = preg_replace("/(<img[^>%]*[^\/%])(>)/i", "$1/>", $stuff);
			$stuff = preg_replace("/<br\s*>/", "<br />", $stuff);
		}
				
		$nstuff = htmlentities($stuff, ENT_QUOTES, $encode);
		if ($nstuff == '') $nstuff = htmlentities($stuff, ENT_QUOTES, 'ISO-8859-15');
		$stuff = $nstuff;
		
		$stuff = str_replace("&lt;","<",$stuff);
	    $stuff = str_replace("&gt;",">",$stuff);
	    $stuff = str_replace("&amp;",'&',$stuff);
	    $stuff = str_replace("&#039;","'",$stuff);
	    $stuff = str_replace("&quot;",'"',$stuff);
		
		// remove TinyMCE stuff
		preg_match_all('/(?:<[^>]*)(_mce_[^=]+=(?:\"|\'|)[^\"]*(?:\"|\'|)\s*)/', $stuff, $mces);
		foreach($mces[1] as $mce) { $stuff = str_replace($mce, "", $stuff); }
		
		// add back original code
		
		$replace = array('CODE', 'PRE', 'CSS', 'JS', 'ASP', 'PHP');
		
		foreach ($replace as $r ) {
			$rreg = '/\-\-\-\-\|\|'.$r.'GO3SHERE\|\|\-\-\-\-/';
			$c = 0;
			
			preg_match_all($rreg, $stuff, $newcode);
			
			foreach ($newcode[0] as $ncode) {
				
				$insert = preg_quote($code[$r][0][$c]); // avoid accidental regex replacement matches
				$stuff 	= preg_replace($rreg, $insert, $stuff, 1);
				$stuff 	= stripcslashes($stuff);

				$c++;
			}		
			$c = 0;
			
		}
		
		// remove extra line breaks
		$stuff = preg_replace("/\r\n/","\n", $stuff);
		
		if ($stuff != '' || $stufflen == 0) return $stuff;
		else $message = '<strong>Error:</strong> Some characters couldn&rsquo;t be recognized. Try switching to UTF-8 encoding and be sure all special characters are properly encoded.';
		
	}

?>