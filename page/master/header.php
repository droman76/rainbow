<?php $babelh = new BabelFish('header') ?>
<div class="rainbow-page rainbow-page-default">

<?php include ('topbar.php'); ?>


      

<!-- MAIN LOGO HEADER -->
<div class="rainbow-page-header">
   <div class="rainbow-inner">
      <a id='sitelinkback' href="<?=$CONFIG->site?>">
      <img src="<?=$t_home?>/images/rainbow_logo.png" alt="”World" rainbow="" family”="" title="”World">
      </a>
      <?php include($handler->getTopMenu()); ?>
      <?php if (is_user_logged_in()) { ?>
     
      <form class="rainbow-search rainbow-search-header" action="<?=$t_home?>/search" method="get">
         <fieldset>
            <input type="text" id='search-input' onclick="resetKeyCount()" onkeyup='searchData(event)' value="Search for people. events, or things" onblur="if (this.value==&#39;&#39;) { this.value=&#39;Search for people. events, or things&#39; };" onfocus="if (this.value==&#39;Search for people. events, or things&#39;) { this.value=&#39;&#39; };" class="rainbow-input-autocomplete search-input ui-autocomplete-input" size="21" name="search" > 
         </fieldset>
      </form>
      <? }
         else {?>
      
      <div style="width:500px;position:relative;top:-6px;float:right">

         <form id="loginform" method='POST' action="">
            <span class='loginfont'>  <?=$babel->say('p_email_or_username')?><br>
            <input type="hidden" name="action" value="login">
            <input style='width:140px' type="text" name='lemail' id="lemail" onfocus="emptyElement('status')">
            </span>
            <span class='loginfont'>  <?=$babel->say('p_password')?><br>
            <input type="password" style='width:140px'  name='lpassword' id="lpassword" onfocus="emptyElement('status')">
            </span>
            <span style='width:50px;display:inline-block;vertical-align:bottom'>
            <button class='rainbow-button-action' id="loginbtn" onclick="login()">  <?=$babel->say('p_login_button')?></button> 
            </span>
            <span class="loginfont" style='z-index:400;position:relative;left:-32px'>
               
               <div class='facebook-login' style='z-index:5000'><a href='<?=$dialog_url?>'>
               <span class='facebook-button' style='z-index:1;position:relative;width:143px;'><?=$babel->say('p_loginfacebook')?></span>
               </a>
               </div>
            </span>
            <span style='margin-left:4px' class="loginfont">
            <a href='javascript:forgot_password()'>  <?=$babel->say('p_forgot_password')?></a>
            </span>
            <span class='loginfont' style='margin-left:-10px;font-size:11px;margin-top:2px'>
            <input type='checkbox'>
            <?=$babel->say('p_rememberme')?>
            </span>
            
         </form>
      </div>
      <?php } ?>
   </div>
</div>
<div id='search-results' style='display:none;left:500px;top:115px;position:absolute;z-index:800000000' class="uiContextualDialogPositioner uiContextualDialogLeft">
   <div class="uiOverlay uiContextualDialog uiOverlayArrowRight" style="width: 450px; top: 0px; ">
      <div class="uiUpArrow" style="">
         
      </div>
      <div class="uiOverlayContent">
         <div id='search_content' style='padding:10px' class="uiOverlayContentHolder" onmouseover='onBoxMouseIn()'> 
            Hey this is the content of the result
         </div>
         <div id='search_loading' class="uiOverlayContentHolder" style='display:none'>
            <span style='margin-left:150px'><img src='<?=$CONFIG->siteroot?>template/default/_graphics/ajax_loader.gif'></span>
         </div>
      </div>
   </div>
</div>

