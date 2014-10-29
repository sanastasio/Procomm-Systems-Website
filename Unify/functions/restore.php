<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
	
		include_once('createsession.php');
		
		if (isset($_SESSION['un1fyUserid'])) {
			
			include_once('urldissect.php');
			include_once('ftpstart.php');
			include_once('checkpath.php');
			
			$message = '<strong>Error:</strong> There was an error restoring your page. Please try again.';
			
			if (@file_exists($pageurl)) {
				
				function loadfile($url, $incpath, $inc) {
					
					global $message, $pagepath;
										
					// read the file
					
					$doc = fopen($url, "r");
					$content = stream_get_contents($doc);
					fclose($doc);
					
					$reginclude = '/(?:include\b|include_once\b|require\b|require_once\b)(?:\s*\(*[^;\?>]*)(?:\"|\')([^\"\'<>]*)(?:(\"|\')\)*(;|[\s\t\r\n]*\?>))/i'; // php include regexp
					if (!preg_match($reginclude, $content)) $reginclude = '/(?:<!--#include\s(?:virtual|file)\=)(?:\"|\')([^\"\'<>]*)(?:\"|\')(?:\s*-->)/i'; // SSI regexp
					
					echo "\n\nURL: $url\n\n";
									
					// read the backup
					$pagename = explode('../../', $url); //explode($_SERVER['DOCUMENT_ROOT'], $url);
					$pagename = trim($pagename[1], '/');
					echo "\n\nPagename: $pagename\n\n";
					$pagename = str_ireplace('/', '.', $pagename);
					$pagename = str_ireplace('..', 'back', $pagename);
					
					// create a backup
					$backupname = preg_replace('/(\.php|\.asp|\.aspx|\.html|\.htm|\.phtml|\.shtml|\.cfm)$/', "_Bak$1", $pagename);
					$backup = dirname(__FILE__).'/../backups/'.$backupname;
					echo "\n\nBackup: $backup\n\n";
							
					if (@file_exists($backup)) {
						
						$backdoc = fopen($backup, "r");
						$backcontent = stream_get_contents($backdoc);
						
						fclose($backdoc);
						
						echo "File Found: $backup\n\n";
						
						editfile($content, $backcontent, $url, $incpath, $inc);
						
					}
					
					if (preg_match($reginclude, $content)) {
						
						preg_match_all($reginclude, $content, $includes);		
						
						foreach( $includes[1] as &$value) {
							
							echo "\n\n-----------------------------\nInclude Path: $value\n\n";
													
							if (@file_exists($_SERVER['DOCUMENT_ROOT'].$value) && $value != '') {
								
								$incfile = $_SERVER['DOCUMENT_ROOT'].$value;
								$incpath = false;
							
							}
							elseif (!stristr($value, 'http://')) {
							
								$incfile = "$pagepath/$value";
								
								if (!$incpath) {
									
									preg_match_all('/\.\.\/\.\.\/([^\s]+)\/[^\/]+/', $incfile, $incorigpath);
									
									if (($incorigpath[1][0]) != '') {
									
										$incpath =  $incorigpath[1][0];
										$newinc = "$pagepath/$incpath/$incorigpath";
										
										$incfile = (@file_exists($newinc)) ? $newinc : $incfile;
									
									}											
									
								}								
								
							}
							else $incpath = false;
							
							$incfile = (@file_exists($incfile)) ? $incfile : str_replace('../../', '../../'.$incpath.'/', $incfile);
							$incfile = (@file_exists($incfile)) ? $incfile : preg_replace('/\.\.\/\.\.\/[^\s]+\/([^\/]+)/', '../../'.$incpath."/$1", $incfile);
							
							if (!preg_match('/\/$/', $incfile)) loadfile($incfile, $incpath, true);
							
						}
						
					}
				}
				
				function editfile($content, $backcontent, $url, $incpath, $inc) {
					
					global $ftpconn, $ftpwd;
					
					if ($content != $backcontent && ((!stristr($content, '<?php') && !strstr($content, '<%') ) || (preg_match('/>[^<]+</', $backcontent) || preg_match('/<html/', $backcontent)) && strstr($backcontent, 'unify'))) {
						
						if ($ftpconn) {
							
							$restdoc = tmpfile();
							if (!$restdoc) {
								$restdocp = tempnam(dirname(__FILE__).'/../settings/tmp', 'Rest');
								chmod($restdocp, 0777);
								$restdoc = fopen($restdocp, 'w+');
							}
							
							fwrite($restdoc, $backcontent);
							rewind($restdoc);
							
							if (strstr($url, $_SERVER['DOCUMENT_ROOT'])) {
							
								if( $inc ) 
								{
									$remotefile = preg_replace( '/.*' . str_replace( '/', '\/', $ftpwd ) . '/', $ftpwd, $url );
								}
								else 
								{
									$fkey 		= explode('../../', $url, 2);
									$path 		= urldissect($fkey[0], false, 3);
									$save_dir 	= $ftpwd; // store the ftp root dir
												
									if( $save_dir === '/' ) // if the root dir is just "/"
									{
										$save_dir = explode( '/', $_SERVER['PHP_SELF'] ); // find out where save.php is and split it into parts
										$save_dir = $save_dir[1]; // take the second part - should be the proper dir
									}
									
									// $remotefile is our best guess at the proper file to replace
									$remotefile = preg_replace('/.*'.str_replace('/', '\/', $save_dir).'/', $save_dir, $path).$fkey[1];
									
									// alternately we'll try the exact path entered by the user in ftp for the base path
									$plan_b = $ftpwd . $fkey[1];
								}
								
							}
							else {
								$fkey = explode('../../', $url, 2);
								$path = urldissect($fkey[0], false, 3); 
								$remotefile = preg_replace('/.*'.str_replace('/', '\/', $ftpwd).'/', $ftpwd, $path).$fkey[1];
							}
							
							echo "\n\nRemoteFile: $pagename\n\n";
							
							// try our best guess first - this usually works
							if( ftp_fput( $ftpconn, $remotefile, $restdoc, FTP_BINARY ) ) 
							{
								echo "Uploaded: $remotefile!\n\n";
							}
							// hail mary effort
							elseif( ftp_fput( $ftpconn, $plan_b, $restdoc, FTP_BINARY ) )
							{
								echo "Uploaded: $plan_b!\n\n";
							}
							// failed to upload the changes
							else 
							{
								$message = '<strong>Error:</strong> Unify could not find this page: <em>'.$remotefile.'</em>';
							}
							
							fclose($restdoc);
							if(isset($restdocp)) unlink($restdocp);
						
						}
						elseif (is_writable($url)) {
							//restores file
							$restdoc = fopen($url, "w");
							fputs($restdoc, $backcontent);
							fclose($restdoc);
						}
						else $message = "<strong>Error:</strong> You do not have permission to edit this page. Try <a href='settings/ftpinfo.php' title='Enter your FTP info'>saving your FTP info here</a>.";
						
					}
					
				}
				
				loadfile($pageurl, false, false);
				
				$message = 'Your page was successfully restored.';
				$script = '<script language="javascript" type="text/javascript">window.top.window.ifrReload();window.top.window.saveFeedback("'.$message.'");</script>';
				echo $script;
				
			}
			else {
				$message = '<strong>Error:</strong> Unify could not find this page: <em>'.$url.'</em>';
				$script = '<script language="javascript" type="text/javascript">window.top.window.saveFeedback("'.$message.'");</script>';
				echo $script;
			}
			
			if ($ftpconn) ftp_close($ftpconn);
			
		}
	}
	
?>