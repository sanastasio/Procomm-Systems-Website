<?php 
	
	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
	
		include_once('createsession.php');
		
		if (isset($_SESSION['un1fyUserid'])) {
			
			$prefproxy = 'http://unify.unitinteractive.com/functions/app/prefs-serv.php?';
			
			if ($_POST) {
		
				include_once( 'getpath.php' );
				
				$filePath = (!preg_match('/^[a-z0-9\/\-\_\s]+$/i', $_POST['filePath'])) ? '' : trim($_POST['filePath'], '/'). '/';
				$imagePath = (!preg_match('/^[a-z0-9\/\-\_\s]+$/i', $_POST['imagePath'])) ? '' : trim($_POST['imagePath'], '/'). '/';
				
				$exFolders = (!preg_match('/^[a-z0-9\/\,\s\-\.\_\s]+$/i', $_POST['exFolders'])) ? '' : trim($_POST['exFolders'], '/');
				$exFolders = trim($exFolders, ',');
				
				$prefgets = 'un1fyUsername=' . urlencode($_SESSION['un1fyUserid']) . '&un1fySess=' . urlencode($_SESSION['un1fySess']) . '&un1fyHTML=' . urlencode($_POST['htmltoggle']) . '&un1fyFiles=' . urlencode($_POST['filetoggle']) . '&un1fyFilePath=' . urlencode($filePath) . '&un1fyImagePath=' . urlencode($imagePath) . '&un1fyExFolders=' . urlencode($exFolders) . '&un1fyPath=' . urlencode($path);
				
				header("Location:$prefproxy$prefgets");
				
			}
			elseif (empty($_GET)) {
		
				include_once( 'getpath.php' );
		
				$prefgets = 'un1fyUsername=' . urlencode($_SESSION['un1fyUserid']) . '&un1fySess=' . urlencode($_SESSION['un1fySess']) . '&un1fyPath=' . urlencode($path);
				
				header("Location:$prefproxy$prefgets");
				
			}
			elseif ($_GET && $_GET['un1fySess'] == $_SESSION['un1fySess'] && $_GET['un1fyHTML'] && $_GET['un1fyPath'] && $_GET['un1fyFiles'] && $_GET['un1fyLatest']) {
								
				$_SESSION['un1fyHTML'] = $gethtml = $_GET['un1fyHTML'];
				$_SESSION['un1fyPath'] = $getpath = $_GET['un1fyPath'];
				$_SESSION['un1fyFiles'] = $getfile = $_GET['un1fyFiles'];
				$_SESSION['un1fyFilePath'] = $getfilepath = $_GET['un1fyFilePath'];
				$_SESSION['un1fyImagePath'] = $getimagepath = $_GET['un1fyImagePath'];
				$_SESSION['un1fyExFolders'] = $getexfolders = $_GET['un1fyExFolders'];
				$_SESSION['un1fyLatest'] = $_GET['un1fyLatest'];
				
				if($_GET['un1fyPrefSave'] == 'Y') {
					
					$getmessage = "Your Preferences were successfully saved.";
					$savescript = '<script type="text/javascript">window.top.window.userBar("'.$getmessage.'");</script>';
					echo $savescript;
				
				}
				
				$script = '<script type="text/javascript">window.top.window.getGlobals("'.$getpath.'", "'.$gethtml.'", "'.$getfile.'", "'.$getfilepath.'", "'.$getexfolders.'", "'.$getimagepath.'");</script>';
				echo $script;
			
			}
		}
	}	
	
?>