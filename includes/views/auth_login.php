<div style="" class="login-box">
			
                   
                         
				<?php
				if(!empty($msg_error)) {
         			?>
                     <div class="box box-solid" style="margin-bottom:8px;">
                	<div class="box-body"  style="font-size:1em;font-weight:bold;text-align:center;line-height:1em;color:#F00;padding:15px 5px 15px 5px;min-height:35px;background-color:#FCE;border:1px solid #F6F;">
							
                            <?php echo $msg_error;?>
                        </div>
                    </div>
			 		<?php
				}
				?>
 <!-- Input addon -->
 			<form action="<?php echo $url_login;?>" method="POST">
              <div class="box box-info">
                <div class="box-header with-border" style="text-align:center">
                  <img src="<?php echo $theme_path;?>images/logo-mini.png" class="user-image" alt="User Image" height="35px;">
                  <span class="hidden-xs" style="margin-left:10px;">
                 
                  <h3 class="box-title"><strong>DASHBOARD</strong></h3>
                  </span>
                </div>
                <div class="box-body">
                 
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="username"  class="form-control" placeholder="User" />
                  </div>
                  <br />
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" />
                  </div>
                 
                </div><!-- /.box-body -->
                 <div class="box-body" >
				     <div class="pull-right "> 
                     	<button type="submit" class="btn btn-primary btn-xs" >&nbsp;&nbsp;&nbsp;Login&nbsp;&nbsp;&nbsp;</button> 
                     </div>
				 </div>
              </div><!-- /.box -->
			</form>
</div>