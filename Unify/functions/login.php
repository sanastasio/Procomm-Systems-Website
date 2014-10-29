<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
		
		include_once('createsession.php');
		
		$loginproxy = 'http://unify.unitinteractive.com/functions/app/login-serv.php?';
		
		if ($_POST) {
		
			include_once( 'getpath.php' );
			
			$logingets = 'un1fyUsername=' . urlencode($_POST['un1fyUsername']) . '&un1fyPassword=' . urlencode($_POST['un1fyPassword']) . '&un1fySess=' . urlencode($_SESSION['un1fySess']) . '&un1fyPath=' . urlencode($path);

			header("Location:$loginproxy$logingets");
			
		}
		elseif (isset($_GET['un1fyUsername']) && isset($_GET['un1fySess']) && $_GET['un1fySess'] == $_SESSION['un1fySess'] && isset($_GET['un1fyURL']) && isset($_GET['isAdmin'])) {
					
			$_SESSION['un1fyUserid'] = $_GET['un1fyUsername'];
			$_SESSION['un1fyUserurl'] = $_GET['un1fyURL'];
			$_SESSION['un1fyIsadmin'] = $_GET['isAdmin'];
			
			include_once( 'getsaveprefs.php' );
			
			if(isset($_SESSION['un1fyUserid']) && $_SESSION['un1fyUserid'] != '') {
				$script = '<script type="text/javascript">window.top.window.location="../";</script>';
				echo $script;
			}
				
		}
		elseif (isset($_GET['error'])) {
			
			if ($_GET['error'] == 'login') $message = "Incorrect username or password";
			elseif ($_GET['error'] == 'domain') $message = "Domain not registered. <a href='http://unify.unitinteractive.com/purchase.php'>Please register</a>.";
				
			$script = '<script type="text/javascript">window.top.window.loginIncorr("'.$message.'");</script>';
			echo $script;
		}
	
	}
	else {
	
		$script = '<script type="text/javascript">window.top.window.loginIncorr("There was an error creating a session.");</script>';
		echo $script;
	
	}
	
?>
