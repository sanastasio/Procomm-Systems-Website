<?php
	
	//error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
		
		include_once('urldissect.php');
		include_once('createsession.php');
		
		if (isset($_SESSION['un1fyUserid']) && isset($_SESSION['un1fyIsadmin']) && $_SESSION['un1fyIsadmin'] == 'Y') {
			
			extract($_POST);
			
			$ftpconn = false;
			
			$ftpu = ($ftpu!='') ? $ftpu : false;
			$ftpp = ($ftpp!='') ? $ftpp : false;
			$ftps = ($ftps!='') ? $ftps : false;

			if( $ftpwd == '' ) $ftpwd = FALSE; // FALSE if the user entered nothing into the 'domain root' field
			
			if ($ftpu && $ftpp && $ftps && $ftpwd) {
			
				$ftpvars = "<?php\n\n";
				$ftpvars .= "\t".'$ftpu = \''.$ftpu.'\''.";\n";
				$ftpvars .= "\t".'$ftpp = \''.$ftpp.'\''.";\n";
				$ftpvars .= "\t".'$ftps = \''.$ftps.'\''.";\n";
				$ftpvars .= "\t".'$ftpwd = \''.$ftpwd.'\''."\n\n";	
				$ftpvars .= '?>';
		
				include_once('ftpconnect.php');
				ftpconnect();
					
				if ($ftpconn) {
								
					$ftpdoc = tmpfile();
					if (!$ftpdoc) {
						$ftpdocp = tempnam(dirname(__FILE__).'/../settings/tmp', 'Ftpdoc');
						chmod($ftpdocp, 0777);
						$ftpdoc = fopen($ftpdocp, 'w+');
					}
					
					fwrite($ftpdoc, $ftpvars);
					rewind($ftpdoc);
					
					$path = urldissect($_SERVER['SCRIPT_NAME'], false, 1);
					
					$remotefile = $ftpwd.$path.'ftpvars.php';
					
					if (ftp_fput($ftpconn, $remotefile, $ftpdoc, FTP_BINARY)) {
						
						$getmessage = "Your FTP information was successfully saved.";
						$savescript = '<script type="text/javascript">window.top.window.userBar("'.$getmessage.'");</script>';
						echo $savescript;
						
					}
					else { // the ftp connection was made successfully, so there is something wrong with the domain root
						$getmessage = "<strong>Error:</strong> The domain root is set incorrectly. Please double check that you have the correct domain root entered. <a href='http://unify.desk.com/customer/portal/articles/410397-setting-ftp-info' title='FTP Info for Unify'>More info here.</a>";
						$savescript = '<script type="text/javascript">window.top.window.userBar("'.$getmessage.'");</script>';
						echo $savescript;
					}
					
					fclose($ftpdoc);
					if(isset($ftpdocp)) unlink($ftpdocp);
				}
				
				echo '<script type="text/javascript">window.top.window.removeFrames();</script>';
			
			}
			else {
			
				$getmessage = '<strong>Error:</strong> All fields are required.';
				$savescript = '<script type="text/javascript">window.top.window.userBar("'.$getmessage.'");</script>';
				echo $savescript;
				
				echo '<script type="text/javascript">window.top.window.removeFrames();</script>';
			
			}
				
		}
	
	}
	
?>