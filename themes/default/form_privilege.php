<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
//var $j = jQuery.noConflict();
$(document).ready(function () {
	$("#form_input_data").on('keydown.autocomplete', '#page_name', function () {
		//$j(".search-by-anggota").on("keydown.autocomplete", function(e) {
							//alert("<?php echo $url_jsonData."";?>/pagelist");								
			
				$(this).typeahead({
					ajax: { 
						url: "<?php echo $url_jsonData."";?>/pagelist",
						timeout: 500,
						displayField: "Lengkap",
						valueField :'ID',
						triggerLength: 2,
						method: "POST",
						loadingClass: "loading-circle",
						preDispatch: function (query) {
							
							//showLoadingMask(true);
							return {
								search: query
							}
						},
						preProcess: function (data) {
							//showLoadingMask(false);
							console.log(data);
							
							if (data.success === false) {
								// Hide the list, there was some error
								return false;
							}
							// We good!
							//return data.mylist;
							return data;
						}
					},
				   onSelect: function(item) {
					  // console.log(item);
						//alert(item.value);
						//var i =$j(this).attr('data-column');  // getting column index
						var v =item.value;  // getting search input value
						//table.columns(0).search(v).draw();
						var lvl_id=$('#level_id').val();
						get_methode(item.value,lvl_id);
						$("#page_id").val(item.value);
						$("#html-path").html(item.text);
					}
				});
		});
		$('#level_id').on( 'change', function () {   // for text boxes
			
			get_methode($("#page_id").val(),this.value);
		} );
 
});
function get_methode(page_id,level_id){
	//alert('page :'+page_id+' lvl : '+level_id);
	$.ajax({
			type:"POST",
			url: "<?php echo $url_getmethode."";?>/"+page_id,
			data: $("#form_input_data").serialize(),
			success: function(data, status) {
				$("#html-privileges").html(data);
				
			}
		});	
}
function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		for (var key in errors){
			$("#"+key).addClass("error");
			$("#err_"+key).html(errors[key]);
			$("#err_"+key).addClass("lbl_error");
			$("#err_"+key).show();
		}
	 }
}
    function setTextField(ddl) {
        document.getElementById('tipe_text').value = ddl.options[ddl.selectedIndex].text;
    }
</script>
<style>
.responsive-form #frmBulanLahir{
	margin-left:5px;
}
.responsive-form #frmTahunLahir{
	margin-left:5px;
}
.dropdown-menu li{
	width:350px;
	border-bottom:1px solid #999;
	background-color:#ddd;
}

@media screen and (max-width: 500px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
}
@media screen and (max-width: 320px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
}
</style>
<?php
//echo "<pre>";print_r($detail);echo "</pre>";
?>
<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
 	
    <div class="row-form">
        <span class="label">Page</span>
       
        <input type="text" name="page_name" id="page_name"  value="<?php echo $detail->PageID;?>"  size="35"/>
         <input type="hidden" name="page_id" id="page_id" value="<?php echo $detail->PageID;?>"  size="4"/>
    </div>
  
     <div class="row-form">
        <span class="label" >Path</span>
       <?php 
			echo $detail->PagePath;
		?>
        <span style="display:inline-block" id="html-path"></span>
    </div>
    <div class="row-form">
        <span class="label">Level</span>
        
         <?php $level_id=isset($_POST['level_id'])?$_POST['level_id']:$detail->LevelID;?>
        <select name="level_id" data-column="0" class="input level_id" id="level_id" >
			  <?php
            echo '<option value="">-Level-</option>';
            $List=$list_level;
            while($data = each($List)) {
               ?>
          <option value="<?php echo $data['key'];?>" <?php echo $level_id==$data['key']?"selected":""; ?>><?php echo $data['value'];?></option>
          <?php
          
            }
         ?>
        </select>
    </div>
     <div class="row-form">
                 <span class="label" >Privilege <small class="wajib"></small></span>
                <span style="display:inline-block" id="html-privileges">
                <?php 
					echo $detail->HtmlPrivileges;
				?>
                </span>
            </div>
  
</form>
</div>
