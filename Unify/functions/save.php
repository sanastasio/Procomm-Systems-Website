<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	include_once('createsession.php');
	include_once('console.php');
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess')) {
	
		if (isset($_SESSION['un1fyUserid'])) {
			
			include_once('urldissect.php');
			include_once('ftpstart.php');
			include_once('checkpath.php');
			
			$message = 'Your page was successfully published.';
			$encode = 'utf-8';
			$doctype = 'xhtml';
			$includes = $conArr = $filecon = array();
						
			$edit = array();			
			$eles = array();
			
			$inc = 0;
			$count = 0;
			
			if ($_POST) {
				
				// begin functions
				
				include_once('cleaner.php');
				
				function cleanpost() {
					
					global $edit;
					
					foreach ($_POST as $key => $post) {
						
						$post = thecleaner($post);

						if (preg_match("/^edit/", $key)) $edit[$key] = $post;
						
					}
					
				}
				
				function loadfile($url, $incpath, $f) {
				
					global $message, $includes, $pagepath, $ftpconn, $ftpwd, $doctype, $encode, $edit;
					
					$ssi = false;
					
					echo "\n\nURL: $url\n\n";
					
					$pagename = (strstr($url, $_SERVER['DOCUMENT_ROOT']) && !strstr($url, '../../')) ? explode($_SERVER['DOCUMENT_ROOT'], $url) : explode('../../', $url);
					$pagename = trim($pagename[1], '/');
					echo "\n\nPagename: $pagename\n\n";
					$pagename = str_ireplace('/', '.', $pagename);
					$pagename = str_ireplace('..', 'back', $pagename);
									
					if(@file_exists($url)) {
						
						// read the file
						$doc = fopen($url, "r");
						$content = stream_get_contents($doc);
						fclose($doc);
						
						if ((!stristr($content, '<?php') && !strstr($content, '<%') && strstr($content, 'unify')) || 
							(preg_match('/>[^<]+</', $content) && strstr($content, 'unify'))) {
						
							if (preg_match('/<!DOCTYPE[^>]*>/', $content)) {
								preg_match_all('/<!DOCTYPE[^>]*>/', $content, $dtarr);
								$doctype = $dtarr[0][0];
							}
							
							if (preg_match('/(?:<meta[^>]*charset=\"*)([^\"<]+)/', $stuff)) {
								preg_match_all($charreg, $stuff, $enc);
								$encode = $enc[1][0];
							}
							
							if ($f) cleanpost();
							
							$elereg = '/<[^>]*class=(?:\"|\'|)[^>]*(\bunify\b|\bunifyRepeatArea\b)[^>]*?(?:\"|\'|)[^>]*>/';
							$reginclude = '/(?:include\b|include_once\b|require\b|require_once\b)(?:\s*\(*[^;\?>]*)(?:\"|\')([^\"\'<>]*)(?:(\"|\')\)*(;|[\s\t\r\n]*\?>))/i'; // php include regexp
							if (!preg_match($reginclude, $content)) $reginclude = '/(?:<!--#include\s(?:virtual|file)\=)(?:\"|\')([^\"\'<>]*)(?:\"|\')(?:\s*-->)/i'; // SSI regexp
							
							// create a backup
							$backupname = preg_replace('/(\.php|\.asp|\.aspx|\.html|\.htm|\.phtml|\.shtml|\.cfm)$/', "_Bak$1", $pagename);
				
							if ( $ftpconn ) {
								
								$backdoc = tmpfile();

								if (!$backdoc) {
									$backdocp = tempnam(dirname(__FILE__).'/../settings/tmp', 'Back');
									chmod($backdocp, 0777);
									$backdoc = fopen($backdocp, 'w+');
								}
								
								fwrite($backdoc, $content);
								rewind($backdoc);
								
								$path = urldissect( $_SERVER['SCRIPT_NAME'], false, 2 );

								$remotefile = $ftpwd . $path . 'backups/' . $backupname;

								if( !ftp_fput( $ftpconn, $remotefile, $backdoc, FTP_BINARY ) )
								{
									$message = '<strong>Error:</strong> Unify failed creating backup for: <em>' . $remotefile . '</em>';
								} 
								
								fclose( $backdoc );
								
								if( isset( $backdocp ) ) 
								{
									unlink( $backdocp );
								}
								
							}
							elseif(is_writable($url)){
								
								$backup = dirname(__FILE__).'/../backups/'.$backupname;
								$backdoc = fopen($backup, "w+");
								fwrite($backdoc, $content);
								fclose($backdoc);
							
							}			
							else $message = '<strong>Error:</strong> You do not have permission to edit this page: <em>'.$pagename."</em>. Try <a href='settings/ftpinfo.php' title='Enter your FTP info'>saving your FTP info here</a>.";
							
							// clean up content
							
							if (preg_match($reginclude, $content)) {
								
								preg_match_all($reginclude, $content, $includes[$url]);
								
								$incArr = preg_split($reginclude, $content);
								$incfiles = $includes[$url][1];
								$neoCon = array();
								
								foreach ( $incArr as $k => $v ) {
									
									$docroot = $_SERVER['DOCUMENT_ROOT'];
									echo "\n\n-----------------------------\nInclude Path: $v\n\n";
									
									if (@file_exists($docroot.$incfiles[$k]) && $incfiles[$k] != '') {
										
										$neoCon[$k] = $v;
										
										$incfile = $docroot.$incfiles[$k];
										$incpath = false;
									
									}
									elseif (!stristr($incfiles[$k], 'http://')) {
										
										$neoCon[$k] = $v;
										
										$incfile = "$pagepath/$incfiles[$k]";
										
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
																		
									if (!preg_match('/\/$/', $incfile)) $neoCon[$incfile] = loadfile($incfile, $incpath, false);
									
								}
								
								return $neoCon;
								
							}
							else return $content;
						}
					}
					else return false;
					
				}
				
				function output() {
					
					global $message;
					
					$script = '<script language="javascript" type="text/javascript">window.top.window.saveFeedback("'.$message.'");</script>';
					echo $script;
					
				}
				
				function conArray($content) {
					
					$conArray = false;
					
					foreach ( $content as $con ) {
					
						if (is_array($con)) $conArray = true;
					
					}
					
					return conArray;
				
				}
				
				function makeEdits($conpost) {
					
					global $edit, $eles, $inc, $count;
					
					$commreg = '/<!--(.|\n|\r)*?-->/';
					
					preg_match_all($commreg, $conpost, $comments);
					
					$conpost = preg_replace($commreg, '<!--!COMMENT!-->', $conpost);
						
					$elereg = '/<[^>]*class\s*=\\\*\s*(?:\"|\'|)[^\"|\']*(\bunify\b|\bunifyRepeatArea\b)[^\"|\'|=]*?(?:\\\*\"|\'|)[^>]*>/';
					
					preg_match_all($elereg, $conpost, $unifyeles);
					
					if ( $unifyeles[0] ) {
						
						$newsplice = '';
						
						foreach ($unifyeles[0] as $unifyele) {
							
							$splice = explode($unifyele, $conpost, 2);
							
							$newsplice = $splice[0];
							
							$post = $splice[1];
							
							$rest = '';
							
							preg_match_all("/^<([^>\s]*)\b/i", $unifyele, $eleTag);
							
							$eleTag = $eleTag[1][0];
							
							if (stristr($eleTag, 'img')) {
								
								if (isset($edit[ "editImg$count" ])) { 
									
									$editStr = array();
								
									$editIncom = $edit[ "editImg$count" ];
									$editIncom = explode(',', $editIncom);
									foreach ($editIncom as $eikey => $eipost) {
										$eipost = explode('::', $eipost);
										$editStr[$eipost[0]] = $eipost[1];
									}
									
									$editContent = '';
									
									if (isset($editStr['link']) && $editStr['link'] != '') {
									
										if( preg_match('/(<a[^>]*>)$/', $newsplice) ) {
											
											preg_match_all('/(<a[^>]*href=(?:\"|\'))([^\"\s]*)((?:\"|\')[^>]*>)$/', $newsplice, $lastlink);
											$newlink = $lastlink[1][0].$editStr['link'];
											if ( $editStr['rel'] != '' ) $newlink .= '" rel="'.$editStr['rel'];
											$newlink .= $lastlink[3][0];
											
											$newsplice = preg_replace('/(<a[^>]*>)$/', "$newlink", $newsplice);
											
										}
										else {
										
											$newsplice .= '<a href="'.$editStr['link'].'"';
											if ( $editStr['rel'] != '' ) $newsplice .= ' rel="'.$editStr['rel'].'"';
											$newsplice .= '>';
											$post = '</a>'.$post;
											
										}
										
									}
									else {
										
										if(preg_match('/(<a[^>]*>)$/', $newsplice)) $newsplice = preg_replace('/(<a[^>]*>)$/', '' , $newsplice);
										if(preg_match('/^(<\/a>)/', $post)) $post = preg_replace('/^(<\/a>)/', '' , $post);
										
									}
									
									$editContent .= '<img src="'.$editStr['src'].'" alt="'.$editStr['alt'].'"';
									if ( $editStr['class'] != '') $editContent .= ' class="'.$editStr['class'].'"';
									if ( $editStr['width'] != '') $editContent .= ' width="'.$editStr['width'].'"';
									if ( $editStr['height'] != '') $editContent .= ' height="'.$editStr['height'].'"';
									$editContent .= '/>'; 
									
								}
								else $editContent = '';
								
								$post = $editContent.$post;
								
							}
							else {
								
								$eleTagReg = '/<\/*'.$eleTag.'[^>]*>/i';
								
								preg_match_all($eleTagReg, $post, $tags);
								
								$contents = preg_split($eleTagReg, $post);
								
								$track = 1;
								
								foreach ( $tags[0] as $tag ) {
								
									if ( strpos( $tag, '</' ) === 0 ) $track--;
									else $track++;
									
									array_shift($contents);
									
									if ( $track == 0 ) break;
									else array_shift($tags[0]);
									
								}
								
								if (sizeof($tags[0]) > 1) {
									
									foreach ( $tags[0] as $tag ) {
										
										if (isset($edit[ "editVid$count" ])) $rest .= $contents[0];
										else $rest .= $tag.$contents[0];
										
										array_shift($contents);
										
									}
																		
								}
								else {
								
									if (isset($edit[ "editVid$count" ])) $rest = $contents[0];
									else  $rest = $tags[0][0].$contents[0];
									
								}
								
							}
							
							$unifyele = preg_replace('/unify\b|unifyRepeatArea\b/', '$0--D0n3--', $unifyele);
							
							if (isset($edit[ "edit$count" ])) $newsplice .= $unifyele."\n".$edit[ "edit$count" ]."\n".$rest;
							elseif (isset($edit[ "editImg$count" ]) && stristr($eleTag, 'img')) $newsplice .= $post;
							elseif (isset($edit[ "editVid$count" ]) && (stristr($eleTag, 'embed') || stristr($eleTag, 'object'))) $newsplice .= "\n".$edit[ "editVid$count" ]."\n".$rest;
							elseif (sizeof($splice) <= 1) $newsplice .= $post;
							else $newsplice .= $unifyele.$post;
							
							$conpost = $newsplice;
							
							$count++;
							
							$rest = '';
						}
						
						$newsplice = preg_replace('/--D0n3--/', '', $newsplice);						
						
					}
					else $newsplice = $conpost;
					
					foreach ( $comments[0] as $com ) {
					
						$newsplice = preg_replace('/<!--!COMMENT!-->/', $com, $newsplice, 1);
					
					}
					
					return $newsplice;
				
				}
				
				function editFile($content, $url) {
				
					global $message, $includes, $edit, $inc;
					
					$fileedits = array();
					
					if (is_array($content)) {
					
						foreach ($content as $conkey => $conpost) {
								
							if (is_array($conpost)) $content[$conkey] = editFile($conpost, $conkey);
							else $content[$conkey] = makeEdits($conpost);
								
						}
						
						foreach ($content as $ckey => $cpost ) {
						
							if (preg_match('/^(\d+)$/', $ckey)) {
								
								if(isset($includes[$url][0][$inc])) $fileedits[$url] .= $cpost.$includes[$url][0][$inc];
								else $fileedits[$url] .= $cpost;
								$inc++;
							}
							else $fileedits["$ckey"] = $cpost;
							
						}
					}
					else $fileedits[$url] = makeEdits($content);
					
					$inc = 0;					
					
					return $fileedits;
					
				}
				
				function cleanFilecon($files) {
					
					global $filecon;
					
					foreach ($files as $fkey => $fpost) {
					
						if (is_array($fpost)) cleanFilecon($fpost);
						else {
							
							if (!$filecon[$fkey]) $filecon[$fkey] = $fpost; 
						
						}
					
					}
				
				}
				
				function flatten($ar) {
				    $toflat = array($ar);
				    $res = array();
				
				    while (($r = array_shift($toflat)) !== NULL) {
				        foreach ($r as $v) {
				                if (is_array($v)) {
				                        $toflat[] = $v;
				                } else {
				                        $res[] = $v;
				                }
				        }
				    }
				
				    return $res;
				}
				
				$conArr = loadfile($pageurl, false, true);
				
				if (is_array($conArr)) {
					$missing = array_search(false,$conArr);
					if ($conArr[$missing] == '') $missing = false;
				}
				elseif ($conArr) $missing = false;
				
				if( $missing === false) {
					
					cleanFilecon( editFile($conArr, $pageurl) );
					
					print_r($filecon);
					
					if (preg_match('/successfully/', $message)) {
											
						foreach ($filecon as $fkey => $fpost) {
								
							if ($fpost != '') {
								
								if(@file_exists($fkey)) {
									
									if ($ftpconn) {
										
										$doc = tmpfile();
										if (!$doc) {
											$docp = tempnam(dirname(__FILE__).'/../settings/tmp', 'Doc');
											chmod($docp, 0777);
											$doc = fopen($docp, 'w+');
										}
										
										$remotefile = '';
										$plan_b;
										
										fwrite($doc, $fpost);
										rewind($doc);
																				
										$incs = flatten($includes);	
																
										if (strstr($fkey, $_SERVER['DOCUMENT_ROOT'])) {
											
											$nfkey = explode($_SERVER['DOCUMENT_ROOT'], $fkey);
											$nfkey = $nfkey[1];
											
											if (in_array($nfkey, $incs)) 
											{
												$remotefile = preg_replace('/.*'.str_replace('/', '\/', $ftpwd).'/', $ftpwd, $fkey);
											}
											else 
											{
												$fkey 		= explode('../../', $fkey, 2);
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
											$fkey = explode('../../', $fkey, 2);
											$path = urldissect($fkey[0], false, 3); 
											$remotefile = preg_replace('/.*'.str_replace('/', '\/', $ftpwd).'/', $ftpwd, $path).$fkey[1];
										}
										
										// try our best guess first - this usually works
										if( ftp_fput( $ftpconn, $remotefile, $doc, FTP_BINARY ) ) 
										{
											echo "Uploaded: $remotefile!\n\n";
										}
										// hail mary effort
										elseif( ftp_fput( $ftpconn, $plan_b, $doc, FTP_BINARY ) )
										{
											echo "Uploaded: $plan_b!\n\n";
										}
										// failed to upload the changes
										else 
										{
											$message = '<strong>Error:</strong> Unify is unable publish this page: <em>'.$remotefile.'</em>';
										}
										
										fclose($doc);
										if(isset($docp)) unlink($docp);
									
									}									
									elseif (is_writable($fkey)) {
										
										$doc = fopen($fkey, "w");
										fwrite($doc, $fpost);
										fclose($doc);
										
									}
									else $message = "<strong>Error:</strong> You do not have permission to edit this page. Try <a href='settings/ftpinfo.php' title='Enter your FTP info'>saving your FTP info here</a>.";
									
								}
								else $message = '<strong>Error:</strong> Unify is unable to find this page: <em>'.$missing.'</em>';
								
							}
							
						}
					}
				
				}
				else $message = '<strong>Error:</strong> Unify is unable to find this page: <em>'.$missing.'</em>';
				
				output();
				
			}
			else output();
			
			if ($ftpconn) ftp_close($ftpconn);
		}
	}
	else {
		$message = '<strong>Error:</strong> Unify is unable to find your session.';
		output();
	}
	
?>
