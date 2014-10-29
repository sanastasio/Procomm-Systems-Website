<?php

	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
		
		include_once('createsession.php');
		
		if (isset($_SESSION['un1fyUserid']) && isset($_SESSION['un1fyIsadmin']) && $_SESSION['un1fyIsadmin'] == 'Y') {
			
			include_once('urldissect.php');
			include_once('ftpstart.php');
			include_once('geturi.php');
			
			$ch = curl_init();
			
			if($ch) {
				
				$scripturl = $_SERVER['HTTP_HOST'] . uri();
				
				$path = explode('/', $scripturl);
				$path = $path[sizeof($path) - 3];
							
				if($ftpconn) {
					
					$_SESSION['un1fyUpdatePath'] = $path;
					
					if (copy('update.php', '../../_un1fyupdate000.php')) header('Location: ../../_un1fyupdate000.php');
					else {
						
						$update = fopen('update.php', "r");
						$upcontent = stream_get_contents($update);
						fclose($update);
						
						$uptemp = tmpfile();
						if (!$uptemp) {
							$uptempp = tempnam(dirname(__FILE__).'/../settings/tmp', 'Uptemp');
							chmod($uptempp, 0777);
							$uptemp = fopen($uptempp, 'w+');
						}
						
						fwrite($uptemp, $upcontent);
						rewind($uptemp);
							
						$path = urldissect($_SERVER['SCRIPT_NAME'], false, 3);
						$filename = urldissect($url, true, false);
						
						$remotefile = $ftpwd.$path.'_un1fyupdate000.php';
						
						if (ftp_fput($ftpconn, $remotefile, $uptemp, FTP_BINARY)) header('Location: ../../_un1fyupdate000.php');
						else {
							
							$message = '<strong>Error:</strong> Your permissions are not set to allow auto-update.';
					
							$script = '<script language="javascript" type="text/javascript">window.top.window.userBar("'.$message.'");</script>';
							echo $script;
						
						
						}
						
						fclose($uptemp);
						if(isset($uptempp)) unlink($uptempp);
						
					}
				}
				else {
					
					$message = "<strong>Error:</strong> You need to enter your FTP info to do auto-updates. Please <a href='ftpinfo.php' title='Enter your FTP info'>enter your FTP info here</a>.";
					
					$script = '<script language="javascript" type="text/javascript">window.top.window.userBar("'.$message.'");</script>';
					echo $script;
					
				}
				
				curl_close($ch);
				
			}
			else {
			
				$message = '<strong>Error:</strong> Your server needs cURL in order to auto-update.';
				
				$script = '<script language="javascript" type="text/javascript">window.top.window.userBar("'.$message.'");</script>';
				echo $script;
				
			}
						
		}
		
		print_r($response);
	
	}

?>