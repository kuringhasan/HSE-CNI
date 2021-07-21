<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $page_title;?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/bootstrap/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/bootstrap/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/dist/css/skins/_all-skins.min.css">
    
    <!-- datepicker -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/bootstrap-datepicker-1.5.1/css/bootstrap-datepicker.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">

      <header class="main-header">
        <nav class="navbar navbar-static-top">
          <div class="container">
              <div class="navbar-header">
              <a href="../../index2.html" class="navbar-brand"><b>Admin</b>LTE</a>
             
            </div>

            <!-- Navbar Right Menu -->
              <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                  <!-- Messages: style can be found in dropdown.less-->
                  <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-envelope-o"></i>
                      <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                      <li class="header">You have 4 messages</li>
                      <li>
                        <!-- inner menu: contains the messages -->
                        <ul class="menu">
                          <li><!-- start message -->
                            <a href="#">
                              <div class="pull-left">
                                <!-- User Image -->
                                <img src="<?php echo $theme_path;?>bootstrap/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                              </div>
                              <!-- Message title and timestamp -->
                              <h4>
                                Support Team
                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                              </h4>
                              <!-- The message -->
                              <p>Why not buy a new awesome theme?</p>
                            </a>
                          </li><!-- end message -->
                        </ul><!-- /.menu -->
                      </li>
                      <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                  </li><!-- /.messages-menu -->

                  <!-- Notifications Menu -->
                  <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-bell-o"></i>
                      <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                      <li class="header">You have 10 notifications</li>
                      <li>
                        <!-- Inner Menu: contains the notifications -->
                        <ul class="menu">
                          <li><!-- start notification -->
                            <a href="#">
                              <i class="fa fa-users text-aqua"></i> 5 new members joined today
                            </a>
                          </li><!-- end notification -->
                        </ul>
                      </li>
                      <li class="footer"><a href="#">View all</a></li>
                    </ul>
                  </li>
                  <!-- Tasks Menu -->
                  <li class="dropdown tasks-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-flag-o"></i>
                      <span class="label label-danger">9</span>
                    </a>
                    <ul class="dropdown-menu">
                      <li class="header">You have 9 tasks</li>
                      <li>
                        <!-- Inner menu: contains the tasks -->
                        <ul class="menu">
                          <li><!-- Task item -->
                            <a href="#">
                              <!-- Task title and progress text -->
                              <h3>
                                Design some buttons
                                <small class="pull-right">20%</small>
                              </h3>
                              <!-- The progress bar -->
                              <div class="progress xs">
                                <!-- Change the css width attribute to simulate progress -->
                                <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                  <span class="sr-only">20% Complete</span>
                                </div>
                              </div>
                            </a>
                          </li><!-- end task item -->
                        </ul>
                      </li>
                      <li class="footer">
                        <a href="#">View all tasks</a>
                      </li>
                    </ul>
                  </li>
                  <!-- User Account Menu -->
                  <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <!-- The user image in the navbar-->
                      <img src="<?php echo $theme_path;?>bootstrap/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                      <!-- hidden-xs hides the username on small devices so only the image appears. -->
                      <span class="hidden-xs">Alexander Pierce</span>
                    </a>
                    <ul class="dropdown-menu">
                      <!-- The user image in the menu -->
                      <li class="user-header">
                        <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        <p>
                          Alexander Pierce - Web Developer
                          <small>Member since Nov. 2012</small>
                        </p>
                      </li>
                      <!-- Menu Body -->
                      <li class="user-body">
                        <div class="col-xs-4 text-center">
                          <a href="#">Followers</a>
                        </div>
                        <div class="col-xs-4 text-center">
                          <a href="#">Sales</a>
                        </div>
                        <div class="col-xs-4 text-center">
                          <a href="#">Friends</a>
                        </div>
                      </li>
                      <!-- Menu Footer-->
                      <li class="user-footer">
                        <div class="pull-left">
                          <a href="#" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                          <a href="<?php echo $url_logout;?>" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div><!-- /.navbar-custom-menu -->
          </div><!-- /.container-fluid -->
        </nav>
      </header>
