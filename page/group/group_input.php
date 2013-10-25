<?php if ((is_group_member($group) && $postaccess=='members') 
|| (is_group_admin($group) && ($postaccess=='members' || $postaccess=='admins'))
|| ($creator == $me)
) { ?>

<div class="rainbow-module  rainbow-module-featured">
      <div class="rainbow-head">
        <span id='feed-input_group'>
             <h3><?=$babel->say('p_share_feed')?> <?=$babel->say($name)?></h3>
        </span>
        
      </div>
      <div class="rainbow-body">
         <form onsubmit="return false" >
            <fieldset>
               <!-- ***************** INPUT TEXT AREA ************************ -->
                  
               <textarea placeholder='What would you like to share?' rows=1 onfocus='this.rows=4' onblur="resize_feed_input(this)" onkeyup='resize_textarea(this,62);smartTagger(event)'  name="body"  id="feed-textarea"></textarea>
               
               <!-- ***************** TAG CONSOLE ************************ -->
               <span style='display:none' id='tag-a'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_meetings_tag')?></span>
              </span>

               
               
               
               <!-- ***************** POST SUBMIT ************************ -->
               
               <div class="rainbow-foot mts">
                  <button onclick="post('<?=$group?>');return false" id="feed-submit-button" class="rainbow-button rainbow-button-submit">
                    <?=$babel->say('p_post_button')?>
                  </button>
                  <!-- ***************** IMAGE SELECT & UPLOAD ************************ -->
                  <span style='margin-left:10px'><a onclick='_("imagedropzone").style.display="block";_("fileinput").style.display="block"' id='addimages_text'><?=$babel->say('p_addimages')?></a>
                  </span>
                      
                  <span id="upload_progress" style='display:none;float:right' >
                          <img style='float:left' src='<?=$CONFIG->site?>/template/default/_graphics/ajax_loader_bw.gif'> 
                          <span id='upload_percent_progress' style='margin-top:15px;margin-left:8px'><?=$babel->say('p_uploadinprogress')?>
                          </span>
                  </span>
                  <div id='imagedropzone' style='display:none'>
                        <span id="fileinput" style='display:none'>
                        <input value="Choose Images" style='border-width:0px' type="file" id="files" name="files[]" multiple />
                        </span>   
                        <div id="drop_zone"></div>r              
                  </div>
                   <!-- ***************** SMART TAGGING ******************** -->
                  <!-- span  style='width:200px;text-align:right;float:right;margin-top:4px' class="tag-select"  >
                  <a href="#" onclick="t('tag-menu')" onmouseover="javascript:s('tag-menu')"> <?=$babel->say('p_addtag')?></a>
                  <style> p:first-letter { color:red;text-transform:lowercase;font-weight: bolder} </style>
                  <div class='tag-menu' id='tag-menu' style='display:none' i>
            
                        <ul class="submenu" onblur="javascript:h('tag-menu')" >
                          <li id='a_menu-tag' onclick="toggleTag('a')" onmouseout="tagOut('a')" onmouseover="tagIn('a')"><p><?=$babel->say('p_awakening_tag')?></p></li>
                          <li id='b_menu-tag' onclick="toggleTag('b')" onmouseout="tagOut('b')" onmouseover="tagIn('b')" ><p><?=$babel->say('p_barter_tag')?></b></li>
                          <li id='c_menu-tag' onclick="toggleTag('c')" onmouseout="tagOut('c')" onmouseover="tagIn('c')"><p><?=$babel->say('p_community_tag')?></p></li>
                          <li id='e_menu-tag' onclick="toggleTag('e')" onmouseout="tagOut('e')" onmouseover="tagIn('e')"><p><?=$babel->say('p_environment_tag')?></p></li>
                          <li id='f_menu-tag' onclick="toggleTag('f')" onmouseout="tagOut('f')" onmouseover="tagIn('f')"><p><?=$babel->say('p_food_tag')?></p></li>
                          <li id='g_menu-tag' onclick="toggleTag('g')" onmouseout="tagOut('g')" onmouseover="tagIn('g')"><p><?=$babel->say('p_gatherings_tag')?></p></li>
                          <li id='l_menu-tag' onclick="toggleTag('l')" onmouseout="tagOut('l')" onmouseover="tagIn('l')"><p><?=$babel->say('p_love_tag')?></p></li>
                          <li id='m_menu-tag' onclick="toggleTag('m')" onmouseout="tagOut('m')" onmouseover="tagIn('m')"><p><?=$babel->say('p_meetups_tag')?></p></li>
                          <li id='n_menu-tag' onclick="toggleTag('n')" onmouseout="tagOut('n')" onmouseover="tagIn('n')"><p><?=$babel->say('p_news_tag')?></p></li>
                          <li id='o_menu-tag' onclick="toggleTag('o')" onmouseout="tagOut('o')" onmouseover="tagIn('o')"><p><?=$babel->say('p_offerings_tag')?></p></li>
                          <li id='p_menu-tag' onclick="toggleTag('p')" onmouseout="tagOut('p')" onmouseover="tagIn('p')"><p><?=$babel->say('p_projects_tag')?></p></li>
                          <li id='r_menu-tag' onclick="toggleTag('r')" onmouseout="tagOut('r')" onmouseover="tagIn('r')"><p><?=$babel->say('p_projects_tag')?></p></li>
                          <li id='s_menu-tag' onclick="toggleTag('s')" onmouseout="tagOut('s')" onmouseover="tagIn('s')"><p><?=$babel->say('p_spaceshare_tag')?></p></li>
                          
                        </ul>

                  </div>
                     

                  </span -->
                  <div id='tag-messenger' style='padding:10px;display:none'>Please select one or more tags from the list (maximum of 3)</div>
                </div>

            </fieldset>
         </form>
         


      </div> <!-- END body -->
 </div> <!-- END module -->

<div id='activity-group' style='float:left'>
     <h2><?=$babel->say($name)?> <?=$babel->say('p_activity')?></h2>
</div>
<?php } else if (is_group_member($group) ) { ?>
<span><?=$babel->say('p_postonlyadmins')?></span>
<?php }?>


