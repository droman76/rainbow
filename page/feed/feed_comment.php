<li id='comment-<?=$comment_id?>_<?=$view?>' data-postid='<?=$postid?>' data-commentid='<?=$comment_id?>' data-timestamp='<?=$timestamp?>' >
<div style='min-height:40px'>
 	<span class='rainbow-avatar rainbow-avatar-small' style='height:30px;width:30px; float:left;z-index:1;'>
    	<img onmouseout='onTriggerMouseOut(event,"avatar_helper")' onmouseover='loadProfile(this,"avatar_helper",110,-90,"<?=$cuser_id?>","image")'  src="<?=$CONFIG->site?>/template/default/images/spacer.gif" alt="<?=$user_name?>" title="<?=$user_name?>" style="position:relative; top:2px; left:-5px;margin-bottom:10px;height:30px;width:30px;background: url(<?=$pic_url?>) no-repeat;">
		</img>
	</span>
 	<div style='font-size:11px;font-color:#9ea2ae'>
 	<?php if ($cuser_id == $me_id) { ?>
 		<div onclick='deleteComment(<?=$comment_id?>,<?=$postid?>)' style='float:right;margin:2px' class='rainbow-icon rainbow-icon-delete'></div>
 	<? } ?>
 		<span onmouseout='onTriggerMouseOut(event,"avatar_helper")' onmouseover='loadProfile(this,"avatar_helper",142,-107,"<?=$cuser_id?>","link")'>
 	
 	<a href='/profile/<?=$user_name?>'><?=$user_full_name?></a>
 	</span> <?=$comment?>
 	</div>
 	<span class="rainbow-river-timestamp" id='ago_<?=$comment_id?>_<?=$view?>'><?=$ago?>
 	</span>
 	<span class="rainbow-river-timestamp"> <?=$location?> - 
 	<?php if (!$you) { ?>
	 	<span id='commentlikestxt1-<?=$postid?>-<?=$comment_id?>_<?=$view?>'>
	 	<a style='font-style:normal' onclick='likeComment(<?=$postid?>,<?=$comment_id?>)'><?=$babel->say('p_ilike')?></a> 
	 	</span>
		<span id='commentlikestxt2-<?=$postid?>-<?=$comment_id?>_<?=$view?>' style='display:none'>
	 	<a style='font-style:normal' onclick='unlikeComment(<?=$postid?>,<?=$comment_id?>)'><?=$babel->say('p_unlike')?></a> 
	 	</span>
	<?php } else { ?>
		<span id='commentlikestxt1-<?=$postid?>-<?=$comment_id?>_<?=$view?>' style='display:none'>
	 	<a style='font-style:normal' onclick='likeComment(<?=$postid?>,<?=$comment_id?>)'><?=$babel->say('p_ilike')?></a> 
	 	</span>
		<span id='commentlikestxt2-<?=$postid?>-<?=$comment_id?>_<?=$view?>'>
	 	<a style='font-style:normal' onclick='unlikeComment(<?=$postid?>,<?=$comment_id?>)'><?=$babel->say('p_unlike')?></a> 
	 	</span>

	<?php } ?>
	<?php if ($likes > 0) { 
		$likevisible = 'display:inline';
 		
 	} else {
 		$likevisible = 'display:none';
 		} ?>

	 	<span id='commentlikes-<?=$postid?>-<?=$comment_id?>_<?=$view?>' style='<?=$likevisible?>'><?=$likes?>
	 	</span>
	 	

 	</span>
</div>
</li>
