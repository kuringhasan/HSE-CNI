
          <div class="row">
            <!-- left column -->
            <div class="col-md-6" >
            
              <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Login</h3>
                </div>
                <form action="<?php echo $url_login;?>" method="POST">
                <div class="box-body">
                 
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" name="username" placeholder="User">
                  </div>
                  <br>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                  </div>
				 
                  
                </div><!-- /.box-body -->
				 <div class="box-body" >
				     <div class="pull-right hidden-xs"> 
                     	<button class="btn btn-primary btn" >&nbsp;&nbsp;Login&nbsp;&nbsp;</button> 
                     </div>
				 </div>
                </form>
              </div><!-- /.box -->

            </div><!--/.col (left) -->
            
          </div>   <!-- /.row -->
