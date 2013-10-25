<?php

$path = $_REQUEST['path'];
$page = $_REQUEST['page'];
$group = $path;

$q = "select * from groups where id = '$group'";
ilog($q);
$r = get_query($q);
$g = $r->fetch_object();
$groupinfo = $g->description;
$groupinfo = str_replace("\n", '<br>', $groupinfo);
$creator = $g->creator;
$access = $g->access;
$me = get_logged_in_user_id();

?>

<?php if ($me != $creator && !is_group_member($group)) { ?>
<form id='joingroup' action='/action/group/join.php' method='POST'>
<input type='hidden' name='groupid' value="<?=$group?>">

<button onclick='_("joingroup").submit()' class='rainbow-button rainbow-button-submit'>+ <?=$babel->say('p_joingroup')?></button>
</form>
<br><br>
<?php } ?>


<div class="rainbow-module  rainbow-module-featured" style='display:none'>
      <div class="rainbow-head"><h3>Administration Token</h3>
      </div>
      <div class="rainbow-body"><a href="http://www.facebook.com/dialog/oauth?client_id=486260484767148&redirect_uri=https%3A%2F%2Fwww.worldrainbowfamily.org%2Factivity%3Fadmintoken%3Dtrue&state=e6f62be5099d9d16f688ec2c23cf774f&scope=user_groups,publish_actions">Set Admin Token</a>
               
      </div>
</div>

<div class="rainbow-module  rainbow-module-popup">
      <div class="rainbow-head"><h3><?=$babel->say('p_groupinfo')?></h3>
      </div>
      <div class="rainbow-body">
      <div style='font-size:13px;margin-top:5px;margin-bottom:5px;padding:2px'><?=$groupinfo?> </div>        
      </div>
</div>
<?php

$q = "select count(userid) as members from group_members where groupid = '$group'";
$r = get_query($q);
$c = $r->fetch_object()->members;

?>

<div class="rainbow-module  rainbow-module-popup">
      <div class="rainbow-head"><h3><?=$babel->say('p_groupmembers')?> (<?=$c?>)</h3>
      </div>
      <div class="rainbow-body">

      <div id='group-membershort' style='font-size:15px;margin-top:5px;margin-bottom:5px;padding:2px'>
      <?php 
      $q = "select * from group_members,users where groupid = '$group' and users.id = group_members.userid";
      $r = get_query($q);
      $i = 0; $more = false;
      while ($o = $r->fetch_object()) {
        $name = $o->name;
        $username = $o->username;
        echo "<a href='/profile/$username'>$name</a><br>";
        if ($i == 20) {
          $more = true;
          break;
        }
        $i++;
      }
      if ($more) {
         echo "<div id='viewmore'><br><span style='color:darkred'><a style='color:darkred' onclick='_(\"viewmore\").style.display=\"none\";_(\"group-memberlong\").style.display=\"inline\"')>".$babel->say('p_viewmore')."</a></span></div>";
      }

      ?>

      </div> 
      <div id='group-memberlong' style='display:none;font-size:15px;margin-top:5px;margin-bottom:5px;padding:2px'>
       <?php 
      while ($o = $r->fetch_object()) {
        $name = $o->name;
        $username = $o->username;
        echo "<a href='/profile/$username'>$name</a><br>";

      }
      ?>

      </div>


      </div>
</div>

<!--div class="rainbow-module  rainbow-module-featured">
      <div class="rainbow-head"><h3><?=$babel->say('p_browse_tag')?></h3></div>
      <div class="rainbow-body">
             <img width=15 src="<?=$CONFIG->site?>/template/default/images/a.gif">
		     <span><a onclick="switchTag('a')"<?=$babel->say('p_meeting_points')?></a><span id='counter_a'></span></span>
		     <br>
		     <img width=15 style='position:relative;top:2px' src="<?=$CONFIG->site?>/template/default/images/b.gif">
		     <span><a onclick="switchTag('b')"<?=$babel->say('p_poems')?></a><span id='counter_b'></span></span>
		     <br>
		     

      </div>
      

</div -->
<?php if ($me != $creator && is_group_member($group)){?>
<form id='leavegroup' action='/action/group/leave.php' method='POST'>
<input type='hidden' name='groupid' value="<?=$group?>">

<button onclick='_("leavegroup").submit()' style='font-size:12px;background-color:lightgray' class='rainbow-button'>- <?=$babel->say('p_leavegroup')?></button>
</form>
<br><br>

<?php } ?>
	<div id='feed_throttle'></div>