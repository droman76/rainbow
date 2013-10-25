<div class="rainbow-module  rainbow-module-featured" style='display:none'>
      <div class="rainbow-head"><h3>Administration Token</h3>
      </div>
      <div class="rainbow-body"><a href="http://www.facebook.com/dialog/oauth?client_id=486260484767148&redirect_uri=https%3A%2F%2Fwww.worldrainbowfamily.org%2Factivity%3Fadmintoken%3Dtrue&state=e6f62be5099d9d16f688ec2c23cf774f&scope=user_groups,publish_actions">Set Admin Token</a>
               
      </div>
</div>
<div class="rainbow-module  rainbow-module-featured">
      <div class="rainbow-head"><h3><?=$babel->say('p_browse_tag')?></h3></div>
      <div class="rainbow-body">
             <img width=15 src="<?=$CONFIG->site?>/template/default/images/a.gif">
		     <span><a onclick="switchTag('a')"><?=$babel->say('p_awakening_tag')?></a><span id='counter_a'></span></span>
		     <br>
		     <img width=15 style='position:relative;top:2px' src="<?=$CONFIG->site?>/template/default/images/b.gif">
		     <span><a onclick="switchTag('b')"><?=$babel->say('p_barter_tag')?></a><span id='counter_b'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/c.gif">
		     <span><a onclick="switchTag('c')"><?=$babel->say('p_community_tag')?></a><span id='counter_c'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/e.gif">
		     <span><a onclick="switchTag('e')"><?=$babel->say('p_environment_tag')?></a><span id='counter_e'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/f.gif">
		     <span><a onclick="switchTag('f')"><?=$babel->say('p_food_tag')?></a><span id='counter_f'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/g.gif">
		     <span><a onclick="switchTag('g')"><?=$babel->say('p_gatherings_tag')?></a><span id='counter_g'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/l.gif">
		     <span><a onclick="switchTag('l')"><?=$babel->say('p_love_tag')?></a><span id='counter_l'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/m.gif">
		     <span><a onclick="switchTag('m')"><?=$babel->say('p_meetups_tag')?></a><span id='counter_m'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/n.gif">
		     <span><a onclick="switchTag('n')"><?=$babel->say('p_news_tag')?></a><span id='counter_n'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/o.gif">
		     <span><a onclick="switchTag('o')"><?=$babel->say('p_offerings_tag')?></a><span id='counter_o'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/p.gif">
		     <span><a onclick="switchTag('p')"><?=$babel->say('p_projects_tag')?></a><span id='counter_p'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/r.gif">
		     <span><a onclick="switchTag('r')"><?=$babel->say('p_rideshare_tag')?></a><span id='counter_r'></span></span>
		     <br>
		     <img width=15 src="<?=$CONFIG->site?>/template/default/images/s.gif">
		     <span><a onclick="switchTag('s')"><?=$babel->say('p_spaceshare_tag')?></a><span id='counter_s'></span></span> 
      	     

      </div>
      

</div>

     <div class="rainbow-module  rainbow-module-featured">
         <div class="rainbow-head"><h3><?=$babel->say('p_latest_groups')?></h3>
         </div>
         <div class="rainbow-body">
            <div class="rainbow-image-block clearfix">
	            <div class="rainbow-image">
            
               </div>

               <?php 
                  $q = "select * from groups order by date DESC limit 10";
                  $r = get_query($q);
                  $trust = get_user_trust_level();
                  $me = get_logged_in_user_id();

                  while ($g = $r->fetch_object()){
                     $name = $g->name;
                     $id = $g->id;
                     $description = $g->description;
                     if (strlen($description) > 200) {
                        //trim
                        $i = strpos($description, ' ',200);

                        $description = substr($description, 0,$i). "...";
                     }
                     $visibility = $g->visibility;
                     $creator = $g->creator;
                     if ($visibility == 'trust' && $trust < 1 && $creator != $me && !is_real_friend($creator))
                        continue;
                     else if ($visibility == 'friends' && $creator != $me && !is_friend($creator)) {
                        continue;
                     }
                     else if ($visibility == 'close' && $creator != $me && !is_real_friend($creator)) {
                        continue;
                     }
                     else if ($visibility == 'private' && $creator != $me) {
                        continue;
                     }

                  ?>
               <div class="rainbow-body"><h3><a href="<?=$CONFIG->siteroot?>group/<?=$id?>"><?=$name?></a></h3>
                  <div class="rainbow-subtext" style='padding:3px;'><?=$description?>
                  </div>
               </div>
               <hr>
               <?php }?>



            </div>
            <div class="rainbow-image-block clearfix">
	  
	            <div class="rainbow-image"><a href="<?=$t_home?>/groups/profile/992/happy-vegetarians">
                  </a>
               </div>
         
            </div>

            <p style="text-align:right; margin:3px 3px;">
            <a href="<?=$CONFIG->site?>/group?action=list"><b>View More</b></a></p>
         </div>
      </div>

 



	<div id='feed_throttle'></div>