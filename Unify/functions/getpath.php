<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
		
		include_once('geturi.php');
		include_once('createsession.php');
		
		$pathproxy = 'http://unify.unitinteractive.com/functions/app/prefs-serv.php?';
		$scripturl = $_SERVER['HTTP_HOST'] . uri();
		
		$path = explode('/', $scripturl);
		$path = $path[sizeof($path) - 3];
		
		if (isset($_SESSION['un1fyPath']) && $_SESSION['un1fyPath'] != $path ) {
		
			$pathgets = 'un1fyUsername=' . urlencode($_SESSION['un1fyUserid']) . '&un1fySess=' . urlencode($_SESSION['un1fySess']) . '&un1fyPath=' . urlencode($_POST['un1fyHTML']);
			
			header("Location:$pathproxy$pathgets");
		
		}	
	
	}
	
?>