<!--
<link type="text/css" href="<?php echo $theme_path;?>jquery/theme/jquery.ui.all.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>jquery/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>jquery/ui/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>jquery/ui/jquery.ui.button.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>jquery/ui/jquery.ui.dialog.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>jquery/ui/jquery.ui.position.min.js"></script>
-->
<div id="notice-modal" title="Pesan">
	<p><?php echo $notice;?></p>
</div>
<div class="admin_info">
<form action="<?php echo $url_save; ?>" method="post" >
<input type="hidden" name="_act" value="save" />
<table border="0" cellpadding="3" cellspacing="2" width="100%">
    <tr>
        <td width="150" ><b>Password</b></td>
        <td width="5"><b>:</b></td>
        <td><input type="password" name="password1" size="20"  class="input"  /></td>
    </tr>  
    <tr>
        <td width="150" ><b>Ulang Password</b></td>
        <td width="5"><b>:</b></td>
        <td><input type="password" name="password2" size="20"  class="input"  /></td>
    </tr>  
    <tr>
        <td ></td>
        <td width="5"></td>
        <td><br />
        <button type="submit" id="simpan">Simpan</button> &nbsp;&nbsp;&nbsp;

    </tr>
</table>
</form>
</div>
<script>
	$(function() {

        $("#simpan").button({
			icons: {
                primary: 'ui-icon-disk'
            }
		}).click(function(){
 		});	
   
        <?php
        if(strlen($notice)) {
		?>
			$("#notice-modal").dialog({
				height: 140,
				modal: true,
				buttons: {
					Ok: function() {
						$(this).dialog('close');
					}
				}
			});
			<?php
		}
		?>

     });
</script>