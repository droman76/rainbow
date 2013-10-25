<?php
$username = get_logged_in_user_name();
$avatar_image = get_avatar_image($username);
if (isset($_SESSION['avatarchanged']))$nocache = '?nocache=true';
else $nocache = '';

?>
<div class="rainbow-sidebar-alt">
		<center><a href='/profile/<?=$username?>'>
      <img src="<?=$avatar_image?><?=$nocache?>" width="156px" alt="<?=$_SESSION['name']?>" title="<?=$_SESSION['name']?>"></a></center>
      <div id="welcome"> <?=$babel->say('p_welcome')?><br> <?=$_SESSION['name']?>!
      </div>
      
      <hr>
         <?php include($handler->getSideMenu()); ?>

      <hr>

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


   ?>   

      <li class="rainbow-menu"><a href="/group/<?=$groupid?>"> <?=$babel->say($groupname)?> <?=$punread?></a></li>
   
   <?php } ?>

   </ul>

           <span style='position:relative;top:-10px;margin-left:10px;margin-bottom:10px;font-size:12px'><a href='/group?action=new'>+ Create a New Group</a><br></span>

 <?php
 $me = get_logged_in_user_id();

 $q = "select count(user2) as myfriends from friends where user1 = $me and accepted = 1";
 $r = get_query($q);
 $c = $r->fetch_object()->myfriends;


 ?>

    <div class="rainbow-module  rainbow-module-popup" >
         <div class="rainbow-head"><h3><?=$babel->say('p_friends_list')?> (<?=$c?>)</h3>
         </div>
      <div class="rainbow-body">
      <?php 
      $sql = "select user2,username,name from friends,users where user2 = users.id and user1 = $me and accepted = 1";
      $r = get_query($sql);
      while ($u = $r->fetch_object()){
         $id = $u->user2;
         $username = $u->username;
         $name = $u->name;
      ?>
      <span><a href='<?=$site_home?>/profile/<?=$username?>'><?=$name?></a></span><br>

      <?php } ?>
      

         </div>
   </div>
