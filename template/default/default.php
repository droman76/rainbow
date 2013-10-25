<?php 
//session_start(); 

$t_home = $CONFIG->site . '/template/default';
$_SESSION['t_home'] = $t_home;
$t_site = $CONFIG->site;
$background = '';
if (isset($_SESSION['username'])){
$extinfo = $CONFIG->userdata .$_SESSION['username'].'/extinfo';

$avatar_image = '';
if (file_exists($extinfo)){
	$hr = fopen($extinfo,'r');
	$image_extension = fread($hr, filesize($extinfo));
	fclose($hr);
	$avatar_image = $CONFIG->site . '/myimages/' . 'medium_'.$_SESSION['username'].$image_extension;

}
else $avatar_image = $CONFIG->site . '/template/default/images/defaultmedium.gif';


}

if ($layout == 'twosidebar'){
	$backgroundlayout = "rainbow-layout-two-sidebar";
}
else if ($layout == 'rightsidebar'){
	$backgroundlayout = "rainbow-layout-one-sidebar";

}
else if ($layout == 'leftsidebar'){
	$backgroundlayout = "rainbow-layout-left-sidebar";

}
else if ($layout == 'profile') {
	$background = "background-color:#ececec";

}

?>

<!DOCTYPE html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>World Rainbow Family: All Site Activity</title>
	<link rel="SHORTCUT ICON" href="<?=$t_home?>/_graphics/favicon.ico">
	<link rel="stylesheet" href="/template/default/css.php" type="text/css">
	
<link rel="image_src" href="<?=$t_home?>/rainbowlogo.png">

<meta property="og:title" content="Rainbow Family Social Network"> 
<meta property="og:image" content="<?=$t_home?>/rainbowlogo.png"> 
<meta property="og:url" content="<?=$t_home?>"> 
<meta property="og:site_name" content="Rainbow Family Social Network"> 
<meta name="description" content="Welcome to the Rainbow Family Social Network! A network free of corporate control and greed, made by family for family, to connect and unite us in rising up like the phoenix from the fire! Welcome Home!">
<meta name="keywords" content="world rainbow family gatherings canada montana europe greece hippie love truth">

<script src="<?=$CONFIG->site?>/js/main.js"></script>
<script src="<?=$CONFIG->site?>/js/ajax.js"></script>

<?php include($handler->getHead()); ?>
	

</head>
<body style="<?=$background?>">

<?php include($handler->getHeader());?>



<div class='page-container' id='main-page' onclick='hideInbox()'>

<?php if ($layout == 'twosidebar' || $layout == 'rightsidebar') {?>


<div class="rainbow-page-body" >
	<div class="rainbow-inner">			
      <div class="rainbow-layout <?=$backgroundlayout?> clearfix rainbow-river-layout">
	      <div class="rainbow-sidebar">
		      
		      <?php include($handler->getRightSidebar());?>

      <ul class="rainbow-menu rainbow-menu-extras rainbow-menu-hz rainbow-menu-extras-default">
         <li class="rainbow-menu-item-bookmark">
            <a href="<?=$t_home?>/bookmarks/add/33?address=https%3A%2F%2Fwww.worldrainbowfamily.org%2Factivity" title="Bookmark this page" rel="nofollow">
               <span class="rainbow-icon rainbow-icon-push-pin-alt ">
               </span>
            </a>
         </li>
         <li class="rainbow-menu-item-rss">
            <a href="<?=$t_site?>/activity?view=rss" title="RSS feed for this page">
               <span class="rainbow-icon rainbow-icon-rss ">
               </span>
            </a>
         </li>
      </ul>	
   </div>
	
<?php }?>

<?php if ($layout == 'twosidebar' || $layout == 'leftsidebar') {?>


   <?php include($handler->getLeftSidebar()); ?>

</div>

<?php }?>



<div class="rainbow-main rainbow-body">

   <?php include($handler->getContent()); ?>
   
</div>
	
</div>





<?php include($handler->getFooter()); ?>
</body></html>