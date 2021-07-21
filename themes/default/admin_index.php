<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $page_title;?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
     <link rel="shortcut icon" href="<?php echo $theme_path;?>images/favicon-16x16.png" />
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/bootstrap/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/bootstrap/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/dist/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?php echo $theme_path;?>bootstrap/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery-1.10.2.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue">
  		
       
   		<div class="wrapper">
                  <header class="main-header">
        <!-- Logo -->
    
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
             <div class="navbar-header" style="margin-left:10px;margin-top:0;">
             <span class="logo-lg"><img src="<?php echo $theme_path;?>images/header-logo.png" style="margin-left:0px;height:50px;"  ></span>
              
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
              </button>
            </div>
         
      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
           
           <ul class="nav navbar-nav" style="margin-left:10px;">
           
           <?php 
				function treeMenu($data,$level=1){
					while($each = each($data)) {
						$aktiv	=$key==0?" ":"";
						$class_li=$key==0?" class=\"nav-item\"":"class=\"nav-item\"";
						$class_ul="";
						$data_a="class=\"nav-link\"";
						$caret="";
						if(count($each[1]->PageChilds)>=1){
							if(($level+1)==2){
								$class_li=" class=\"nav-item dropdown $aktiv\" ";
								$class_ul=" class=\"dropdown-menu\" ";
								$data_a=" class=\"nav-link dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"";
								$caret="<span class=\"caret\"></span>";
							}
						}
						?>
						<li <?php echo $class_li;?>><a href="<?php echo $each[1]->PageUrl?>" <?php echo $data_a;?>><?php echo $each[1]->PageTitle?> <?php echo $caret;?></a>
						<?php
							if(count($each[1]->PageChilds)>=1) {
								?>
								<ul <?php echo $class_ul;?>>
									<?php treeMenu($each[1]->PageChilds,$level+1);?>
								</ul>
								<?php
							}
							?>
						</li>
                <?php 
					}//end while
				}
				treeMenu($menus);
				?>
           
           
           <!--
            	<li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
               <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>-->
              </ul>
          </div><!-- /.navbar-collapse -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            	
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo url::base();?>foto/nofoto_man.jpg" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $_SESSION['framework']['current_user']->Name;?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo url::base();?>foto/nofoto_man.jpg" class="img-circle" alt="User Image">
                    <p>
                      <?php echo $CurrentUserLevel->LevelName;?>
                      <small>
					  <strong><?php echo $CurrentUnit['current']['name'];?></strong><br />
					  </small>
					  <?php echo $Unit;?>
                    </p>
                  </li>
                  <!-- Menu Body -->
                 <!-- <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Test</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li>-->
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo $url_ubahpassword;?>" class="btn btn-default btn-flat">Ubah Password</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo $url_logout;?>" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
             
            </ul>
          </div>
        </nav>
      </header>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header" style="">
                    <div  style="font-size:1.3em; width:40%;">
                  		<strong> <?php echo $content_title;?></strong>
                    <!-- <small>Control panel</small>-->
                    </div>
                    <div  style="top:5px;position:absolute; right:15px;" class="pull-right">
                   		<?php echo $menu_on_title;?>
               		</div>
                </section>
                <!-- Main content -->
                <section class="content">
                   <?php echo $content;?>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
           <footer class="main-footer">
              &copy; 2017 <?php echo $Unit;?> - <?php echo $WebName;?> <a href="http://www.damas.or.id/"></a>
                <div class="pull-right hidden-xs">
                    Versi 1.0
                </div>
            </footer>
        </div><!-- ./wrapper -->
        
    <!-- jQuery 2.1.4 
    <script src="<?php echo $theme_path;?>bootstrap/plugins/jQuery/jQuery-2.1.4.min.js"></script>-->
    
    <!-- jQuery UI 1.11.4 -->
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip 
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>-->
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo $theme_path;?>bootstrap/bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?php echo $theme_path;?>bootstrap/plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo $theme_path;?>bootstrap/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="<?php echo $theme_path;?>bootstrap/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/fastclick/fastclick.min.js"></script>
      <!-- TreeView -->
    <script src="<?php echo $theme_path;?>bootstrap/plugins/maxazan-jquery-treegrid/js/jquery.treegrid.js"></script>
	<script src="<?php echo $theme_path;?>bootstrap/plugins/maxazan-jquery-treegrid/js/jquery.treegrid.bootstrap2.js"></script>
    
    
    <!-- AdminLTE App -->
    <script src="<?php echo $theme_path;?>bootstrap/dist/js/app.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="<?php echo $theme_path;?>bootstrap/dist/js/pages/dashboard.js"></script>
    <!-- AdminLTE for demo purposes -->
    
    <script src="<?php echo $theme_path;?>bootstrap/dist/js/demo.js"></script>
  </body>
</html>
