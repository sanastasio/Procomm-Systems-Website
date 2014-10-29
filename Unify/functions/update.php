<?php

	error_reporting(E_ALL & ~E_NOTICE);
	@session_start();
	
	include_once($unifypath.'/functions/createsession.php');
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
		
		$unifypath = $_SESSION['un1fyUpdatePath'];
		$response = array(
		
			'status' => '<strong>Error:</strong> There was an error updating. Please try a manual update.',
			'readout' => ''
			
		);
		
		if (isset($_SESSION['un1fyUserid']) && isset($_SESSION['un1fyIsadmin']) && $_SESSION['un1fyIsadmin'] == 'Y') {
			
			include_once($unifypath.'/functions/urldissect.php');
			include_once($unifypath.'/functions/ftpstart.php');
			
			$ch = curl_init();
			
			if($ch) {
				
				if($ftpconn) {
					
					$path = urldissect($_SERVER['SCRIPT_NAME'], false, 1);
					
					$ftpvars = array(
						
						'ftps' => $ftps,
						'ftpu' => $ftpu,
						'ftpp' => $ftpp,
						'ftpwd' => $ftpwd,
						'remote' => $ftpwd.$path.'un1fyupdate/'
						
					);
					
					set_time_limit(300);
					
					curl_setopt($ch, CURLOPT_URL, 'http://unify.unitinteractive.com/functions/app/update-serv-130.php');
					curl_setopt($ch, CURLOPT_HEADER, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch,CURLOPT_POST,count($ftpvars));
					curl_setopt($ch,CURLOPT_POSTFIELDS,$ftpvars);
					curl_setopt($ch, CURLOPT_TIMEOUT, 300);

					$res = curl_exec($ch);
					
					print_r($res);
					
					$sitepath = urldissect($_SERVER['SCRIPT_NAME'], false, 1);
					
					function transfer($path) {
						
						global $ftpconn, $ftpwd, $update, $sitepath, $unifypath, $response;
						
						$entries = Array();
							
						if (is_dir($path)) {
							
							$path = rtrim(str_replace("\\", "/", $path), '/') . '/';
							
							$dir = dir($path);
							
							$updatepath = $update.str_replace($unifypath.'/', '', $path);
							
							if($ftpconn && @ftp_mkdir($ftpconn, $updatepath)) $response['readout'] .= "<br/><br/>Made directory: $updatepath<br/><br/>--------------------------<br/><br/>";
							else $response['readout'] .= "<br/><br/>Error making directory: $updatepath<br/><br/>--------------------------<br/><br/>";
							
							while (false !== ($entry = $dir->read())) {
							
								if ($entry != '.' && $entry != '..' && !preg_match('/\.DS_Store/', $entry)) {
									$entries[] = $path.$entry;
								}
								
							}
							
							$dir->close();
							
						}
						else $entries[] = $path;
						
						foreach ($entries as $entry) {
							
							$entry = trim($entry, "/");
							
							if (is_dir($entry)) {
								
								$updatedir = $update.str_replace($ftpwd.$sitepath.$unifypath, '', $entry);
								
								$response['readout'] .= "Making directory: $updatedir<br/>";
								
								if($ftpconn && @ftp_mkdir($ftpconn, $updatedir)) $response['readout'] .= "Made directory: $updatedir<br/><br/>--------------------------<br/><br/>";
								else $response['readout'] .= "Error making directory: $updatedir<br/><br/>--------------------------<br/><br/>";
								
								transfer($entry);
								
							}
							else {
								
								$updatefile = $update.str_replace($unifypath.'/', '', $entry);
								
								$response['readout'] .= "Transferring: $entry<br/>";
								
								$doc = fopen($entry, 'r');
								$content = stream_get_contents($doc);
								fclose($doc);
								
								$file = tmpfile();
								if (!$file) {
									$filep = tempnam(dirname(__FILE__).'/../settings/tmp', 'File');
									chmod($filep, 0777);
									$file = fopen($filep, 'w+');
								}
								
								fwrite($file, $content);
								rewind($file);
								
								if($ftpconn && ftp_fput($ftpconn, $updatefile, $file, FTP_BINARY)) $response['readout'] .= "Successfully transferred: $updatefile<br/><br/>--------------------------<br/><br/>";
								else $response['readout'] .= "Error transferring: $updatefile<br/><br/>--------------------------<br/><br/>";
								
								fclose($file);
								if(isset($filep)) unlink($filep);
								
							}
						
						}
								
					}
					
					function deleteFolder($path) {
						
						global $ftpconn, $ftpwd, $sitepath, $response;
						
						$path = rtrim(str_replace("\\", "/", $path), '/') . '/';
						
						if (is_dir($path)) {
							
							$dir = dir($path);
							
							while (false !== ($entry = $dir->read())) {
							
								if ($entry != '.' && $entry != '..') {
									$entries[] = $path.$entry;
								}
								
							}
						
							$dir->close();
							
						}
						else $entries[] = $path;
						
						foreach ($entries as $entry) {
						
							if (is_dir($entry)) {
								
								deleteFolder($entry);
								
								$deletedir = $ftpwd.$sitepath.$entry;
								
								$response['readout'] .= "Deleting directory: $deletedir<br/>";
								
								if($ftpconn && @ftp_rmdir($ftpconn, $deletedir)) $response['readout'] .= "Removed directory: $deletedir<br/><br/>--------------------------<br/><br/>";
								else $response['readout'] .= "Error removing directory: $deletedir<br/><br/>--------------------------<br/><br/>";
								
								
								
							}
							else {
								
								$deletefile = $ftpwd.$sitepath.$entry;
								
								$response['readout'] .= "Deleting: $entry<br/>";
								
								if($ftpconn && ftp_delete($ftpconn, $deletefile)) $response['readout'] .= "Successfully deleted: $deletefile<br/><br/>--------------------------<br/><br/>";
								else $response['readout'] .= "Error deleting: $deletefile<br/><br/>--------------------------<br/><br/>";
								
							}
						
						}
						
						if($ftpconn && @ftp_rmdir($ftpconn, $path)) $response['readout'] .= "Removed directory: $path<br/><br/>--------------------------<br/><br/>";
						else $response['readout'] .= "Error removing directory: $deletedir<br/><br/>--------------------------<br/><br/>";
								
					}
					
					$update = $ftpwd.$sitepath.'un1fyupdate/';
					
					$backups = $unifypath.'/backups';
										
					transfer($backups);
					transfer($unifypath.'/functions/ftpvars.php');
					
					deleteFolder($unifypath);
					
					if(ftp_rename($ftpconn, $update, $ftpwd.$sitepath.$unifypath)) $response['readout'] .= "New Unify renamed<br/><br/>";
					else $response['readout'] .= "Error renaming new Unify<br/><br/>";
					
					if(@file_exists($unifypath.'/index.php')) {
						$response['status'] = 'true';
					
						$script = '<script language="javascript" type="text/javascript">window.top.window.autoUpdateSuccess();</script>';
						echo $script;
					}
					
					print_r($response);
					
					curl_close($ch);
					
				}
				
			}
						
		}
	
	}

?>