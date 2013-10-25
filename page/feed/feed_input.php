<?php
$citydisplay = 'display:none';
$regiondisplay = 'display:none';
$countrydisplay = 'display:none';
$worlddisplay = 'display:none';


if ($_SESSION['city'] != '') $citydisplay = '';
else if ($_SESSION['region'] != '') $regiondisplay = '';
else if ($_SESSION['country'] != '') $countrydisplay = '';
else $worlddisplay = '';

?>

<div class="rainbow-module  rainbow-module-featured">
      <div class="rainbow-head">
        <span id='feed-input_continent' style='display:none'>
             <h3><?=$babel->say('p_share_feed')?> <?=$babel->say($_SESSION['continent_code'])?></h3>
        </span>
        <span id='feed-input_country' style='<?=$countrydisplay?>'>
             <h3><?=$babel->say('p_share_feed')?> <?=$babel->say($_SESSION['country'])?></h3>
        </span>
        <span id='feed-input_region' style='<?=$regiondisplay?>'>
             <h3><?=$babel->say('p_share_feed')?> <?=$babel->say($_SESSION['region'])?></h3>
        </span>
        <span id='feed-input_city' style='<?=$citydisplay?>'>
             <h3><?=$babel->say('p_share_feed')?> <?=$babel->say($_SESSION['city'])?></h3>
        </span>
        <span id='feed-input_world' style='<?=$worlddisplay?>'  >
             <h3><?=$babel->say('p_share_feed')?> <?=$babel->say('p_world')?></h3>
        </span>
        <span id='feed-input_a' style='display:none'  >
             <h3><?=$babel->say('p_share_tag')?> <?=$babel->say('p_awakening_tag')?></h3>
        </span>  
        <span id='feed-input_b' style='display:none'  >
             <h3><?=$babel->say('p_share_barter_tag')?> <?=$babel->say('p_barter_tag')?> </h3>
        </span>  
        <span id='feed-input_c' style='display:none'  >
             <h3><?=$babel->say('p_share_c_tag')?> <?=$babel->say('p_community_tag')?></h3>
        </span>  
        <span id='feed-input_e' style='display:none'  >
             <h3><?=$babel->say('p_share_e_tag')?> <?=$babel->say('p_environment_tag')?></h3>
        </span>  
        <span id='feed-input_f' style='display:none'  >
             <h3><?=$babel->say('p_share_f_tag')?> <?=$babel->say('p_food_tag')?></h3>
        </span>  
        <span id='feed-input_g' style='display:none'  >
             <h3><?=$babel->say('p_share_g_tag')?> <?=$babel->say('p_gatherings_tag')?></h3>
        </span>  
        <span id='feed-input_l' style='display:none'  >
             <h3><?=$babel->say('p_share_l_tag')?> <?=$babel->say('p_love_tag')?></h3>
        </span>  
        <span id='feed-input_m' style='display:none'  >
             <h3><?=$babel->say('p_share_m_tag')?> <?=$babel->say('p_meetups_tag')?></h3>
        </span>  
        <span id='feed-input_n' style='display:none'  >
             <h3><?=$babel->say('p_share_n_tag')?> <?=$babel->say('p_news_tag')?></h3>
        </span>  
        <span id='feed-input_o' style='display:none'  >
             <h3><?=$babel->say('p_share_o_tag')?> <?=$babel->say('p_offerings_tag')?></h3>
        </span>  
        <span id='feed-input_p' style='display:none'  >
             <h3><?=$babel->say('p_share_p_tag')?> <?=$babel->say('p_project_tag')?></h3>
        </span>  
        <span id='feed-input_r' style='display:none'  >
             <h3><?=$babel->say('p_share_r_tag')?> <?=$babel->say('p_rideshare_tag')?></h3>
        </span>  
        <span id='feed-input_s' style='display:none'  >
             <h3><?=$babel->say('p_share_s_tag')?> <?=$babel->say('p_spaceshare_tag')?></h3>
        </span>  


      </div>
      <div class="rainbow-body">
         <form onsubmit="return false" >
            <fieldset>
               <!-- ***************** INPUT TEXT AREA ************************ -->
                  
               <textarea placeholder='What would you like to share?' rows=1 onfocus='this.rows=4' onblur="resize_feed_input(this)" onkeyup='resize_textarea(this,62);smartTagger(event)'  name="body"  id="feed-textarea"></textarea>
               
               <!-- ***************** TAG CONSOLE ************************ -->
               <span style='display:none' id='tag-a'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_awakening_tag')?></span>
              </span>

               
               <span style='display:none' id='tag-b'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?> </span><span style='color:red'><?=$babel->say('p_barter_tag')?></span><span id='barter-offer'></span><span id='barter-ask'></span><div style='color:#9f9e99' id='barter-msg'> Type <b>b,o</b> to say what you offer and/or type <b>b,a</b> to say what you are asking for</div>

               </span>
               <span style='display:none' id='tag-c'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_community_tag')?></span>
              </span>
               <span style='display:none' id='tag-e'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_environment_tag')?></span>
              </span>
              <span style='display:none' id='tag-f'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_food_tag')?></span>
              </span>
              
               <span style='display:none' id='tag-g'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_gatherings_tag')?></span>
              </span>
               <span style='display:none' id='tag-l'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_love_tag')?></span>
              </span>
               <span style='display:none' id='tag-m'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_meetups_tag')?></span>
              </span>
               <span style='display:none' id='tag-n'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_news_tag')?></span>
              </span>
               <span style='display:none' id='tag-o'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_offerings_tag')?></span>
              </span>
               <span style='display:none' id='tag-p'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_projects_tag')?></span>
              </span>
               <span style='display:none' id='tag-r'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_rideshare_tag')?></span>
              </span>
               <span style='display:none' id='tag-s'>
                <span style='font-weight:bold'><?=$babel->say('p_tagged')?>: </span><span style='color:red'><?=$babel->say('p_spaceshare_tag')?></span>
              </span>
               
               <!-- ***************** POST SUBMIT ************************ -->
               
               <div class="rainbow-foot mts">
                  <button onclick="post();return false" id="feed-submit-button" class="rainbow-button rainbow-button-submit">
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
                  <span  style='width:200px;text-align:right;float:right;margin-top:4px' class="tag-select"  >
                  <a href="#" onclick="t('tag-menu')" onclick="javascript:s('tag-menu')"> <?=$babel->say('p_addtag')?></a>
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
                     

                  </span>
                  <div id='tag-messenger' style='padding:10px;display:none'>Please select one or more tags from the list (maximum of 3)</div>
                </div>

            </fieldset>
         </form>
         


      </div> <!-- END body -->
 </div> <!-- END module -->

