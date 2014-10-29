<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
	
		include_once('createsession.php');
		
		if (isset($_SESSION['un1fyUserid'])) {
		
			$userproxy = 'http://unify.unitinteractive.com/functions/app/users-serv.php?';
		
			if ($_POST && $_POST['un1fyAcctUser'] && $_POST['un1fyAcctEmail'] && $_POST['un1fyAcctUser-orig'] && $_POST['un1fyAcctEmail-orig'] ) {
				
				include_once( 'getpath.php' );
				
				$usergets = 'un1fyUsername=' . urlencode($_SESSION['un1fyUserid']) . '&un1fySess=' . urlencode($_SESSION['un1fySess']) . '&un1fyPath=' . urlencode($path) . '&un1fyAcctUser=' . urlencode($_POST['un1fyAcctUser']) . '&un1fyAcctEmail=' . urlencode($_POST['un1fyAcctEmail']) . '&un1fyOrigEmail='. urlencode($_POST['un1fyAcctEmail-orig']) .'&un1fyOrigUser='. urlencode($_POST['un1fyAcctUser-orig']);
				
				if ($_POST['un1fyAcctName']) $usergets .= '&un1fyAcctName=' . urlencode($_POST['un1fyAcctName']);
				if ($_POST['un1fyAcctPass'] != '' && $_POST['un1fyAcctPassConfirm'] != '') $usergets .= '&un1fyAcctPass=' . urlencode($_POST['un1fyAcctPass']) . '&un1fyAcctPassConfirm=' . urlencode($_POST['un1fyAcctPassConfirm']);
				
				header("Location:$userproxy$usergets");
				
			}
			elseif ($_POST && $_POST['un1fyNewAcctUser'] && $_POST['un1fyNewAcctEmail'] && $_POST['un1fyNewAcctName'] && $_POST['un1fyNewAcctPass'] && $_POST['un1fyNewAcctPassConfirm'] ) {
				
				include_once( 'getpath.php' );
				
				$usergets = 'un1fyUsername=' . urlencode($_SESSION['un1fyUserid']) . '&un1fySess=' . urlencode($_SESSION['un1fySess']) . '&un1fyPath=' . urlencode($path) . '&un1fyNewAcctUser=' . urlencode($_POST['un1fyNewAcctUser']) . '&un1fyNewAcctEmail=' . urlencode($_POST['un1fyNewAcctEmail']) . '&un1fyNewAcctName='. urlencode($_POST['un1fyNewAcctName']) . '&un1fyNewAcctPass=' . urlencode($_POST['un1fyNewAcctPass']) . '&un1fyNewAcctPassConfirm=' . urlencode($_POST['un1fyNewAcctPassConfirm']);
				
				header("Location:$userproxy$usergets");
				
			}		
			elseif (empty($_GET)) {
				
				include_once ( 'getpath.php' );
				
				$usergets = 'un1fyUsername=' . urlencode($_SESSION['un1fyUserid']) . '&un1fySess=' . urlencode($_SESSION['un1fySess']) . '&un1fyPath=' . urlencode($path);
				
				header("Location:$userproxy$usergets");
				
			}
			elseif ($_GET && $_GET['un1fyDelUser']) {
				
				include_once ( 'getpath.php' );
				
				$usergets = 'un1fyUsername=' . urlencode($_SESSION['un1fyUserid']) . '&un1fySess=' . urlencode($_SESSION['un1fySess']) . '&un1fyPath=' . urlencode($path) . '&un1fyDelUser=' . urlencode($_GET['un1fyDelUser']);
				
				header("Location:$userproxy$usergets");
				
			}
			elseif ($_GET && $_GET['un1fySess'] == $_SESSION['un1fySess']) {
			
				$admin = array();
				$users = array();
			
				foreach ($_GET as $key => $val) {
					
					if (strstr($key, '-A')) $admin[str_replace('-A', '', $key)] = $val;
					elseif (!strstr($key, 'un1fySess') && !strstr($key, 'un1fyUserMessage')) {
						
						$num = explode('-', $key);
						$users[$num[1]][$num[0]] = $val;
						
					}
				
				}
				
				$adminscript = '<script type="text/javascript">window.top.window.adminUser("'.$admin['un1fyName'].'", "'.$admin['un1fyUsername'].'", "'.$admin['un1fyEmail'].'");</script>';
				echo $adminscript;
				
				foreach ( $users as $user ) {
					
					$userscript = '<script type="text/javascript">window.top.window.buildUser("'.$user['un1fyName'].'", "'.$user['un1fyUsername'].'", "'.$user['un1fyEmail'].'", "'.$user['un1fyId'].'");</script>';
					echo $userscript;
				
				}
				
				if( $_GET['un1fyUserMessage'] != '0') {
					
					$m = $_GET['un1fyUserMessage'];
					
					if ($m == '1') $getmessage = "Your account settings were successfully saved.";
					elseif ($m == '2') $getmessage = "Your new account has been successfully created.";
					elseif ($m == '3') $getmessage = "<strong>Error:</strong> This account has already been created.";
					elseif ($m == '4') $getmessage = "Account successfully deleted.";
					elseif ($m == '5') $getmessage = "<strong>Error:</strong> There was an error deleting this account.";
					else $getmessage = "<strong>Error:</strong> There was an error saving.";
					
					$savescript = '<script type="text/javascript">window.top.window.userBar("'.$getmessage.'");</script>';
					echo $savescript;
					
					$_SESSION['un1fyUserid'] = $admin['un1fyUsername'];
				}
				
				echo '<script type="text/javascript">window.top.window.removeFrames();</script>';
			}
		}
	}
	
?>