<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if (isset($_SESSION['un1fySess']) && strstr($_SESSION['un1fySess'], 'unifySess') && $_GET['type'] != '') {
	
		include_once('createsession.php');
		
		if (isset($_SESSION['un1fyUserid'])) {
			
			$type = $_GET['type'];
	
			$pageurl = $_SERVER['HTTP_REFERER']; //incoming page url
			
			if (stristr($pageurl, $_SERVER['HTTP_HOST'])) {
				
				$allfiles = 'psd,pdf,swf,sit,tar,tgz,zip,gzip,bmp,gif,jpeg,jpg,jpe,png,txt,doc,docx,xl,xls,flv,mov,qt,mpg,mpeg,mp3,aiff,aif,aac,wav,ppt';
				$imgfiles = 'png,jpg,gif,jpeg,tiff,bmp';
				
				$pathpatt = ($type == 'img') ? '/image|img/i' : '/upload|files/i';
				$pathdefault = ($type == 'img') ? $_SESSION['un1fyImagePath'] : $_SESSION['un1fyFilePath'];
				
				$files = ($type == 'img') ? $imgfiles : $allfiles;
				$files = explode(',', $files);
				
				$pattern = '/(';
				
				foreach ($files as $file) {
					
					$pattern .= '\.'.$file.'|';
					
				}
				
				$pattern = trim($pattern, '|').')/i';
				
				$matches = Array();
				
				function findfiles($path, $pattern, $pathpatt) {
				
					global $matches;
				
					$path = rtrim(str_replace("\\", "/", $path), '/') . '/';
					
					$entries = Array();
					$dir = dir($path);
					
					while (false !== ($entry = $dir->read())) {
					
						if (!preg_match('/(\/unify\-js\/|\/unify\-images\/)/', $path)) $entries[] = $entry;
						
					}
					
					$dir->close();
					
					foreach ($entries as $entry) {
					
						$fullname = $path . $entry;
						$pathname = str_replace('../..', '', $path);
						
						$exFolders = preg_replace('/,\s*/', '|', $_SESSION['un1fyExFolders']);
						$exFolders = str_replace('-', '\-', $exFolders);
						$exFolders = str_replace('.', '\.', $exFolders);
						$exFolders = '/'.str_replace('/', '\/', $exFolders).'/';
						
						if ($entry != '.' && $entry != '..' && is_dir($fullname)) {
							
							if (preg_match($pathpatt, $path) && !in_array($pathname, $matches) && is_writable($path)) $matches[] = $pathname;
							if (!preg_match($exFolders, $fullname) || $_SESSION['un1fyExFolders'] == '') findfiles($fullname, $pattern, $pathpatt);
							
						}
						elseif (($entry == '.' || $entry == '..') && is_dir($fullname)) {
						
							if (preg_match($pathpatt, $path) && !in_array($pathname, $matches) && is_writable($path)) $matches[] = $pathname;
							
						}
						elseif (is_file($fullname) && preg_match($pattern, $entry)) {
							
							if (!in_array($pathname, $matches) && is_writable($path)) $matches[] = $pathname;
						  
						}
					
					}
				  
				}
				
				findfiles("../../$pathdefault", $pattern, $pathpatt);
				
				if (sizeof($matches) > 0 ) {
				
					foreach ($matches as $match) {
						
						$script = '<script language="javascript" type="text/javascript">window.top.window.folderOption("'. $match .'", "'. $type .'");</script>';
						echo $script;
					
					}
				
				}
				else {
					$script = '<script language="javascript" type="text/javascript">window.top.window.folderOption("/", "'. $type .'");</script>';
					echo $script;
				}
				
				$endscript = '<script language="javascript" type="text/javascript">window.top.window.destroyFolderLoader();</script>';
				echo $endscript;
				
			}
		
		}
	
	}

?>