<?php

	function ftpconnect() 
	{		
		global $fftp, $ftpu, $ftpp, $ftps, $ftpwd, $ftpconn;
		
		if( $ftpu && $ftpp && $ftps && $ftpwd ) 
		{			
			if( $ftpwd != '/' )
			{
				$ftpwd = rtrim( $ftpwd, '/' );
			}
			
			//start ftp connection
			if( function_exists( 'ftp_connect' ) ) 
			{
				$ftpconn = ftp_connect( $ftps, 21, 10 ) /*or die( "Couldn't connect to $ftps" )*/;
				
				if( $ftpconn )
				{
					if( ftp_login( $ftpconn, $ftpu, $ftpp ) )
					{
						ftp_pasv( $ftpconn, true );
					}
					else
					{
						$ftpconn 	= false;
						$getmessage = "<strong>Error:</strong> Your username or password is incorrect.";
						$savescript = '<script type="text/javascript">window.top.window.userBar("'.$getmessage.'");</script>';
					
						echo $savescript;
					}
				}
				else
				{
					$ftpconn 	= false;
					$getmessage = "<strong>Error:</strong> Unify was unable to connect to the server. Please double check that the server name is entered correctly.";
					$savescript = '<script type="text/javascript">window.top.window.userBar("'.$getmessage.'");</script>';
				
					echo $savescript;
				}
			}
			else 
			{
				$ftpconn 	= false;
				$getmessage = "<strong>Error:</strong> FTP is not enabled for your install of PHP. <a href='http://www.php.net/manual/en/ftp.setup.php' title='Installing FTP on PHP'>More info here.</a>";
				$savescript = '<script type="text/javascript">window.top.window.userBar("'.$getmessage.'");</script>';
				
				echo $savescript;
			}
		}
	}

?>