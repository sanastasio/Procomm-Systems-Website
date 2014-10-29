<?php
	
	error_reporting (E_ALL & ~E_NOTICE);
	
	$ssp = ini_get("session.save_path");
	
	try {
		if($ssp != '') @ini_set("session.save_path",$ssp);
		else @ini_set("session.save_path",'/');
	}
	catch (Exception $e) {
		@ini_set("session.save_path",'/');
	}
	
	@ini_set("session.auto_start", 0);
	@ini_set("register_long_arrays", 0);
	@ini_set("session.cache_limiter", "private");
//	@ini_set("open_basedir", realpath(dirname(__FILE__).'/../../'));
	@ini_set('memory_limit', '256M');

	$sessname = '';

	if (!isset($_SESSION['un1fySess'])) {
	
		$sessname = "unifySess".uniqid('');
		$_SESSION['un1fySess'] = $sessname;
		
	}
	else $sessname = $_SESSION['un1fySess'];
	
	session_name($sessname);
	session_cache_expire(240); // 4 hours
	@session_start();
	
	$ver = 'Unify - 1.4.3';

?>