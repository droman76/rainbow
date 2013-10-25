<h1><?=$babel->say('p_cover')?></h1><br>
<p><?=$babel->say('p_choose_cover_info')?>
<br>
<form action='<?=$CONFIG->siteroot?>action/profile/upload_cover.php' method='post' enctype="multipart/form-data">
<input type="hidden" name="cover" value="edit">
<input type="file" onchange='_("cprogress").style.display="inline";submit(this)' class="rainbow-input-file" name="image" value="upload file">

</form>

<span id='cprogress' style='display:none;font-size:16px;color:darkgrey'><img src='/template/default/_graphics/ajax_loader_bw.gif'> <?=$babel->say('p_loadingcoverplswait')?></span>