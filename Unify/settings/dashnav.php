<div id="un1fyTopBanner" class="un1fyTopBanner">
	<div class="un1fyHeader">
		<ul class="un1fyTopNav">
			<li id="un1fyGoUpdate"><a class="un1fyFBrowse" rel="#un1fyPages" href="javascript:" title="Choose a page to edit">Back to Editing</a></li>
		</ul>
		<h1><a id="un1fyHome" href="../" title="Go to your hompage">Unify</a></h1>
		<ul class="un1fyInfo">
			<li id="logout"><a href="javascript:" title="logout">logout</a>&middot;</li>
			<li><a href="http://unify.unitinteractive.com/help.php" title="help info">help</a>&middot;</li>
			<li><a href="http://unify.unitinteractive.com" title="Unify">visit the Unify website</a></li>
		</ul>
	</div>
</div><!--end of un1fyTopBanner-->
<div id="un1fyDashScrollWrap">
    <div class="un1fyTopBuffer">
        <div class="un1fyTabNav clearfix">
            <ul>
                <li id="un1fyTabPreferences"><a href="./" title="Preferences">Preferences</a></li>
                <li id="un1fyTabMyAccount"><a href="acct.php" title="User Info">User Info</a></li>
                <li id="un1fyTabFTPInfo"><a href="ftpinfo.php" title="FTP Info">FTP Info</a></li>
            </ul>
        </div><!--end of un1fyTabNav-->
    </div><!--end of un1fyTopBuffer-->
    <div class="un1fyDashboardWrap clearfix">
        <div class="un1fyDashContent clearfix">
        
            <div id="un1fyUpdates">
                    <?php 
                    		$ver = 'Unify - 1.4.3';
                            $ver = explode('- ', $ver);
                            $ver = $ver[1];
                            
                            $late = $_SESSION['un1fyLatest'];
                            $late = preg_replace('/unify/', '', $late);
                            
                            if($late != $ver) {
                                echo '<h3>Updates: <span><em>A Newer Version is Available</em></span></h3>';
                                echo '<p><a id="un1fyAutoUpdate" class="autoUpdate" href="javascript:" title="Start auto-update">update</a> or Download: <a href="http://unify.unitinteractive.com/login.php" class="second">'.$_SESSION['un1fyLatest'].'</a>';
                            }
                            else {
                                echo '<h3>Updates: <span>You Are Running the Latest Version</span></h3>';
                                echo '<p>Download: <a href="http://unify.unitinteractive.com/login.php">'.$_SESSION['un1fyLatest'].'</a> | <a href="http://unify.unitinteractive.com/changelog.php" title="Find out what&rsquo;s new in this version">changelog</a>';
                            }
                    ?> | <a class="updateLink" href="http://unify.unitinteractive.com/faq.php#update">How do I update?</a></p>
            </div>