<div id='activity-title_continent' style='float:left;display:none'>
     <h2><?=$babel->say($_SESSION['continent_code'])?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_country' style='float:left;<?=$countrydisplay?>'>
     <h2 class="rainbow-heading-main" ><?=$babel->say($_SESSION['country'])?> <?=$babel->say('p_activity')?></h2>
</div>

<div id='activity-title_region' style='float:left;<?=$regiondisplay?>'>
     <h2 class="rainbow-heading-main" ><?=$babel->say($_SESSION['region'])?> <?=$babel->say('p_activity')?></h2>
</div>

<div id='activity-title_city' style='float:left;<?=$citydisplay?>'>
     <h2 class="rainbow-heading-main" ><?=$babel->say($_SESSION['city'])?> <?=$babel->say('p_activity')?></h2>
</div>

<div id='activity-title_world' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_world')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_a' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_awakening_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_b' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_barter_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_c' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_community_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_e' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_environment_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_f' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_food_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_g' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_gatherings_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_l' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_love_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_m' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_meetups_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_n' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_news_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_o' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_offerings_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_p' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_projects_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_r' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_rideshare_tag')?> <?=$babel->say('p_activity')?></h2>
</div>
<div id='activity-title_s' style='float:left;display:none'>
     <h2 class="rainbow-heading-main" ><?=$babel->say('p_spaceshare_tag')?> <?=$babel->say('p_activity')?></h2>
</div>

