<table border="1" cellpadding="50" cellspacing="0" align="center">
<tr>
	<td>
    <?php
	echo "<pre>";print_r($level);	echo "</pre>";
	?>
		<div class="ui-dialog ui-widget ui-widget-content ui-corner-all" style="position: relative;">
			<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" ><span class="ui-dialog-title" id="ui-dialog-title-login">Login As</span></div>
			<div class="ui-dialog-content ui-widget-content" style="width: auto; height: auto;">
				<?php
				
				while($level = current($levels)) {
					$id = "login".$level->LevelID;//uniqid("login_");
					?>
					<div class="button"  onclick="document.<?php echo $id;?>.submit();"style="width: 100%;text-align: left;">
						<form action="<?php echo $url_login;?>" method="POST" name="<?php echo $id;?>">
						<input type="hidden" name="username" value="<?php echo $username;?>" />
						<input type="hidden" name="password" value="<?php echo $password;?>" />
						<input type="hidden" name="login_as" value="<?php echo $level->LevelID;?>" />
						<input type="hidden" name="ref_id" value="<?php echo $level->RefID;?>" />
						</form>
						<font size="3"><b>&raquo; <?php echo $level->LevelName;?></b></font>
						<?php
						if(trim($level->RefName) <> "") {
							?><br /><?php echo $level->RefName." : ".$level->RefID;
						}
						?>
						</div>
					<?php
					next($levels);
				}
				?>
			</div>
		</div>
	</td>
</tr>
</table>