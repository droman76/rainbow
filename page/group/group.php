<?php


$action = $_REQUEST['action'];
$isgroup = true;

if ($action == 'new') {
   include('newgroup.php');
   return;
}
else if ($action == 'edit') {
   include('editgroup.php');
   return;

}
else if ($action == 'list') {

   include('listgroups.php');
   return;

}

$path = $_REQUEST['path'];
$page = $_REQUEST['page'];
$group = $path;

ilog("Group page: action $action -- path $path");

// Retrieve group information
$q = "select * from groups where id = '$group'";
$r = get_query($q);
if ($g = $r->fetch_object()) {

$name = $g->name;
$description = $g->description;
$image = $g->image;
$postaccess = $g->postaccess;
$creator = $g->creator;
$me = get_logged_in_user_id();



}
else {
   no_access();
}
?>



<br>
<h1><?=$name?></h1><br>

<?php include('group_input.php'); ?>



<div id='feed_throttle' ></div>
  <ul id="rainbow-river-main" class="rainbow-list hj-syncable rainbow-list-river rainbow-river" data-options="[]">
   
   <div id="feedcontainer_<?=$group?>">
      <?php if (!displayGroupFeed(0,15,$group)){
         include('no_items.php');
      }
      cronos_update($group);

      ?>
   </div>
   <div id="feedcontainer_country" style='display:none'>
   </div>
   <div id="feedcontainer_region" style='display:none'>
   </div>
   <div id="feedcontainer_city" style='display:none'>
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






