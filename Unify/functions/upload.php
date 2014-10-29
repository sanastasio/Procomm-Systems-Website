<?php
	
	//error_reporting (E_ALL & ~E_NOTICE);
	@session_start();
	
	if( isset( $_SESSION['un1fySess'] ) && strstr( $_SESSION['un1fySess'], 'unifySess' ) ) // we have an active session
	{
		include_once('urldissect.php');
		include_once('ftpstart.php');
		include_once('createsession.php');
		
		if( !empty( $_POST ) && isset( $_SESSION['un1fyUserid'] ) ) // the user is logged in and has posted something
		{
			if( $_GET['type'] == 'img' ) // the is uploading an image
			{
				$file = $_FILES['un1fyImgUpload']; // the file to upload
				
				if( @file_exists( $file['tmp_name'] ) ) // the temporary file to transfer has been created
				{
					$uploaddir = ($_POST['un1fyImgFolder']) ? '../..'.$_POST['un1fyImgFolder'] : '../../';
					$uploaddir = ($_SESSION['un1fyImagePath']) ? '../../'.$_SESSION['un1fyImagePath'] : $uploaddir;
					$uploadfile = $uploaddir.basename($file['name']);
					
					if( !@file_exists( $uploadfile ) || $_GET['over'] ) // an image with this name already exists in the directory
					{
						$fileName 	= $file['name'];
						$fileSize 	= $file['size'];
						$alert 		= "<strong>Error:</strong> There was an error uploading your image. Please try again.";
						
						list( $fileWidth, $fileHeight ) = getimagesize( $file['tmp_name'] );  
						
						if ( preg_match( '/(.jpg|.png|.gif)$/i', $fileName ) && $fileSize < 5242880 ) // the file is an appropriate image type and under 5mb
						{
							if( move_uploaded_file($file['tmp_name'], $uploadfile ) ) 
							{
								$alert = "Your file was successfully uploaded.";
							}
							elseif( $ftpconn ) 
							{
								$path 		= urldissect( $_SERVER['SCRIPT_NAME'], false, 3 );
								$uploadfile = str_ireplace( '../../', '', $uploadfile );
								$remotefile = $ftpwd . $path . $uploadfile;
								$tempfile 	= fopen( $file['tmp_name'], 'r' );
								
								if( ftp_fput( $ftpconn, $remotefile, $tempfile, FTP_BINARY ) ) 
								{
									$alert = "Your file was successfully uploaded.";
								}
							}
						}
						else 
						{
							$alert = "<strong>Error:</strong> You may only upload <strong>.jpg</strong>, <strong>.gif</strong>, or <strong>.png</strong> image files, under <strong>5.0 MB</strong> in size.";
							$fileName = "";
						}
						
						$script = '<script language="javascript" type="text/javascript">window.top.window.imageInfo("'.$fileSize.'", "'.$fileWidth.'", "'.$fileHeight.'", "'.$alert.'", "'.$fileName.'", "'.str_replace('../../', '', $uploaddir).'" );</script>';
						echo $script;
					}
					else 
					{
						$script = '<script language="javascript" type="text/javascript">window.top.window.imageOverwritePrompt();</script>';
						echo $script;
					}
				}
				else // could not access the temporary file
				{
					$alert = "<strong>Error:</strong> Unify was unable to access the file you were trying to upload. This is usually a problem with access to the server's temp directory.";
					$fileName = "";
					$script = '<script language="javascript" type="text/javascript">window.top.window.imageInfo("'.$fileSize.'", "'.$fileWidth.'", "'.$fileHeight.'", "'.$alert.'", "'.$fileName.'", "'.str_replace('../../', '', $uploaddir).'" );</script>';
					
					echo $script;
				}
			}
			else // the user is uploading something other than an image
			{	
				$file = $_FILES['un1fyUpload'];
				
				if( @file_exists( $file['tmp_name'] ) ) // the temporary file to transfer has been created
				{
					$uploaddir = ($_POST['un1fyFolder']) ? '../..'.$_POST['un1fyFolder'] : '../../';
					$uploaddir = ($_SESSION['un1fyFilePath']) ? '../../'.$_SESSION['un1fyFilePath'] : $uploaddir;
					$uploadfile = $uploaddir.basename($file['name']);
										
					if (!@file_exists($uploadfile) || $_GET['over']) {
					
						$fileName = $file['name'];
						$fileSize = $file['size'];
						$alert = "<strong>Error:</strong> There was an error uploading your image. Please try again.";
						
						if ( !preg_match('/(\.html|\.php|\.js|\.css|\.htm|\.shtml|\.asp|\.aspx|\.cfm|\.phtml)$/i', $fileName ) && $fileSize < 100000000 )
						{
							if( move_uploaded_file( $file['tmp_name'], $uploadfile ) ) 
							{
								$alert = "Your file was successfully uploaded.";
							}
							elseif( $ftpconn ) 
							{
								$path 		= urldissect( $_SERVER['SCRIPT_NAME'], false, 3 );
								$uploadfile = str_ireplace( '../../', '', $uploadfile );
								$remotefile = $ftpwd.$path.$uploadfile;
								$tempfile 	= fopen( $file['tmp_name'], 'r' );
								
								if( ftp_fput( $ftpconn, $remotefile, $tempfile, FTP_BINARY ) ) 
								{
									$alert = "Your file was successfully uploaded.";
								}
								else 
								{
									$alert = "<strong>Error:</strong> You do not have permission to upload files to this folder.";
									$script = '<script language="javascript" type="text/javascript">window.top.window.fileInfo("", "'.$alert.'", "", "" );</script>';
									
									echo $script;								
								}				
							}
							else 
							{
								$alert = "<strong>Error:</strong> You do not have permission to upload files to this folder.";
								$fileName = "";
							}
						}
						else 
						{
							$alert = "<strong>Error:</strong> You may not upload <strong>.html</strong>, <strong>.htm</strong>, <strong>.php</strong>, <strong>.js</strong>, or <strong>.css</strong> files, under <strong>100 MB</strong> in size.";
							$fileName = "";
						}
						
						$script = '<script language="javascript" type="text/javascript">window.top.window.fileInfo("'.$fileSize.'", "'.$alert.'", "'.$fileName.'", "'.str_replace('../../', '', $uploaddir).'" );</script>';
						
						echo $script;
					}
					else 
					{
						$script = '<script language="javascript" type="text/javascript">window.top.window.filePrompt();</script>';
						
						echo $script;
					}
				}
				else // could not access the temporary file
				{
					$alert = "<strong>Error:</strong> Unify was unable to access the file you were trying to upload. This is usually a problem with access to the server's temp directory.";
					$fileName = "";
					$script = '<script language="javascript" type="text/javascript">window.top.window.imageInfo("'.$fileSize.'", "'.$fileWidth.'", "'.$fileHeight.'", "'.$alert.'", "'.$fileName.'", "'.str_replace('../../', '', $uploaddir).'" );</script>';
					
					echo $script;
				}	
			}
			
			if( $ftpconn ) ftp_close( $ftpconn ); // close an open ftp connection
		}
	}

?>