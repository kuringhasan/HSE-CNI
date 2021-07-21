 <link rel="stylesheet" type="text/css" href="<?php echo $theme_path;?>css/login.css" />
 <style>
		.header-info{
			-webkit-border-radius:5px 5px 0px 0px;
			-moz-border-radius:5px 5px 0px 0px;
			border-radius:5px 5px 0px 0px;
			border:1px #063 solid;
			background-color:#063;
			font-size:15px;
			font-weight:bold;
			padding:5px 5px 5px 5px;
			color:#FFF;
			text-align:left;
		}
		.body-info{
			border:1px #CCC solid;
			border-top:0px;
			background:#EAF4F5; 
		}
		#side_menu .body-info{
			background: #eee; /* Old browsers */
			background: -moz-linear-gradient(top, rgba(252,252,252,1) 0%, rgba(224,224,224,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(252,252,252,1)), color-stop(100%,rgba(224,224,224,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top, rgba(252,252,252,1) 0%,rgba(224,224,224,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top, rgba(252,252,252,1) 0%,rgba(224,224,224,1) 100%); /* Opera11.10+ */
			background: -ms-linear-gradient(top, rgba(252,252,252,1) 0%,rgba(224,224,224,1) 100%); /* IE10+ */
			background: linear-gradient(top, rgba(252,252,252,1) 0%,rgba(224,224,224,1) 100%); /* W3C */
		}
		</style>
 <div align="center">
 <div style="width:21.4em;" >
 <div class="header-info" >Login </div>
   <div class="body-info" style="padding-top:5px;">
   <div id="form_wrapper" class="form_wrapper" style="margin-top:0px;">
   <form name="frm_login" class="login active" action="<?php echo $url_login;?>" method="post" >
						<input type="hidden"  name="url_current" value="<?php echo url::current("");?>" />
   
                        <?php echo $_SESSION['error'];?>
                        <?php 
						if (isset($_GET['err'])){
						    echo "<div class='error' style='width:12.7em;margin:0.5em 0.5em 0.5em 0.7em;'>".$konfirmerror[$_GET['err']]."</div>";
						 }
						?>
						<div  >
							<label>Username:</label>
							<input type="text"  name="username" />
						</div>
						<div style="padding: 0px 0px 0px 0px;">
							 <label>Password: </label>
							<input type="password" name="password"/>
						</div>
                        <div  style="height:48px;">
							<input type="submit" value="Login"  name="btn_login"/>
                            <a href="forgot_password.html" rel="forgot_password"  style="font-size: 11px;margin-top: 15px;float: left;">Poho password?</a>
							<div class="clear"></div>
						</div>
					</form>
            </div>
   </div>
</div>
</div>


 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Login</h3>
                </div>
                <div class="box-body">
                 
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="email" class="form-control" placeholder="User">
                  </div>
                  <br>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Password">
                  </div>
                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
