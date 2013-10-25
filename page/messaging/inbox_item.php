	<li <?=$id?> class="<?=$highlight?>" onclick="showMainInbox(<?=$loto?>)" onmouseout="if(this.className != 'message-selected')this.className='messageblur';" onmouseover="if(this.className != 'message-selected')this.className='messagehover';" style='margin:0px'>
		<div style='padding-top:4px;padding-bottom:4px'>
		<div style='vertical-align:top;display:inline-block;margin-bottom:3px;margin-right:6px;margin-left:10px;margin-top:3px'>
			<img src='<?=$img?>'>
		</div>
		<div id='inbox-header' style='display:inline-block;margin-bottom:10px;width:<?=$width?>'>
			<span style='font-weight:bolder'><?=$name?><br/></span>
			<span style='color:gray'><?=$reply?><?=$msg?></span>
			<div style='font-size:10px'>
				<i><font color='gray'><?=$ago?><?=$babel->say('p_ago')?></font></i>
			</div>
		</div>
		</div>
		<hr/>
	</li>
