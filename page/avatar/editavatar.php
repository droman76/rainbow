<?php
include_once($CONFIG->home.'page/avatar/config.php');


//Check to see if any images with the same name already exist
if (file_exists($large_image_location)){
	if(file_exists($thumb_image_location)){
		$thumb_photo_exists = "<img style='margin:20px;width:120px' src=\"/myimages/".$avatar_image_name.$image_extension."?nocache=true\" alt=\"Thumbnail Image\"/>";

	}else{
		$thumb_photo_exists = "<img style='margin:20px;width:120px' src=\"$t_home"."/images/defaultmedium.gif\" alt=\"Thumbnail Image\"/>";
	}
   	$large_photo_exists = "<img style='margin:10px' src=\"/myimages/".$large_image_name.$image_extension."?nocache=true\" alt=\"Large Image\"/>";
} else {
   	$large_photo_exists = "<img style='margin:20px;width:120px' src=\"$t_home"."/images/defaultmedium.gif?nocache=true\" alt=\"Thumbnail Image\"/>";
   	$thumb_photo_exists = "<img style='margin:20px;width:120px' src=\"$t_home"."/images/defaultmedium.gif?nocache=true\" alt=\"Thumbnail Image\"/>";
	}

$edit_thumbnail = "false";
if (isset($_GET['edit_thumbnail'])) $edit_thumbnail = "true";
?>

<h1>Edit avatar</h1><br>
<p class="mtm" style='font-size:14px'>
	Your avatar is your image that is displayed throughout the site. You can change it as often as you'd like. (File formats accepted: GIF, JPG or PNG)</p>

<div class="rainbow-image-block clearfix" >
	<div class="rainbow-image">
		<div id="current-user-avatar" class="mrl prl">
			<label>Current avatar</label><br />
			<?=$thumb_photo_exists?>
		</div>
		<?php if (file_exists($large_image_location)){ ?>
	
		<a title="Remove your avatar and set the default icon" href="<?=$t_site?>/action/avatar/delete.php" class="rainbow-button rainbow-button-cancel mll" rel="nofollow">Remove</a>
		<? } ?>
	</div>
	<div class="rainbow-body">

		<form method="post"  name="photo" enctype="multipart/form-data" action="<?=$t_site?>/action/avatar/upload.php" method='post' class="rainbow-form rainbow-form-avatar-upload">
		<fieldset>
		<div>
			<h2><?=$babel->say('p_avataruploadtitle')?></h2><br>
			<div style='font-size:16px'><?=$babel->say('p_avatar_stepone')?></div><br>
			<input type="file" name="image" size="30" class="rainbow-input-file" />
		</div>
		<div class="rainbow-foot">
		<div style='font-size:16px'><?=$babel->say('p_avatar_steptwo')?></div><br>
			
			<input type="submit" onclick='_("progressupload").style.display="inline"' name='upload' value="Upload" class="rainbow-button rainbow-button-submit" />
			<span id='progressupload' style='display:none'><img style='width:16px;height:16px' src='/template/default/_graphics/ajax_loader_bw.gif'> <?=$babel->say('p_uloadingpicplswait')?> </span>
			<br><br><b><a href='<?=$CONFIG->site?>/feed'><?=$babel->say('p_return_feed')?></a></b>
		</div>

		</fieldset>
		</form>
	</div>
	<br>
	<?php if (file_exists($large_image_location)){ ?>
	<div id="avatar-croppingtool" class="mtl ptm">
		<div style='font-size:16px'><?=$babel->say('p_avatar_stepthree')?></div><br>
	
	<p style='font-size:14px'>
	<?=$babel->say('p_croppinginfo')?>
	</p>	
	<?php /*

	<form name="thumbnail" method="post" action="/action/avatar/upload.php" class="rainbow-form rainbow-form-avatar-crop">
		<fieldset>
			<div class="clearfix">
				<img src="<?php echo '/myimages/'.$large_image_name.$image_extension;?>" id="thumbnail" style='float:left;margin-right:30px' alt="Create Thumbnail" />

				<div id="user-avatar-preview-title"><label>Preview</label>
				</div>
					<div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;">
				<img src="<?php echo '/myimages/'.$large_image_name.$image_extension;?>" style="position: relative;" alt="Thumbnail Preview" />
			
				</div>
			</div>
			<div class="rainbow-foot">
				<input type="hidden" name="x1" value="" id="x1" />
				<input type="hidden" name="y1" value="" id="y1" />
				<input type="hidden" name="x2" value="" id="x2" />
				<input type="hidden" name="y2" value="" id="y2" />
				<input type="hidden" name="w" value="" id="w" />
				<input type="hidden" name="h" value="" id="h" />
				<input type="submit" name="upload_thumbnail" value="Create your avatar" class="rainbow-button rainbow-button-submit" />
			</div>
		</fieldset>
	</form>
	</div>
</div>
	
	


*/ ?>

<?php
//Display error message if there are any

/*
if($edit_thumbnail == "false" && strlen($large_photo_exists)>0 && strlen($thumb_photo_exists)>0){
	echo $large_photo_exists."&nbsp;".$thumb_photo_exists;
	echo "<p><a href=\"".'/action/avatar/delete.php'."?a=delete&t=".$_SESSION['username']."\">Delete images</a></p>";
	?>
	<form name="photo" enctype="multipart/form-data" action="/action/avatar/upload.php" 
	method="post">
	Upload Avatar Photo <input type="file" name="image" size="30" /> <input type="submit" name="upload" value="Upload" />
	</form>
	<a href="/avatar?edit_thumbnail=true">Edit thumbnail</a></p>
	<a href="/">Return to feed</a></p>

	<?php

	


}else{

		if(strlen($large_photo_exists)>0 || $edit_thumbnail="true"){?>
	*/
			?>
		<div align="center">
			<img src="<?php echo '/myimages/'.$large_image_name.$image_extension;?>?nocache=true" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail" />
			
			<div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;">
				<img src="<?php echo '/myimages/'.$large_image_name.$image_extension;?>?nocache=true" style="position: relative;" alt="Thumbnail Preview" />
			</div>
			<br><br>
			<br><br>
			<br><br>
			<br><br>
			<br><br>
			
			
			<form style='float:left' name="thumbnail" action="/action/avatar/upload.php" method="post">
				<input type="hidden" name="x1" value="" id="x1" />
				<input type="hidden" name="y1" value="" id="y1" />
				<input type="hidden" name="x2" value="" id="x2" />
				<input type="hidden" name="y2" value="" id="y2" />
				<input type="hidden" name="w" value="" id="w" />
				<input type="hidden" name="h" value="" id="h" />
				<input type="submit" class="rainbow-button rainbow-button-cancel mll" name="upload_thumbnail" value="<?=$babel->say('p_createavatar',false)?>" id="save_thumb" />
			</form>
			<br style="clear:both;"/>
			
		</div>
	<hr />
	<?php /*
	<h2>Upload Photo</h2>
	<form name="photo" enctype="multipart/form-data" action="/action/avatar/upload.php" method="post">
	Photo <input type="file" name="image" size="30" /> <input type="submit" name="upload" value="Upload" />
	</form>

<!-- Copyright (c) 2008 http://www.webmotionuk.com -->

*/  }?>

