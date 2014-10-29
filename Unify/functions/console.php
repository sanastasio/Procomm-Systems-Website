<?php

	function console( $log ) {
					
		$log = var_export( $log, true );
		$log = preg_replace('/\s+/', ' ', $log);
		$log = addslashes($log);
		
		$script = '<script language="javascript" type="text/javascript">console.log("'.$log.'");</script>';
		
		echo $script;
		
	}

	//console('console loaded');
	
?>