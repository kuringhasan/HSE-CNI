<style class="cp-pen-styles">
body{
	text-align:center;
	padding-left:0;
	padding-right:0;
}
.login-as{
	  width:100%;
	  vertical-align:middle;
	  text-align:center;
	  margin-left:0;
	  margin-right:0;
	  padding-left:0;
	padding-right:0;
}
.col-xs-10{
	 margin-left:0;
	  margin-right:0;
	  padding-left:0;
	padding-right:0;
}
.small-box{
	 margin-left:0;
	  margin-right:0;
	  padding-left:0;
	padding-right:0;
}
@media screen and (max-width: 767px) {
  .login-as{
	  width:100%;
	  vertical-align:middle;
	
  }
}



@media screen and (min-width: 768px) {
 .login-as{
	  width:100%;
	  vertical-align:middle;
	  padding-left:35%;
	  padding-right:35%;
	  padding-top:10%;
  }
}
</style>
 
<div  class="row login-as" style="">
        
    <div class="col-xs-10" style="width:100%" >
        <!-- small box -->
        <div class="small-box bg-aqua">
        <span class="small-box-footer"><h4>Login Sebagai</h4></span>
            <div class="inner">
               <?php
				while($level = current($levels)) {
					$id = "login".$level->LevelID;//uniqid("login_");
					?>
					<div class="btn btn-block btn-primary btn-sm"  onclick="document.<?php echo $id;?>.submit();" style="display:inline-block;">
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
    </div><!-- ./col -->
</div>
