<form id='groupcreate' method='post' enctype="multipart/form-data" action='<?=$CONFIG->site?>/action/group/create.php'>
<br>
	<div style='margin-bottom:20px'>
	<span style='font-weight:bolder'><?=$babel->say('p_group_name')?>:</span><br>
	<span><input style='width:400px' name='groupname' value="<?=$groupname?>" type='text'></span>
	</div>

	

	<div>
	<span style='font-weight:bolder'><?=$babel->say('p_group_image')?>:</span><br>
	<span><input type="file" name="image" ></input></span>
	</div><br>

	<div>
	<span style='font-weight:bolder'><?=$babel->say('p_group_desc')?>:</span><br>
	<span><textarea name='description' style='width:500px;height:110px'><?=$description?></textarea></span>
	</div><br>
	<!-- div>
	<span style='font-weight:bolder'><?=$babel->say('p_grouptopic')?>:</span><br>
	<span><select name='topic'>
	</select>
	</span>
	</div>
	<br -->
	
	<div>
	<span style='font-weight:bolder'><?=$babel->say('p_visibility')?>:</span><br>
	<span><select name='visibility'>
	<option value='all'><?=$babel->say('p_allregistered',false)?></option>
	<option <?php if ($visibility == 'trust') echo 'SELECTED';?> value='trust'><?=$babel->say('p_alltrusted',false)?></option>
	<option <?php if ($visibility == 'friends') echo 'SELECTED';?> value='friends'><?=$babel->say('p_allfriends',false)?> </option>
	<option <?php if ($visibility == 'close') echo 'SELECTED';?> value='close'><?=$babel->say('p_allclosefriends',false)?> </option>
	<option <?php if ($visibility == 'private') echo 'SELECTED';?> value='private'><?=$babel->say('p_onlyme',false)?></option>
	</select>
	</span>
	</div>
	<br>

	<div>
	<span style='font-weight:bolder'><?=$babel->say('member_access')?></span><span><?=$babel->say('member_accesscomment')?></span><br>
	<span><select name='access'>
	<option <?php if ($access == 'all') echo 'SELECTED';?> value='all'><?=$babel->say('p_allregistered',false)?></option>
	<option <?php if ($access == 'trust') echo 'SELECTED';?> value='trust'><?=$babel->say('p_alltrusted',false)?></option>
	<option <?php if ($access == 'friends') echo 'SELECTED';?> value='friends'><?=$babel->say('p_allfriends',false)?></option>
	<option <?php if ($access == 'close') echo 'SELECTED';?> value='close'><?=$babel->say('p_allclosefriends',false)?> </option>
	<option <?php if ($access == 'private') echo 'SELECTED';?> value='private'><?=$babel->say('p_onlyme',false)?></option>
	</select>
	</span>
	</div><br>

	<div>
	<span style='font-weight:bolder'><?=$babel->say('post_access')?>:</span><br>
	<span><select name='postaccess'>
	<option <?php if ($postaccess == 'members') echo 'SELECTED';?> value='members'><?=$babel->say('p_membersonly',false)?></option>
	<option <?php if ($postaccess == 'admins') echo 'SELECTED';?> value='admins'><?=$babel->say('p_adminsonly',false)?></option>
	<option <?php if ($postaccess == 'me') echo 'SELECTED';?> value='me'><?=$babel->say('p_onlyme_comment',false)?></option>
	</select>
	</span>
	</div><br>

	
	<div>
	<span style='font-weight:bolder'><?=$babel->say('p_moderation')?>:</span><br>
	<span><select name='moderation'>
	<option <?php if ($moderation == 'admin') echo 'SELECTED';?> value='admin'>Admin Moderated - Only admin users can moderate</option>
	<option <?php if ($moderation == 'useradmin') echo 'SELECTED';?> value='useradmin'>User Moderated - Any user can moderate - admin approves all moderations</option>
	<option <?php if ($moderation == 'userfree') echo 'SELECTED';?> value='userfree'>User Moderated - Any user can moderate - admin is notified of all moderations</option>
	</select>
	</span>
	</div>
	<br>
	<div>
	<input type='hidden' name='action' value='<?=$action?>'>
	<input type='hidden' name='groupid' value='<?=$groupid?>'>
	<button onclick='createGroup()' class="rainbow-button rainbow-button-input"><?=$babel->say($button_action)?>
	</button>
	</div>
	</form>