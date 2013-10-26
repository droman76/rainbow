<?php
/*** FEED ITEM INPUTS
   * $post_id => The post unique id
   * $user_id => The feed items user id 
   * $user_name => The user full name
   * $user_login_id => the user login id 
   * $pic_url => the picture to display of the user
   * $location => the location the message is sent from
   * $ago => how long ago it was posted
   * $message => the body of the message
   * $last_viewed => the time this view was seen last 
   * $view => the current view

**/
$newItem = '';
$view = 'city';
if ($last_viewed <= $timestamp) {
   //$newItem = 'rainbow-new-item';
   //system_log("new item in list");
   //elog("Last viewed: $last_viewed Current Item: $timestamp");
}

if (isset($link_desc) && strlen($link_desc) > 300) {
  $i = strpos($link_desc, ' ',300);
  $link_desc = '<div style="color:gray;font-size:12px;padding-right:10px">'.substr($link_desc, 0,$i).'...</div>';
}

if (isset($action) && $action != '')  {
  if ($action == 'action_join') {
      $action_html = "<span font-size='15px'>".$babel->say($action).' <span style="color:gray">'.$location."</span></span>";
      $message = "<span style='margin-left:140px;'><img style='padding-top:10px' src='/template/default/_graphics/smallspiral.jpg'></span>";
  }
  else if ($action == 'action_avatarchange') {
    $action_html = "<span font-size='15px'>".$babel->say($action)."</span>";
    $avatarimg = get_avatar_image($user_login_id,'big');
    $message = "<span style='margin-left:40px;'><img style='padding-top:10px' src='$avatarimg?nocache=true'></span>";
 
  }
  else if ($action == 'action_groupcreate') {
    $q = "select * from groups where id = '$action_object'";
    $r = get_query($q);
    $g = $r->fetch_object();
    $groupname = $g->name;

    $message = '<br>'.$g->description;
    $message .= "<br><span style='margin-left:140px;'><img style='padding-top:10px' src='/template/default/_graphics/newgroup.jpg'></span>";
    $action_html = "<span font-size='15px'>".$babel->say($action)."</span> <a href='/group/$action_object'>$groupname</a>";
    
  }
}
else if (isset($group) && $group != '' && !isset($isgroup)) {
  global $userid;

  if (is_numeric($group)) {
    $action_html = "posted on the wall";
  
  }else {


    $action_html = "posted in group <a href='/group/$group'>$group</a>";
  }
}
else $action_html = '';


?>
<div class='profile_box'>

