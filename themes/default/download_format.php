	<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery-1.6.2.min.js"></script>
<link type="text/css" href="<?php echo $theme_path;?>css/form_search.css" rel="stylesheet" />
<script>
$(function() {

		//$("#btnDownloadFormat").button();
		$("#btnDownloadFormat").click(function(){
			alert('cek '+'<?php echo $url_download;?>?category='+$("#category_format").val());
				$("#download_media").attr("src", '<?php echo $url_download;?>?category='+$("#category_format").val());
		});
});
</script>
<div class="search-result" style="padding:8px 5px 8px 5px;">
<div class="admin_info" style="padding:5px 5px 5px 5px; width:99%">
<table style="width:99%;">
<tr>
<td style="width:50%">
	Donwload Format &nbsp;
    <select name="format" id="category_format">
   		<option value="">---</option>
       <option value="sapi">Data Sapi</option>
                   
    </select> &nbsp;  <button id="btnDownloadFormat" class="btn btn-primary btn-xs"  type="button"><i class="fa fa-fw fa-download"></i> Download</button>

</td>
<td style="width:49%">
<form class="form-horizontal" action="<?php echo $url_upload;?>" method="post" name="formimport" enctype="multipart/form-data" >
<table cellpadding="2" cellspacing="2" border="0" >
	<tr>
        <td style="padding:2px 4px 3px 3px;">File Excel</td>
        <td><input type="file" name="nfile"  /> </td>
    </tr>
    <tr>
        <td></td>
        <td style="padding-top:5px;"><button type="submit" name="upload" value="upload" class="btn btn-primary btn-xs" id="upload"><i class="fa fa-fw fa-upload"></i> Upload</button></td>
    </tr>
</table>

</form>

</td>
</tr>
</table>
</div>
<iframe id="download_media" name="download_media" style="height:400px;width:400px;" ></iframe>
</div>