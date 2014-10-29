<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
		
		include_once('createsession.php');
		if ($_POST['page']) $_SESSION['un1fyCurpage'] = $_POST['page'];
	
	}
	
?>