<li style='list-style-type:none' id="item-<?=$post_id?>_<?=$view?>" data-timestamp='<?=$timestamp?>' class="rainbow-item <?=$newItem?>">
   <div id="message">
   
      <div class="rainbow-image-block rainbow-river-item clearfix">
      	<div class="rainbow-image">
            <div class="rainbow-avatar rainbow-avatar-small">
               <span class="rainbow-icon rainbow-icon-hover-menu "></span>
               <a href="<?=$CONFIG->site?>/profile/<?=$user_login_id?>" class="">
               <img onmouseout='onTriggerMouseOut(event,"avatar_helper")' onmouseover='loadProfile(this,"avatar_helper",144,-76,"<?=$user_id?>","image")' src="<?=$CONFIG->site?>/template/default/images/spacer.gif" alt="<?=$user_name?>" title="<?=$user_name?>" class="" style="background: url(<?=$pic_url?>) no-repeat;"></a>
            </div>
               
         </div>
  
         <div class="rainbow-body">
            <div class="rainbow-river-summary">
               <a href="<?=$CONFIG->site?>/profile/<?=$user_login_id?>" class="rainbow-river-subject"><b>
               <span style='font-size:14px' onmouseout='onTriggerMouseOut(event,"avatar_helper")' onmouseover='loadProfile(this,"avatar_helper",142,-107,"<?=$user_id?>","link")'>
               <?=$user_name?> 
               </span></b>
               </a> <?=$action_html?>       
            </div>
            
            <div style='float:right;position:relative;top:-13px;width:50px'>
                  <?php
                  $taglist = explode("::", $tags);
                  foreach($taglist as $tag) { if ($tag != '') {?>

                       <img src='<?=$CONFIG->site?>/template/default/images/<?=$tag?>.gif'>
                  <?php   
                  }}
                  ?>
            </div>
            <div class="feed-text-message">
              	<?=$message?>
            </div>
            <div id="video" style='margin-top:0px'>
               <?php if ($link_video != ''){?>
                  <?=$link_video?>
                  <?=$link_desc?>
               <?php } ?>
            </div>
            <div id="images" style='margin-top:14px'>
               <?php if (is_array($images)){
                  foreach ($images as $image) {?>
                    <a href='<?=$CONFIG->site?>/myimages/<?=$user_login_id?>/gallery/feed/<?=$image?>'> <img src="<?=$CONFIG->site?>/myimages/<?=$user_login_id?>/feed/<?=$image?>">
                        </a>
                  <?php
                  }
               } ?>
            </div>
          
      	</div>
      </div>
         
   </div>
   
   <div id="comments" class='rainbow-comment-box'>

      <div class='timetagline' style='position:relative;top:-5px;left:-5px'>
         <span id="item-<?=$post_id?>_<?=$view?>_<?=$timestamp?>" class="rainbow-river-timestamp">
                     <?=$ago?>  

         </span> 
         <span style="font-size:10px;color:gray">
            <i><?=$location?></i><br/>
         </span>
         <?php if (is_user_logged_in()) {?>
         <span style='position:relative;top:-4px'>
          
            <?php if ($you == false) { ?>
            <span id='blessbox1-<?=$post_id?>_<?=$view?>'><a onclick='blessPost(<?=$post_id?>,-1)'><span id='bless-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_like')?></span></a></span> 
            <span style='display:none' id='blessbox2-<?=$post_id?>_<?=$view?>'><a onclick='undoblessPost(<?=$post_id?>,-1)'><span id='bless-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_unblessed',false)?></span></a></span>
            <?php } else { ?>
            <span style='display:none' id='blessbox1-<?=$post_id?>_<?=$view?>'><a onclick='blessPost(<?=$post_id?>,-1)'><span id='bless-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_like')?></span></a></span> 
            <span id='blessbox2-<?=$post_id?>_<?=$view?>'><a onclick='undoblessPost(<?=$post_id?>,-1)'><span id='bless-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_unblessed',false)?></span></a></span>
             
            <?php } ?>

            &#183; <a onclick="viewbox('<?=$post_id?>')"><?=$babel->say('p_comment')?></a> &#183; 

            <?php if ($share->you == false) { ?>
            <span id='sharebox1-<?=$post_id?>_<?=$view?>'><a onclick='sharePost(<?=$post_id?>)'><span id='share-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_share')?></span></a></span> 
            <span style='display:none' id='sharebox2-<?=$post_id?>_<?=$view?>'><a onclick='unsharePost(<?=$post_id?>)'><span id='share-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_unshare',false)?></span></a></span>
            <?php } else { ?>
            <span style='display:none' id='sharebox1-<?=$post_id?>_<?=$view?>'><a onclick='sharePost(<?=$post_id?>)'><span id='share-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_share')?></span></a></span> 
            <span id='sharebox2-<?=$post_id?>_<?=$view?>'><a onclick='unsharePost(<?=$post_id?>)'><span id='share-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_unshare',false)?></span></a></span>
             
            <?php } ?>     
         

            &#183; 
            <?php if ($follow->you == false) { ?>
            <span id='followbox1-<?=$post_id?>_<?=$view?>'><a onclick='followPost(<?=$post_id?>)'><span id='follow-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_follow')?></span></a></span> 
            <span style='display:none' id='followbox2-<?=$post_id?>_<?=$view?>'><a onclick='unfollowPost(<?=$post_id?>)'><span id='follow-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_unfollow',false)?></span></a></span>
            <?php } else { ?>
            <span style='display:none' id='followbox1-<?=$post_id?>_<?=$view?>'><a onclick='followPost(<?=$post_id?>)'><span id='follow-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_follow')?></span></a></span> 
            <span id='followbox2-<?=$post_id?>_<?=$view?>'><a onclick='unfollowPost(<?=$post_id?>)'><span id='follow-<?=$post_id?>_<?=$view?>'><?=$babel->say('p_unfollow',false)?></span></a></span>
          
            <?php } ?>     
         
           
         </span>
         <? } ?>
         <div id="blessings">
      
         </div>  
         <?php if ($blessings > 0 ) {?>
         <div id='blessplaceholder_<?=$post_id?>_<?=$view?>' style='position:relative;left:-5px;' class='rainbow-river-comments'>
            <ul>
               <li><span style='padding-left:5px;font-size:11px;color:gray'>
               <span id='blessplaceholdertext_<?=$post_id?>_<?=$view?>'><?=$btext?></span>
               </span>
               </li>
            </ul>
         </div>
         <?php } else { ?>
         <div id='blessplaceholder_<?=$post_id?>_<?=$view?>' style='display:none;position:relative;left:-5px;' class='rainbow-river-comments'>
            <ul>
               <li><span style='padding-left:5px;font-size:11px;color:gray'>
               <?php echo $babel->say('p_you') .' '. $babel->say('p_blessed_this'); ?>
               </span>
               </li>
            </ul>
         </div>

         <?php } ?>
         <?php if ($share->items > 0 ) {?>
         <div id='shareplaceholder_<?=$post_id?>_<?=$view?>' style='position:relative;left:-5px;' class='rainbow-river-comments'>
            <ul>
               <li><span style='padding-left:5px;font-size:11px;color:gray'>
               <span id='shareplaceholdertext_<?=$post_id?>_<?=$view?>'><?=$share->text?></span>
               </span>
               </li>
            </ul>
         </div>
      <?php } else { ?>
         <div id='shareplaceholder_<?=$post_id?>_<?=$view?>' style='display:none;position:relative;left:-5px;' class='rainbow-river-comments'>
            <ul>
               <li><span style='padding-left:5px;font-size:11px;color:gray'>
               <span id='shareplaceholdertext_<?=$post_id?>_<?=$view?>'><?php echo $babel->say('p_you') .' '. $babel->say('p_share_this'); ?></span>
               </span>
               </li>
            </ul>
         </div>

         <?php } ?>
         <?php if ($follow->items > 0 ) {?>
         <div id='followplaceholder_<?=$post_id?>_<?=$view?>' style='position:relative;left:-5px;' class='rainbow-river-comments'>
            <ul>
               <li><span style='padding-left:5px;font-size:11px;color:gray'>
               <span id='followplaceholdertext_<?=$post_id?>_<?=$view?>'><?=$follow->text?></span>
               </span>
               </li>
            </ul>
         </div>
      <?php } else { ?>
         <div id='followplaceholder_<?=$post_id?>_<?=$view?>' style='display:none;position:relative;left:-5px;' class='rainbow-river-comments'>
            <ul>
               <li><span style='padding-left:5px;font-size:11px;color:gray'>
               <span id='followplaceholdertext_<?=$post_id?>_<?=$view?>'><?php echo $babel->say('p_you') .' '. $babel->say('p_follow_this'); ?></span>
               </span>
               </li>
            </ul>
         </div>

      <?php } ?>
         <div  style='position:relative;left:-5px' class='rainbow-river-comments'>
            <ul id='comment-container_<?=$post_id?>_<?=$view?>'>
               
               <?php $replies = displayComments($post_id,$view);
                  if ($replies > 0) $display = 'display:block';
                  else $display = 'display:none';
               ?>
            </ul>
            <ul> 
               <li id="comment-<?=$post_id?>_<?=$view?>" style='<?=$display?>'>
               <div style='margin-top:7px'>
                  <span style='height:30px;width:30px; float:left;z-index:300;'>
                  <img src="<?=$CONFIG->site?>/template/default/images/spacer.gif" alt="<?=$user_name?>" title="<?=$user_name?>" style="position:relative; top:-4px; left:-5px;margin-bottom:10px;height:30px;width:30px;background: url(<?=$my_pic_url?>) no-repeat;">
                  </span>
                  <textarea id="commentarea_<?=$post_id?>_<?=$view?>" class='rainbow-comment' value='type in your comment' placeholder="<?=$babel->say('p_write_comment',false)?>" onkeypress='commenting(event,this,<?=$post_id?>)'></textarea>
               </div>
               </li>
            </ul>
         </div>
      </div>
   </div>

   

</li>

</div>



<div style='margin-top:10px'></div>