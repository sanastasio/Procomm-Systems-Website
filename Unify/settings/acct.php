<?php include_once('dashcheck.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Unify Settings :: User Info</title>
<link href="../css/unify-main.css" rel="stylesheet" type="text/css" />
<link href="../css/unify-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../unify-js/lib/jquery.js"></script>
<script type="text/javascript" src="../unify-js/unify-main.js"></script>
<script type="text/javascript" src="../unify-js/unify-dash.js"></script>
</head>
<body class="un1fyAcct">

<?php include 'dashnav.php'; ?>   
	     
            <div class="un1fyDashLeft">
                <div class="un1fyDashElement">
                    <form id="un1fyAcctFormSuperAdmin" class="un1fyAcctForm un1fyAcctDisplay" action="../functions/getsaveusers.php" method="post" target="un1fySaveUserFrame" >
                        <h3></h3>
                        <ul>
                            <li class="un1fyAcctUserLi"><label>Username </label><input class="un1fyLocked un1fyAcctUser" name="un1fyAcctUser" type="text" value="" readonly="true" /><input class="un1fyLocked un1fyAcctUser-orig" name="un1fyAcctUser-orig" type="hidden" value="" readonly="true" /></li>
                            <li class="un1fyAcctPassLi"><label>Password </label><input class="un1fyLocked un1fyAcctPass" name="un1fyAcctPass" type="text" value="TOTALLY SECURE, REALLY" readonly="true" /></li>
                        </ul>
                        <ul class="un1fyAcctMeta">
                            <!--<li><label>First &amp; Last Name </label><input class="un1fyUnlocked" name="un1fyAcctName" type="text" value="" readonly="true" /> <span class="un1fyInstructions">For identification only</span></li>-->
                            <li class="un1fyAcctEmailLi"><label>Email </label><input class="un1fyLocked un1fyAcctEmail" name="un1fyAcctEmail" type="text" value="" readonly="true" /><input class="un1fyLocked un1fyAcctEmail-orig" name="un1fyAcctEmail-orig" type="hidden" value="" readonly="true" /></li>
                        </ul>
                    </form>
                </div>
                <h4><a id="un1fyNewUserLink" href="javascript:" title="give someone else the ability to edit content on your site">Add New User</a> <span>Only the account owner has administrative privileges</span></h4>
                <div id="un1fyNewUser" class="un1fyNewUserElement">
                    <form id="un1fyAcctFormNewUser" class="un1fyAcctForm un1fyAcctDisplay" action="../functions/getsaveusers.php" method="post" target="un1fySaveUserFrame">
                        <h3>New User</h3>
                        <ul>
                            <li><label>Name </label><input id="un1fyNewName" class="un1fyUnlocked un1fyAcctName" name="un1fyNewAcctName" type="text" /><span>Please enter a name</span></li>
                            <li><label>Email </label><input class="un1fyUnlocked un1fyAcctEmail" name="un1fyNewAcctEmail" type="text" value="" /><span>Please enter a valid email</span></li>
                            <li><label>Username </label><input class="un1fyUnlocked un1fyAcctUser" name="un1fyNewAcctUser" type="text" value="" /><span>Please enter a username</span></li>
                            <li><label>Password </label><input id="un1fyNewAcctPass" class="un1fyUnlocked un1fyAcctPass" name="un1fyNewAcctPass" type="password" value="" /><span>&ldquo;Medium&rdquo; or &ldquo;strong&rdquo; required.</span></li>
                            <li id="pwStrengthNew" class="un1fyAcctPassInfo">n/a <span class="advice">&ldquo;Medium&rdquo; or &ldquo;strong&rdquo; required.</span></li>
                            <li><label>Confirm Password </label><input class="un1fyUnlocked un1fyAcctPassCon" name="un1fyNewAcctPassConfirm" type="password" value="" /><span>Must match password</span></li>
                            <li class="un1fyFormSave"><a id="un1fyNewUserSave" class="userSave" href="javascript:" title="save">save</a> <span class="un1fyCancel">or <a id="un1fyNewUserCancel" href="javascript:">cancel</a></span></li>
                        </ul>
                    </form>
                </div>
            </div>
            
<?php include 'dashfoot.php' ; ?>