<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $theme_path;?>css/form_search.css" rel="stylesheet" />
<script>
$(function() {

		$("#Upload").button();
		/*$("#Upload").click(function(){
				$.ajax({
                		 	type	:'POST',
                			dataType:'html',
                			url		:urla,
                			data	:'aksi='+aksi+'&'+$("#frmBA").serialize(),
                			success	:function(msg){
                			   $("#daftar-ruang").html("ek");
                			
                			}
        		    });
			   
		   });
		});*/
});
</script>
<div class="search-result" style="padding:8px 5px 8px 5px;">
<div style="border:1px solid #0CF;background-color:#FFC;padding:10px 10px 5px 10px;font-size:12px;margin-bottom:10px;">
<ol>
<li>File yang diupload adalah file excel MCM Virtual Account yang langsung di download dari CMS</li>
<li>Tidak boleh merubah apapun file MCM</li>
</ol>
</div>     

     

<?php
     if ($_POST['Upload']){
		 echo "<div class='admin_info' style='padding:5px 5px 5px 5px;'>";
		echo "<b>".$Notice."</b><br>";
		echo "<font color='red'>".$nmFile."</font>";
		echo "</font>";
	} else
	{
		formuplod($url_upload);
	}
	

	
 function formuplod($url_action){
	
?>
<div class="responsive-form" style="width:100%" >
<form class="responsive-form" name="form1" method="post" enctype="multipart/form-data" action="<?php echo $url_action;?>">
<div class="row-form">
        <span class="label" >File MCM </span>  <input name="file_excel" type="file" id="file_excel" class="input" style="border:0;">
</div>
<div class="row-form" style="margin-top:8px;">
        <span class="label" >&nbsp;</span> 
			  <button name="Upload" type="submit" id="Upload" value="Upload" class="btn btn-primary btn-xs" ><i class="fa fa-fw fa-upload"></i>&nbsp;Upload</button>
</div>
</form>
</div>

<?php 
}
?>
</div>
<div id="konfirmupload">
</div>