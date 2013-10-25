
<?php if (is_new_user()) {?>
<div id='intro-newuser' class='modal-bg' style='display:inline'>

   <div style='left:20%;top:30px;width:600px;height:550px' class="modal">
      <div style='margin:20px'>
      <span style='float:right' ><a onclick='_("intro-newuser").style.display="none"'><img  src='/template/default/_graphics/close.png'></a></span>
         
         <h2><?=$babel->say('p_welcomenew')?></h2><hr>
         <?=$babel->say('p_welcomemsg')?>
         <br><br>
         <h3><?=$babel->say('p_feature1title')?></h3>
         <?=$babel->say('p_feature1desc')?><br><br>
         <h3><?=$babel->say('p_feature2title')?></h3>
         <?=$babel->say('p_feature2desc')?><br><br>
         <h3><?=$babel->say('p_feature3title')?></h3>
         <?=$babel->say('p_feature3desc')?><br><br>
         <h3><?=$babel->say('p_feature4title')?></h3>
         <?=$babel->say('p_feature4desc')?><br><br>
         <?=$babel->say('p_feature5desc')?> &nbsp;<a onclick='_("intro-newuser").style.display="none"'><?=$babel->say('p_startsite')?></a>

         

      </div>
   </div>

</div>
<?php }
$regionclass = '';
$countryclass = '';
$item = '';

//check if there is a feed link set
if (isset($_REQUEST['path'])) $item = $_REQUEST['path'];

// OK, only show tabs and feed input if there is no post being reference directly 
if ($item == '') {
?>

<ul id='feed_view_menu' class="rainbow-menu rainbow-menu-filter rainbow-menu-hz rainbow-menu-filter-default">
      <?php if ($_SESSION['city'] != '' && $_SESSION['city'] != 'na') { 
         $start = 'city';

       ?>
      <li class="rainbow-state-selected" id='city'><a onclick="switchView('city','15')"><?=$babel->say('city')?> <span id='counter_city'></span></a></li>
      <?php } else if (($_SESSION['region'] != '' && $_SESSION['region'] != 'na') && ($_SESSION['country_code'] == 'US' || $_SESSION['country_code'] == 'CA')) { 
         if ($_SESSION['city'] == ''){
            $start = 'region';
            $regionclass = 'class="rainbow-state-selected"';
         }
         ?>
      <li id='region' <?=$regionclass?>><a onclick="switchView('region','13')"><?=$babel->say('region')?> <span id='counter_region'></span></a></li>
      <?php } else {
         $start = 'country';
         $countryclass = 'class="rainbow-state-selected"';
         } ?>
      <?php if ($_SESSION['city'] != '' && ($_SESSION['region'] != '' && $_SESSION['region'] != 'na') && ($_SESSION['country_code'] == 'US' || $_SESSION['country_code'] == 'CA')) { ?>
            <li id='region' <?=$regionclass?>><a onclick="switchView('region','13')"><?=$babel->say('region')?> <span id='counter_region'></span></a></li>
      <? } ?>
         
      <?php if ($_SESSION['country_code'] != '' && $_SESSION['country_code'] != 'na') { ?>
     
      <li id='country' <?=$countryclass?>><a onclick="switchView('country','10')"><?=$babel->say('country')?> <span id='counter_country'></span></a></li>
      <? } else {
         $start = 'world';
          $view = 'world';  
          $worldclass = 'class="rainbow-state-selected"';
        
         }?>

      <?php if ($_SESSION['continent_code'] != '' && $_SESSION['continent_code'] != 'na') { ?>
     
      <li id='continent'><a onclick="switchView('continent','6')"><?=$babel->say('continent')?> <span id='counter_continent'></span></a></li>
      <? } ?> 
      
      <li id='world' <?=$worldclass?>><a onclick="switchView('world','1')"><?=$babel->say('world')?> <span id='counter_world'></span></a></li>
      
</ul>



<?php include('feed_input.php'); ?>




<div id='feed_throttle' ></div>
  <ul id="rainbow-river-main" class="rainbow-list hj-syncable rainbow-list-river rainbow-river" data-options="[]">
     
   <div id="feedcontainer_<?=$start?>">
      <?php if (!displayFeed(0,15,$start)){
         include('no_items.php');
      }
      cronos_update($start);
      ?>
   </div>
   <?php if (isset($_SESSION['city']) && $_SESSION['city'] != '') {?>
   <div id="feedcontainer_country" style='display:none'>
   </div>
   <?php } ?>
   <?php if (isset($_SESSION['city']) && $_SESSION['city'] != '') {?>
   <div id="feedcontainer_region" style='display:none'>
   </div>
   <?php } ?>
   <div id="feedcontainer_continent" style='display:none'>
   </div>
   <div id="feedcontainer_world" style='display:none'>
   </div>
   <div id="feedcontainer_a" style='display:none'>
   </div>
   <div id="feedcontainer_b" style='display:none'>
   </div>
   <div id="feedcontainer_c" style='display:none'>
   </div>
   <div id="feedcontainer_e" style='display:none'>
   </div>
   <div id="feedcontainer_f" style='display:none'>
   </div>
   <div id="feedcontainer_g" style='display:none'>
   </div>
   <div id="feedcontainer_l" style='display:none'>
   </div>
   <div id="feedcontainer_m" style='display:none'>
   </div>
   <div id="feedcontainer_n" style='display:none'>
   </div>
   <div id="feedcontainer_o" style='display:none'>
   </div>
   <div id="feedcontainer_p" style='display:none'>
   </div>
   <div id="feedcontainer_r" style='display:none'>
   </div>
   <div id="feedcontainer_s" style='display:none'>
   </div>
   <div id='ajax_loader' style='display:none;margin:auto;padding-top:20px;width:100px'><img src="<?=$CONFIG->site?>/template/default/_graphics/ajax_loader.gif"></div> 
   
</ul>

<?php } else { 

   $view = getPostView($item);
   ?>

<ul id="rainbow-river-main" class="rainbow-list hj-syncable rainbow-list-river rainbow-river" data-options="[]">

<div id="feedcontainer_<?=$view?>">
   <?php 
   // There is a specific post being requested... load that ONLY
   displayPost($item,$view);

   ?>
</div>
   <div id='ajax_loader' style='display:none;margin:auto;padding-top:20px;width:100px'><img src="<?=$CONFIG->site?>/template/default/_graphics/ajax_loader.gif"></div> 
 
</ul>




<?php } ?>




<div id='avatar_helper' onmouseover='onBoxMouseIn(event)' onmouseout='onBoxMouseOut(event,"avatar_helper")' class="uiContextualDialogPositioner uiContextualDialogLeft" >
   <div class="uiOverlay uiContextualDialog uiOverlayArrowRight" style="width: 347px; top: 0px; ">
      <div class="uiUpArrow" style="top: 1px; margin-top: 0px;"></div>
      <div class="uiOverlayContent">
         <div id='avatar_helper_content' class="uiOverlayContentHolder" onmouseover='onBoxMouseIn()'>
         </div>
         <div id='avatar_helper_loading' class="uiOverlayContentHolder" style='display:none'>
            <span style='margin-left:150px'><img src='<?=$CONFIG->siteroot?>template/default/_graphics/ajax_loader.gif'></span>
         </div>
      </div>
     </div>
</div>




