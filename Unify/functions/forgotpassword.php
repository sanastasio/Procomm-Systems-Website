<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
	
		include_once ('createsession.php');
		
		$forgotproxy = 'http://unify.unitinteractive.com/functions/app/forgotpass-serv.php?';
		
		if ($_POST && $_POST['un1fyUsername'] ) {
			
			include_once( 'getpath.php' );
			
			$forgotgets = 'un1fySess=' . urlencode($_SESSION['un1fySess']) . '&un1fyPath=' . $path . '&un1fyAcctUser=' . urlencode($_POST['un1fyUsername']);
			
			header("Location:$forgotproxy$forgotgets");
			
		}
		elseif ($_GET && $_GET['un1fySess'] == $_SESSION['un1fySess']) {
		
			$m = $_GET['un1fyPassMessage'];
			
			echo $m;
				
			if ($m == '0') $getmessage = "Your password was reset and an email was sent.";
			elseif ($m == '1') $getmessage = "There is no such user on this domain.";
			
			$script = '<script type="text/javascript">window.top.window.forgotReset("'.$getmessage.'");</script>';
			echo $script;
			
		}
	}
	
?>