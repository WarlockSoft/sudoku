<?php 

	session_start();
	
	
	require_once MODELS_PATH . "class_CNews.php";	
	require_once MODELS_PATH . "class_CCatalogue.php";
	require_once MODELS_PATH . "class_CActions.php";			
	require_once MODELS_PATH . "class_CAnounce.php";	
	require_once USERS_PATH . "class_CUser.php";	
	require_once USERS_PATH . "class_CAuthentification.php";
	require_once MODELS_PATH . "class_CTexts.php";
	require_once MODELS_PATH . "class_COrder.php";
	require_once UTILS_PATH . "class_CError.php";	
	require_once MODELS_PATH . "class_CBanners.php";
	require_once TEMPLATES_PATH . "pagenums_class.php";
	require_once UTILS_PATH . "class_CControls.php";
	$Connection = SetConnection();	
	$Error = new CError();
	$AllBanners = CBanners::getBannersList($Connection);
	
	$title = "Бесплатные flash-игры на компьютер!";
	$newTitle2 = ($newTitle) ? $newTitle . " играть онлайн бесплатно" : $title; 
	if (!$_SESSION['id'])
		$_SESSION['id']= makeUnique();
	
	$user = makeCookie($login, $password);	

?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $newTitle2 ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="google-site-verification" content="NwSdodlDr_xYHGesb7j7IEkYmtY0Yke14Lj8i-Yh59s" />
    <meta name='yandex-verification' content='74ff165371628f73' />   
    <meta name='yandex-verification' content='7f5c70a2d07f40eb' />
	<meta name="verify-admitad" content="5ef07c4c44" />
    <meta name='wmail-verification' content='d897a4aec2f58a437f2e9ae3d33c5e4c' />
    <meta charset="utf-8" lang="ru">
    <meta property="og:title" content="<?= $newTitle2 ?>" />
    <meta property="og:description" content="<?= $News->name ?>" />
    <meta property="og:url" content="<?= substr(SITE_HOME_URL, 0, strlen(SITE_HOME_URL)-1) . $_SERVER['REQUEST_URI'] ?>" />
    <meta property="og:image" content="<?= substr(SITE_HOME_URL, 0, strlen(SITE_HOME_URL)-1) . $Photo["photo"] ?>" />
	
    

	

    <link rel="stylesheet" type="text/css" href="/css/style.css">
    
	<script async src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/banners.js"></script>
	


</head>
<body>










  <div class="container"> 
        <div class="main-content">



 
<header>
       <? include SITE_HOME_DIR . "/inc/inc_menu.php"; ?>		
</header>


   
      
   
     

 
   
     
  
<div class="content clearfix">	  
	 

		<div class="center_col_konkurs clearfix">