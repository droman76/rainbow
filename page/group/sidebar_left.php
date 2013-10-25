<?php
$path = $_REQUEST['path'];
$group = $path;
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


$group_image = $CONFIG->siteroot . "groupimages/$group/medium.".$image;
if ($group != null && $group != '') { ?>

<div class="rainbow-sidebar-alt">
		<center><img src="<?=$group_image?>" width="156px" alt="Web Focalizer" title="Web Focalizer" --></center>
      <!-- div id="welcome"> Welcome to the <br> <?=$babel->say($group)?>!
      </div -->
      <?php if (is_group_admin($group)) { ?>
      <?=$babel->say('p_youareadmin')?>
      <?php } else if (is_group_member($group)) {?>
      <?=$babel->say('p_youaremember')?>
      
      <?php } ?>
      <hr>
         <?php include($handler->getSideMenu()); ?>

      <hr>
<!--
      <div class="rainbow-module  rainbow-module-featured"><div class="rainbow-head"><h3>Latest News</h3>
      </div>
      <div class="rainbow-body">
         <div class="rainbow-image-block clearfix">
	         <div class="rainbow-image">
            
            </div>
            <div class="rainbow-body"><h3><a href="<?=$t_home?>/groups/profile/2176/rainbow-communities">New Moon Gathering</a></h3>
               <div class="rainbow-subtext">Bike Caravan is Gathering in Victoria, Vancouver Island on the neww Moon of September.
               </div>
            </div>
      </div>
      <div class="rainbow-image-block clearfix">
	  
	      
      </div>

      <p style="text-align:right; margin:3px 3px;">
         <a href="<?=$t_home?>/groups/member/daniel"><b>View More</b></a></p>
   
   </div>

</div>
-->

<?php }  else {?>

<div class="rainbow-sidebar-alt">
      <center><!-- img src="<?=$group_image?>" width="156px" alt="Web Focalizer" title="Web Focalizer" --></center>
      <div id="welcome"> Welcome!
      </div>
      
      <hr>
         <?php include($handler->getSideMenu()); ?>

      <hr>
<!--
      <div class="rainbow-module  rainbow-module-featured"><div class="rainbow-head"><h3>Latest News</h3>
      </div>
      <div class="rainbow-body">
         <div class="rainbow-image-block clearfix">
            <div class="rainbow-image">
            
            </div>
            <div class="rainbow-body"><h3><a href="<?=$t_home?>/groups/profile/2176/rainbow-communities">New Moon Gathering</a></h3>
               <div class="rainbow-subtext">Bike Caravan is Gathering in Victoria, Vancouver Island on the neww Moon of September.
               </div>
            </div>
      </div>
      <div class="rainbow-image-block clearfix">
     
         
      </div>

      <p style="text-align:right; margin:3px 3px;">
         <a href="<?=$t_home?>/groups/member/daniel"><b>View More</b></a></p>
   </div>
   -->
</div>


<?php } ?>
<br>
 <span style="font-size:11px;weight:bold"><b><?=$babel->say('p_groupsibelong')?></b></span>
      <ul class="rainbow-menu rainbow-menu-page rainbow-menu-page-default">

      <?php 
      // retrieve user groups
      $me = get_logged_in_user_id();
      $q = "select * from group_members,groups where groupid = id and userid = $me";

      $r = get_query($q);

      while ($g = $r->fetch_object()) {
         $groupname = $g->name;
         $groupid = $g->id;
         $unread = get_group_unread($groupid);
         if ($unread > 0)
            $punread = "<span style='color:red'>($unread)</span>";
         else $punread = '';
         if ($groupid == $group) continue;


      ?>   

         <li class="rainbow-menu"><a href="/group/<?=$groupid?>"> <?=$babel->say($groupname)?> <?=$punread?> </a></li>
      
      <?php } ?>

      </ul>
