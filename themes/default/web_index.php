<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="RaFee" />
	<title><?php echo $page_title;?></title>
	<link type="text/css" href="<?php echo $theme_path;?>css/style.css" rel="stylesheet" />
     <link type="text/css" href="<?php echo $theme_path;?>css/tabel.css" rel="stylesheet" />
     <link rel="stylesheet" href="<?php echo $theme_path;?>development-bundle/themes/ui-lightness/jquery-ui-1.8.14.custom.css" >
	<link type="text/css" href="<?php echo $theme_path;?>css/superfish.css" rel="stylesheet" />
	<!-- <script type="text/javascript" src="<?php echo $theme_path;?>js/jquery/jquery-1.6.2.min.js"></script>-->
    
    <script type="text/javascript" src="<?php echo $theme_path;?>js/jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery/jquery.bgiframe.min.js"></script>
	<script type="text/javascript" src="<?php echo $theme_path;?>js/superfish.js"></script>
	<script type="text/javascript" src="<?php echo $theme_path;?>js/hoverIntent.js"></script>
	<script type="text/javascript" src="<?php echo $theme_path;?>js/supersubs.js"></script>
	<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
    <link rel="stylesheet" href="<?php echo $theme_path;?>development-bundle/themes/ui-lightness/jquery.ui.all.css">

	<script src="<?php echo $theme_path;?>development-bundle/ui/jquery.ui.core.js"></script>
	<script src="<?php echo $theme_path;?>development-bundle/ui/jquery.ui.widget.js"></script>
	<script src="<?php echo $theme_path;?>development-bundle/ui/jquery.ui.datepicker.js"></script>
    <script src="<?php echo $theme_path;?>development-bundle/ui/i18n/jquery.ui.datepicker-id.js"></script>
<script>



	$(function(){
        $("input[type='text'],input[type='password'], textarea, select").addClass("input").addClass("ui-widget").addClass("ui-widget-content").addClass("ui-corner-all");

	});

</script>

</head>

<body>

<div id="progresstop" style="position:absolute;display:none" class="progresstop">Loading ...</div>

 

<table border="0" cellpadding="0" cellspacing="0" width="100%">

<tr>

	<td id="header">
    <img src="<?php echo $theme_path;?>images/logo_simantap.png"  height="70" alt="-"  style="margin-left:0px;float:left;" />
   <img src="<?php echo $theme_path;?>images/header-right.png"  height="73" alt="-"  style="margin-left:0px;float:right; min-height:50px;" />
		
    </td>

</tr>

<tr>

	<td id="menu">

		<ul class="sf-menu">

			<?php

			function buildMenu($data) {

				while($each = each($data)) {

					?>

					<li>

						<a href="<?php echo $each[1]->PageUrl?>"><?php echo $each[1]->PageTitle?></a>

						<?php

						if(count($each[1]->PageChilds)) {

							?>

							<ul>

								<?php buildMenu($each[1]->PageChilds);?>

							</ul>

							<?php

						}

						?>

					</li>

					<?php

				}

			}
			/*$mem	=new Cache();
			$key_menu	=md5("list_menu_".$_SESSION["framework"]["login_as"]);
			$cek_mem_menu=$mem->get($key_menu);
			$mn=array();
			if($cek_mem_menu){
				$mn=$cek_mem_menu;
			}else{
				$mn= $menus;
				$exp=24*3600;
				$mem->set($key_menu,$mn,$exp);
			}*/
			buildMenu($menus);
//echo $blm;
			?>

		</ul>
	</td>

</tr>

<tr>

	<td>

		<div style="height:45px;" id="content_head">

			<div style="width:50%; font-size:20px;" id="content_title" >	<?php echo $content_title;?></div>
       	 <div style="margin-bottom:3px; right:0px; bottom:0; position:absolute;" id="bottom_head">	
        	<?php echo $menu_on_title;?>
        	</div>
      </div>
<div style="clear:both;"></div>
		<div id="content" style="min-height:400px; width:auto;">

			<?php echo $content;?>

		</div>

	</td>

</tr>

<tr>

	<td>

		<div id="footer"  style="position:relative;">

			<div class="copyright" style="top:5px;">

				&copy; 2015 SIMANTAP<?php //echo $WebID;?> - <?php echo $Unit;?><a href="http://www.damas.or.id/"></a>

			</div>

			<div id="current_user" style="width:25%; margin-right:10px; text-align:right;font: bold 11px Tahoma; right:0px; top:8px;  margin-bottom:2px; position:absolute;" class="copyright">
       <span id="online" style=""></span>&nbsp;<span style=""><?php echo $_SESSION['framework']['current_user']->Name;?>-<?php echo $CurrentUserLevel->LevelName;?></span>
        
		</div>
		</div>

	</td>

</tr>

</table>

<script>
var i=0;
	online();
	
	function online(){
		//alert('<?php echo $url_cekonline;?>');
		i=i+1;
		if (window.XMLHttpRequest){
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{
			// code for IE6, IE5
			xmlhttp =new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				
				//document.getElementById("online").innerHTML=i;
				var status=xmlhttp.responseText;
				var gbr='';
				if(status=='on')
				{
					gbr=i+'<img src="<?php echo $theme_path;?>/images/user_online.png" title="Online" height="12"  style="border:0px;"/>';
				}
				if(status=='idle')
				{
					gbr=i+'<img src="<?php echo $theme_path;?>/images/user_idle.png" title="Tidak ada aktivitas" height="12"  style="border:0px;"/>';
				}
				//alert(i);
				//window.onload = (function(){
					document.getElementById("online").innerHTML = gbr;
				//});
			}
		}
		xmlhttp.open("GET",'<?php echo $url_cekonline;?>');
		xmlhttp.send();
		
		window.setTimeout(online, 2000);

		//setInterval('online();', 20000);
	}
	$(function(){
		$( "#bottom_head" ).buttonset();
		$("ul.sf-menu").supersubs({

            minWidth: 12,

            maxWidth: 27,

            extraWidth: 1

        }).superfish().find("ul").bgIframe({opacity:false});

		$(".button_list a, .button").button();

      
	});
	
</script>

</body>

</html>