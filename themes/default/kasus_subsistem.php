<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $theme_path;?>css/form_search.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery-1.12.1.min.js"></script>
<script>
$(document).ready(function () {
  
	$('#TampilkanData').click(function(){
		loaddata($("#form_cari").serialize()+'&'+$("#form-list-data").serialize());
		//jumlah_data($("#form_cari").serialize()+'&'+$("#form-list-data").serialize());
	});
	loaddata($("#form_cari").serialize()+'&'+$("#form-list-data").serialize());
});
function loaddata(data)
{
	$("#tabeldata").css("text-align","center");
	$("#loader_listdata").fadeIn();
	var url_list='<?php echo $url_listdata; ?>';
	
		 $.ajax({
            url : url_list,
            type : 'POST',
            data : data,
            success: function(msg){
                $('#tabeldata').html(msg);
                 $("#loader_listdata").fadeOut();
				 jumlah_data($("#form_cari").serialize()+'&'+$("#form-list-data").serialize());
            }
        });
       return false;
} 
     	
function klikloaddata(){
	//alert($("#form-list-data").serialize());
		 loaddata($("#form_cari").serialize()+'&'+$("#form-list-data").serialize());
		 //jumlah_data($("#form_cari").serialize()+'&'+$("#form-list-data").serialize());
	 }
function jumlah_data(data)
{
	
	$("#media-navigasi").html('<img src="<?php echo $theme_path."/images/h-loader.gif";?>" style="border: none; margin-top:5px;opacity: 0.4;filter: alpha(opacity=40); height:8px;"  class="spinner_jumlah"/>');
	$("#hasil_jumlah_harga").html('<img src="<?php echo $theme_path."/images/h-loader.gif";?>" style="border: none; margin-top:5px;opacity: 0.4;filter: alpha(opacity=40); height:8px;"  class="spinner_jumlah"/>');
		var url_list='<?php echo $url_listdata."/jumlah_data";?>';
	
		 $.ajax({
            url : url_list,
            type : 'POST',
            data : data,
            success: function(msg){
				var obj2 = JSON.parse(msg);
				 $(".spinner_jumlah").fadeOut();
				$("#current_page").val(obj2.current_page);
				 $("#media-navigasi").html(obj2.navigasi);
				 $("#hasil_jumlah_harga").html(obj2.total_harga);
				//return true;
				 
				 
            }
        });
       
} 
</script>
<style>


#loader_listdata{
	position: fixed;
  top: 50%;
  left: 50%;
  /*margin-top: -50px;
  margin-left: -100px;*/
  	filter: alpha(opacity=50); /* internet explorer */
	-khtml-opacity: 0.5;      /* khtml, old safari */
	-moz-opacity: 0.3;       /* mozilla, netscape */
	opacity: 0.5;    
}

</style>
<?php
?>



<div class="search-result">
<div class="responsive-form" style="width:100%;margin-top:4px;">
 <form id="form_cari"  method="post" >

 	 
       

        
       <span class="label" style="min-width:70px;width:auto;font-weight:normal;padding-top:3px;margin-left:4px">Kode/Nama </span>
         <input type="text" class="input" name="crNama" id="crNama" placeholder="Nama"  size="20"/>
        
        
  <div style="float:right;border:1px solid #FFF">	<button type="button" class="btn btn-primary btn-xs" id="TampilkanData" ><i class="fa fa-search"></i>&nbsp;Cari</button></div>
</form>

</div>
</div>
<div class="search-result">
<img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:34px; vertical-align:middle; border:0px;"  alt=""  id="loader_listdata"/>
    <div id="tabeldata" style="min-height:390px; width:auto;">
       
    </div>      
</div>

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Form Data Pegawai</h4>
        </div>
        <div class="modal-body">
           
        </div>
      <div class="modal-footer">
    
       
      </div>
    </div>
  </div>
</div>