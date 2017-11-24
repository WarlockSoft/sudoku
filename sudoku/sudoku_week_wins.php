<?php
	session_start();
	require_once "settings.php";	
	require_once UTILS_PATH . "utils.php";	
	require_once MODELS_PATH . "class_CSudoku.php";
	require_once MODELS_PATH . "class_CUsers.php";
	require_once MODELS_PATH . "class_CSudokuLeaders.php";


	$Connection = SetConnection();

 
	echo $week_start = date('Y-m-d 00:00:00', strtotime('-7 days'));
	echo $week_end = date('Y-m-d 00:00:00', strtotime('-1 days'));

	
	
	$Connection->close();