<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	
	$doc = file_get_contents("./login.php", true);
	$doc = stripslashes($doc);
	
	$cb = (strstr($doc, "Copyright <?php echo @date('Y'); ?> Unit Interactive, LLC<br />")
			&& strstr($doc, '<h1 class="logo">Unify</h1>')
			&& filesize('./unify-images/unify_sprite1.png') == '36437') ? true : false;//'36529'
	

?>