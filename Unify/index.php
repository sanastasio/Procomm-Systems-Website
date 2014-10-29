<?php 
	@session_start();
		
	if (isset($_SESSION['un1fySess'])) {
		
		session_name($_SESSION['un1fySess']);
		@session_start();
		
		if (isset($_SESSION['un1fyUserid'])) {
		
			$user = &$_SESSION['un1fyUserid'];
			$admin = &$_SESSION['un1fyIsadmin'];
			$sess = &$_SESSION['un1fySess'];
	
		}
		else header("Location:./login.php");
	}
	else header("Location:./login.php");
	
	$page = (isset($_SESSION['un1fyCurpage'])) ? $_SESSION['un1fyCurpage'] : "../";
	
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex" />
<link rel="shortcut icon" href="./unify-images/icon.png" />
<link rel="apple-touch-icon-precomposed" href="./unify-images/apple-touch-icon-precomposed.png" />
<title>Unify</title>
    
    <link href="css/unify-main.css" rel="stylesheet" type="text/css" />
    <link href="css/unify-ui.css" rel="stylesheet" type="text/css" />
    
</head>
<body>

	<div id="un1fyFrameShim"></div>

    <div id="un1fyTopBanner" class="un1fyTopBanner">
        <div class="un1fyHeader">
            <ul class="un1fyTopNav">
                <li id="un1fyUndo"><a title="Cannot Undo at this time" href="javascript:">Undo</a></li>
                <li id="un1fyRedo"><a title="Cannot Redo at this time" href="javascript:">Redo</a></li>
                <li id="un1fyCxl"><a title="Cannot Cancel All at this time" href="javascript:">Cancel All</a></li>
                <li id="un1fyRestore"><a title="Restore to the last published version" href="javascript:">Restore</a>&middot;</li>
                <li id="un1fyPublish"><a title="You must make edits before publishing" href="javascript:">Publish</a></li>
            </ul>
            
            <h1><a id="un1fyHome" href="javascript:" title="Go to your homepage">Unify</a></h1>
            
            <ul class="un1fyInfo">
                <li id="un1fyLogout"><a title="Logout of Unify" href="javascript:">logout</a>&middot;</li>
                <?php if ($admin == 'Y') : ?><li id="un1fyDashboard"><a title="Change your preferences, edit users, or enter your FTP info" href="javascript:">settings</a>&middot;</li><?php endif; ?>
                <li id="un1fyHelp"><a title="Visit our online help" href="http://unify.unitinteractive.com/help.php">help</a>&middot;</li>
                <li><a class="un1fyFBrowse" rel="#un1fyPages" title="Choose a page to edit" href="javascript:">pages</a></li>
            </ul>
        </div>
    </div>
        
    <iframe id="un1fyPageSrc" name="un1fyPageSrc" marginheight="0" align="top" frameborder="0" src="<?php echo $page; ?>" application="yes"></iframe>

    <script type="text/javascript" src="unify-js/lib/jquery.js"></script>
    <script type="text/javascript" src="unify-js/lib/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="unify-js/lib/jquery-ui.js"></script>
    <script type="text/javascript" src="unify-js/lib/jquery.unify.patch.js"></script>
    
    <script type="text/javascript" src="unify-js/tiny_mce/tiny_mce.js"></script>
    
    <script type="text/javascript" src="unify-js/codemirror/js/codemirror.js"></script>
    
    <script type="text/javascript" src="unify-js/unify-main.js"></script>
    <script type="text/javascript" src="unify-js/unify-ui.js"></script>

</body>
</html>