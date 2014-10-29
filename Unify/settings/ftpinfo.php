<?php include_once('dashcheck.php'); 
	  include_once('../functions/ftpvars.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Unify Settings :: FTP Info</title>
<link href="../css/unify-main.css" rel="stylesheet" type="text/css" />
<link href="../css/unify-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../unify-js/lib/jquery.js"></script>
<script type="text/javascript" src="../unify-js/unify-main.js"></script>
<script type="text/javascript" src="../unify-js/unify-dash.js"></script>
</head>
<body class="un1fyFTP">
	
<?php include 'dashnav.php'; ?>
            
            <form id="un1fyFTPForm" class="un1fyAcctForm un1fyAcctDisplay un1fyAcctEditing" action="../functions/ftpcreate.php" method="post" target="un1fySaveFTPFrame" >
	            <div class="un1fyDashLeft">
	            	
	            	<p class="FTPtext"><strong>Please Note:</strong> FTP information is not required for most installations of Unify. If you are encountering file permission issues, save your FTP info here and try publishing/uploading again. Unify will write your FTP information to an unreadable file on this server.</p>
	            	
	                <div class="un1fyDashElement">
	                    
	                        <h3>Your FTP Info</h3>
	                        <ul>
	                            <li class="un1fyFTPServerLi"><label>Server</label><input class="un1fyFTPServer un1fyUnlocked" name="ftps" type="text" value="<?php if($ftps) echo $ftps; ?>" /><span>Please enter your server &ndash; no &ldquo;ftp.&rdquo;</span></li>
	                            <li class="un1fyFTPUserLi"><label>FTP Username</label><input class="un1fyUnlocked" name="ftpu" type="text" value="<?php if($ftpu) echo $ftpu; ?>" /><span>Please your FTP username</span></li>
	                            <li class="un1fyFTPPassLi"><label>FTP Password</label><input class="un1fyUnlocked" name="ftpp" type="password" value="" /><span>Please enter your FTP password</span></li>
	                            <li class="un1fyFTPPathLi"><label>Domain Root<a href="http://unify.desk.com/customer/portal/articles/410397-setting-ftp-info" title="More Info">?</a></label><input class="un1fyUnlocked" name="ftpwd" type="text" value="<?php if($ftpwd) echo $ftpwd; ?>" /><span>Please enter the path to your site</span></li>
	                        </ul>
	                 </div>
	                <h2 class="un1fyPrefSave"><a id="un1fyFTPBtn" href="javascript:">save</a><a id="un1fyFTPClearBtn" href="javascript:">Clear all FTP info</a></h2>
	            </div>
            </form>
            
<?php include 'dashfoot.php'; ?>
      