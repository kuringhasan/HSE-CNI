<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery-1.12.1.min.js"></script>
<div class="responsive-form" >
<style>
.responsive-form .label{
	width:150px;
}
</style>
<form action="" method="post" class="form" id="form-backup" name="form-backup"  style="position:absolute;" >

	 <div class="row-form">
        <span class="label">Nama File Target</span>
        <input type="text" name="nama_file" id="nama_file" size="20"  class="input"  />&nbsp;<small>Jika file tidak diisi, nama file otomatis nama database</small>
    </div> 
     <div class="row-form">
        <span class="label"> </span>
        <button type="button" name="btn-backup" id="btn-backup"   class="btn btn-primary btn-xs"   style="right:0px;" ><i class="fa fa-fw fa-file-text"></i>&nbsp;Backup</button>
         <button type="button" name="btn-download" id="btn-download"   class="btn btn-primary btn-xs" value=""   style="right:0px; display:none;" ><i class="fa fa-fw fa-download"></i>&nbsp;Download</button>
         <span id="file-backup"></span>
         <img src="<?php echo $theme_path;?>images/loading50.gif" style="display:none; height:16px; vertical-align:middle; border:0px;" id="loader_backup"/>
    </div> 


</form>

</div>
<iframe name="media-download"  id="media-download"  style="display:none;"></iframe>

<script>

$(document).ready(function () {
	$('#btn-backup').click(function(){
									
		$('#loader_backup').fadeIn();
		$('#btn-download').hide();
		$('#file-backup').hide();
		$.ajax({
            url : '<?php echo $url_backup;?>',
            type : 'POST',
            data : $("#form-backup").serialize(),
            success: function(msg){
				var obj2 = JSON.parse(msg);
				$('#loader_backup').fadeOut();
				if (obj2.sukses==true){
					$('#file-backup').html("Database telah diback-up ");
					$('#btn-download').show();
					$('#btn-download').attr("value",obj2.file);
				}else{
					
				}
				
            }
        });
	});
	$('#btn-download').click(function(){
									 // alert('<?php echo $url_tmp;?>'+$(this).val());
		$('#media-download').attr("src",$(this).val());
	});
   
});
</script>