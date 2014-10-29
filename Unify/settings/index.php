<?php include_once('dashcheck.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Unify Settings :: Preferences</title>
<link href="../css/unify-main.css" rel="stylesheet" type="text/css" />
<link href="../css/unify-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../unify-js/lib/jquery.js"></script>
<script type="text/javascript" src="../unify-js/unify-main.js"></script>
<script type="text/javascript" src="../unify-js/unify-dash.js"></script>
</head>
<body class="un1fyPref">

<?php include 'dashnav.php'; ?>   
	
	     <form id="un1fyPrefsForm" class="un1fyPrefForm" action="../functions/getsaveprefs.php" method="post" target="un1fySavePrefsFrame">
            <div class="un1fyDashLeft">
                <div id="un1fyPathElement" class="un1fyDashElement">
                    <h3>Your Unify file path</h3>
                    <ul>
                        <li id="un1fyPath" class="un1fyPath"></li>
                    </ul>
                    <p>This is where your Unify files are located. This is also the path you&rsquo;ll follow to login to your site&rsquo;s Unify account to begin editing your site content. <em><strong>Note:</strong> Unify discovers this based on what you name your Unify folder.</em></p>
                </div>
                <div class="un1fyDashElement">
                    <h3>HTML Editing</h3>
                    <label class="un1fyPrefHtmlOn"><input id="htmltoggleon" name="htmltoggle" type="radio" value="Y" /> on</label> <label class="un1fyPrefHtmlOff"><input id="htmltoggleoff" name="htmltoggle" type="radio" value="N" /> off</label>
                    <p>Set this preference to &ldquo;on&rdquo; if you want users to be able to directly edit the HTML markup, or &ldquo;off&rdquo; if you do not want users to be able to edit the HTML.</p>
                </div>
                <div class="un1fyDashElement">
                    <h3>Exclude Folders</h3>
                    <p>Enter a comma separated list of folder names or paths you do not want listed in the Unify file browser. Do not use a &ldquo;/&rdquo; at the end fo the path. <em>Ex: &ldquo;wordpress, .svn, functions/important&rdquo;</em></p>
                    <ul>
                    	<li><label>Folders </label><input id="un1fyExFolders" class="un1fyUnlocked" name="exFolders" type="text" value="" /></li>
                    </ul>
                </div>
				<div class="un1fyDashElement">
                    <h3>File Uploads</h3>
                    <label class="un1fyPrefHtmlOn"><input id="filetoggleon" name="filetoggle" type="radio" value="Y" /> on</label> <label class="un1fyPrefHtmlOff"><input id="filetoggleoff" name="filetoggle" type="radio" value="N" /> off</label>
                    <p>Set this preference to &ldquo;on&rdquo; if you want users to be able to upload files, or &ldquo;off&rdquo; if you do not want users to upload files. There is a 100MB limit on all uploads.</p>
                    <ul>
                    	<li class="un1fyFilePath">
                    		<p>To specify a file folder, enter the relative path here. Leave it blank if you want Unify to auto-discover folders.</p>
                        	<label>Default File Path </label><input id="un1fyFilePath" class="un1fyUnlocked" name="filePath" type="text" value="" />
                        </li>
                    </ul>
                </div>
                <div class="un1fyDashElement">
                    <h3>Image Uploads</h3>
                    
                     <p>To specify an image folder, enter the relative path here. Leave it blank if you want Unify to auto-discover folders.</p>
                    
                    <ul>
                        <li><label>Default Image Path </label><input id="un1fyImagePath" class="un1fyUnlocked" name="imagePath" type="text" value="" /></li>
                    </ul>
                </div>
                <h2 class="un1fyPrefSave"><a id="un1fyPrefBtn" href="javascript:">save</a></h2>
            </div>
         </form>  
         
<?php include 'dashfoot.php'; ?>