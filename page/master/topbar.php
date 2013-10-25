<div id='system_messages' class="rainbow-page-messages">
   <!--.rainbow-state-success.rainbow-state-error.rainbow-state-notice -->
   <ul class="rainbow-system-messages">
      <?php 
         $messages = get_all_system_messages();
         if (isset($messages) && $messages != ''){
            $messages = explode('::', $messages);
            foreach($messages as $message) {
                if ($message == '') continue;
                   ?>
      <li style="opacity: 0.9;" class='rainbow-message rainbow-state-success'>
         <?=$message?> 
      </li>
      <?php   
         }
         }
         ?>
   </ul>
</div>
<?php if (is_user_logged_in()) {?>
<div class="rainbow-page-topbar">
   <div class="rainbow-inner">
      <ul class="rainbow-menu rainbow-menu-topbar rainbow-menu-topbar-alt">
         <!-- li class="rainbow-menu-item-administration"><a href="<?=$t_home?>/admin"><span class="rainbow-icon rainbow-icon-settings "></span>  <?=$babelh->say('p_administration')?></a></li>
         <li class="rainbow-menu-item-usersettings"><a href="<?=$t_home?>/settings/user/daniel"><span class="rainbow-icon rainbow-icon-settings "></span>  <?=$babelh->say('p_settings')?></a></li -->
         <li class="rainbow-menu-item-logout"><a href="<?=$CONFIG->site.'?action=logout'?>">  <?=$babelh->say('p_logout')?></a></li>
      </ul>
      <ul class="rainbow-menu rainbow-menu-topbar rainbow-menu-topbar-default">
         
         <li><a href='<?=$CONFIG->siteroot?>'><span class='rainbow-icon' style='background-image:url(<?=$CONFIG->siteroot?>rainbowlogo.png);height:16px;width:100px'></span></a></li>
         <li><a onclick="showFriendInbox(this,'friend-inbox',336,0)" title="Friends"><span class="rainbow-icon rainbow-icon-users "></span>
         <span id="counter_friends"></span>
         </a>
         </li>
         <li>
            <a onclick="showInbox(this,'message-inbox',436,0)">
               <span id="messages-topbar-icon" class="rainbow-icon rainbow-icon-mail"></span>
               <span id="counter_mail"></span>
            </a>
         </li>
         <li>
            <a onclick="showNotificationInbox(this,'notification-inbox',338,2)">
               <div id="notification-topbar-icon" class="notification-icon"></div>
               <span id="counter_notifications"></span>
            </a>
         </li>
      </ul>
   </div>
</div>
<?php } ?>

<div id='friend-inbox' style='display:none;position:absolute;z-index:400000' class="uiContextualDialogPositioner uiContextualDialogLeft">
 <div class="uiOverlay uiContextualDialog uiOverlayArrowRight" style="width: 347px; top: 0px; ">
      <div class="uiUpArrow" style=""></div>
      <div class="uiOverlayContent">
         <div id='friend-inbox_content' style='padding:0px' class="uiOverlayContentHolder" onmouseover='onBoxMouseIn()'>
         </div>
         <div id='friend-inbox_loading' class="uiOverlayContentHolder" style='display:none'>
            <span style='margin-left:150px'><img src='<?=$CONFIG->siteroot?>template/default/_graphics/ajax_loader.gif'></span>
         </div>
         
      </div>
     </div>

</div>


<div id='notification-inbox' style='display:none;position:absolute;z-index:400000' class="uiContextualDialogPositioner uiContextualDialogLeft">
 <div class="uiOverlay uiContextualDialog uiOverlayArrowRight" style="width: 347px; top: 0px; ">
      <div class="uiUpArrow" style=""></div>
      <div class="uiOverlayContent">
         <div id='notification-inbox_content' style='padding:0px' class="uiOverlayContentHolder" onmouseover='onBoxMouseIn()'>
         </div>
         <div id='notification-inbox_loading' class="uiOverlayContentHolder" style='display:none'>
            <span style='margin-left:150px'><img src='<?=$CONFIG->siteroot?>template/default/_graphics/ajax_loader.gif'></span>
         </div>
         
      </div>
     </div>

</div>



<div id='message-inbox' style='display:none;position:absolute;z-index:400000' class="uiContextualDialogPositioner uiContextualDialogLeft">
 <div class="uiOverlay uiContextualDialog uiOverlayArrowRight" style="width: 447px; top: 0px; ">
      <div class="uiUpArrow" style=""></div>
      <div class="uiOverlayContent">
         <div id='message-inbox_content' style='padding:0px' class="uiOverlayContentHolder" onmouseover='onBoxMouseIn()'>
         </div>
         <div id='message-inbox_loading' class="uiOverlayContentHolder" style='display:none'>
            <span style='margin-left:150px'><img src='<?=$CONFIG->siteroot?>template/default/_graphics/ajax_loader.gif'></span>
         </div>
         
      </div>
     </div>

</div>

<div id='inbox-main' onclick='if(!inside)_("inbox-main").style.display="none"' style='display:none' class='modal-bg'>
   <div id='inbox-userlist' onmouseout='inside=false' onmouseover='inside=true' style='position:absolute;margin-top:20px;margin-left:10px;top:0px:left:0px;width:360px;height:100px;overflow-y:scroll;' class='modal'>
   </div>
   <div id='inbox-conversation' onmouseout='inside=false;' onmouseover='inside=true;' style='position:absolute;width:63%;left:380px;margin-top:20px;height:100px' class='modal'>
      <div id="conversation-header" style='width:100%'></div>
      
      <div id="conversation-box" onmouseout='this.style.overflowY="hidden"' onmouseover='this.style.overflowY="auto"' class='conversation-thread'>
         <div id="floweroflife">
         <center><img src='/template/default/_graphics/floweroflife.jpg'></center><br>
         </div>
         <ul>
         <div id="conversation-thread"></div>
         </ul>
      </div>
      <div id="conversation-newmessage" class='newmessagebox'>
         <div style='margin:20px;margin-left:60px'>
         <textarea id='conversation-textarea' placeholder='Write a reply...' style='margin-top:10px;;width:500px' rows='4'></textarea>
         <br><button style='margin-bottom:10px' onclick='replyConversation()'>Reply</button>
         </div>
      </div>


   </div>
</div> 