<style>
	  .dropdown-menu>li
{	position:relative;
	-webkit-user-select: none; /* Chrome/Safari */        
	-moz-user-select: none; /* Firefox */
	-ms-user-select: none; /* IE10+ */
	/* Rules below not implemented in browsers yet */
	-o-user-select: none;
	user-select: none;
	cursor:pointer;
}
.dropdown-menu .sub-menu {
    left: 100%;
    position: absolute;
    top: 0;
    display:none;
    margin-top: -1px;
	border-top-left-radius:0;
	border-bottom-left-radius:0;
	border-left-color:#fff;
	box-shadow:none;
}
.right-caret:after,.left-caret:after
 {	content:"";
    border-bottom: 5px solid transparent;
    border-top: 5px solid transparent;
    display: inline-block;
    height: 0;
    vertical-align: middle;
    width: 0;
	margin-left:5px;
}
.right-caret:after
{	border-left: 5px solid #ffaf46;
}
.left-caret:after
{	border-right: 5px solid #ffaf46;
}
</style>
       <header class="main-header" ><!-- awal dropdown menu-->
        <nav class="navbar navbar-static-top" >
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
              </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse"  >
              <ul class="nav navbar-nav" >
              
                	<?php
			function buildMenu($data,$current_page_id="") {
				while($each = each($data)) {
					?>
					<li <?php echo $each[1]->PageID==$current_page_id?" class=\"active\"":""; //echo count($each[1]->PageChilds)>1?" class=\"dropdown\"":"";?> >
                    <?php
					$caret="<span class=\"caret\"></span>";
					$class_link="class=\"dropdown-toggle\" data-toggle=\"dropdown\"";
					if($each[1]->PageParentID>1)
					{
						$caret="";
						$class_link=" class=\"trigger right-caret\"";
					}
					
					?>
						<a href="<?php echo count($each[1]->PageChilds)>1?"#":$each[1]->PageUrl;?>" <?php echo count($each[1]->PageChilds)>1?$class_link:"";?>><?php echo $each[1]->PageTitle?> <?php echo count($each[1]->PageChilds)>1?$caret:"";?></a>
						<?php
						if(count($each[1]->PageChilds)) {
								$class_menu=$each[1]->PageParentID==1?" class=\"dropdown-menu\"":" class=\"dropdown-menu sub-menu\"";  
							?>
							<ul <?php echo $class_menu;?> >
								<?php buildMenu($each[1]->PageChilds);?>
							</ul>
							<?php
						}
						?>
					</li>
					<?php
				}
			}

			buildMenu($menus,$PageID);

			?>
              </ul>
              <?php  //echo $PageID;//echo "<pre>"; print_r($menus);echo "</pre>";?>
            </div><!-- /.navbar-collapse -->
           
          </div><!-- /.container-fluid -->
        </nav>
      </header><!-- end of drop down menu -->
      <!-- Full Width Column -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           <?php echo $content_title;?>
            <!--<small>Control panel</small>-->
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $content_title;?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
      
          <!-- Main row -->
          <div class="row" style="padding:5px 5px 5px 5px;">
		  <?php echo $content;?>
		  </div>

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="container">
          <div class="pull-right hidden-xs">
            <b>Version</b> 2.3.0
          </div>
          <strong>Copyright &copy; 2014-2015 <a href="www.damas.or.id">Damas IT Center</a>.</strong> All rights reserved.
        </div><!-- /.container -->
      </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo $theme_path;?>bootstrap/bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $theme_path;?>bootstrap/dist/js/app.min.js"></script>
    
    <script src="<?php echo $theme_path;?>bootstrap/bootstrap-datepicker-1.5.1/js/bootstrap-datepicker.js"></script>
     <script src="<?php echo $theme_path;?>bootstrap/bootstrap-datepicker-1.5.1/locales/bootstrap-datepicker.id.min.js"></script>
   
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo $theme_path;?>bootstrap/dist/js/demo.js"></script>
    <script>
	$(function(){
		$(".dropdown-menu > li > a.trigger").on("click",function(e){
			var current=$(this).next();
			var grandparent=$(this).parent().parent();
			if($(this).hasClass('left-caret')||$(this).hasClass('right-caret'))
				$(this).toggleClass('right-caret left-caret');
			grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
			grandparent.find(".sub-menu:visible").not(current).hide();
			current.toggle();
			e.stopPropagation();
		});
		$(".dropdown-menu > li > a:not(.trigger)").on("click",function(){
			var root=$(this).closest('.dropdown');
			root.find('.left-caret').toggleClass('right-caret left-caret');
			root.find('.sub-menu:visible').hide();
		});
	});
    </script>
  </body>
</html>
