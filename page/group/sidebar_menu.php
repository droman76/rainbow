 <?php 

$path = $_REQUEST['path'];
$page = $_REQUEST['page'];
$group = $path;

?>

 <ul class="rainbow-menu rainbow-menu-page rainbow-menu-page-default">
         <?php if (is_group_admin($group)) { ?>
         <li class="rainbow-menu"><a href="<?=$t_site?>/group?action=edit&groupid=<?=$group?>"><img alt="" src="<?=$t_home?>/images/settings.png" style="vertical-align: middle; "> <?=$babel->say('p_edit')?></a></li>
         <hr>
         <?php } ?>

         <li class="rainbow-menu"><a href="<?=$CONFIG->site?>/group/<?=$group?>"><img src="<?=$t_home?>/images/wire.png" style="vertical-align: middle; "> <?=$babel->say('p_feed')?></a></li>   
         <hr>
         <li class="rainbow-menu"><a href="<?=$CONFIG->siteroot?>group?action=list"><img alt="" src="<?=$t_home?>/images/groups.png" style="vertical-align: middle; "> <?=$babel->say('p_discussions')?></a></li>
    
         <!--
         <li class="rainbow-menu"><a onclick='showMainInbox()'><img src="<?=$t_home?>/images/mail.png" style="vertical-align: middle; "> <?=$babel->say('p_messages')?></a></li>
         
         <li class="rainbow-menu"><a href="<?=$CONFIG->siteroot?>events"><img src="<?=$t_home?>/images/events.png" style="vertical-align: middle; "> <?=$babel->say('p_events')?></a></li>
         <li class="rainbow-menu"><a href="<?=$CONFIG->siteroot?>photos"><img src="<?=$t_home?>/images/photos.png" style="vertical-align: middle; "> <?=$babel->say('p_photos')?></a></li>
         <li class="rainbow-menu"><a href="<?=$CONFIG->siteroot?>videos"><img src="<?=$t_home?>/images/videos.png" style="vertical-align: middle; "> <?=$babel->say('p_videos')?></a></li>
     
         <li class="rainbow-menu"><a href="<?=$CONFIG->siteroot?>group?action=list"><img alt="" src="<?=$t_home?>/images/groups.png" style="vertical-align: middle; "> <?=$babel->say('p_discussions')?></a></li>
             -->

      </ul>