<?php 

	@session_start();
	
	if (isset($_SESSION['un1fySess']) && isset($_SESSION['un1fyUserid'])) {
	
		session_name($_SESSION['un1fySess']);
		@session_start(); 
		
		$user = &$_SESSION['un1fyUserid'];
		$admin = &$_SESSION['un1fyIsadmin'];
		$sess = &$_SESSION['un1fySess'];
		
		if ( $admin != 'Y' ) header("Location:../index.php");
	
	}
	else header("Location:../login.php");
	
	if(file_exists('../../_un1fyupdate000.php')) {
		
		include_once('../functions/ftpstart.php');
		include_once('../functions/urldissect.php');
		
		if($ftpconn) {
			
			$updatepath = urldissect($_SERVER['SCRIPT_NAME'], false, 3);
			ftp_delete($ftpconn, $ftpwd.$updatepath.'_un1fyupdate000.php');
			ftp_close($ftpconn);
			
		}
		else {
		
			unlink('../../_un1fyupdate000.php');
		
		}
		
	}

?>