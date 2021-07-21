<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<!-- bootstrap datepicker -->
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
var $m = jQuery.noConflict();
$m(document).ready(function () {
	//alert('cek');
	
		
		$m("#form_input_data").on('keydown.autocomplete', '#vendor', function () {
		//$j(".search-by-anggota").on("keydown.autocomplete", function(e) {
							//alert("<?php echo $url_jsonData."";?>/list_partner");								
			
				$m(this).typeahead({
					ajax: { 
						url: "<?php echo $url_jsonData."";?>/list_partner",
						timeout: 500,
						displayField: "Lengkap",
						valueField :'ID',
						triggerLength: 1,
						method: "POST",
						loadingClass: "loading-circle",
						preDispatch: function (query) {
						//	alert(query);
							//showLoadingMask(true);
							return {
								search: query
							}
						},
						preProcess: function (data) {
							//showLoadingMask(false);
							//alert(data.Lengkap);
							console.log(data);
							/*if (data.success === false) {
								// Hide the list, there was some error
								return false;
							}*/
							// We good!
							//return data.mylist;
							return data;
						}
					},
				   onSelect: function(item) {
						//alert(item.value);
						//var i =$j(this).attr('data-column');  // getting column index
						var v =item.value;  // getting search input value
						//table.columns(0).search(v).draw();
						$m("#vendor_id").val(item.value);
					}
				});
		});
 
});
function errorForm(msj_obj){
			 if (jQuery.isEmptyObject(msj_obj)==false)
			 {
				 var errors=msj_obj;
				 $m('.row-error').remove();
				for (var key in errors){
					$m("#"+key).addClass("error");
				
					$m("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
					
					
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
.wajib{
	color:#F00;
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
echo "<pre>";print_r($detail);echo "</pre>";
?>
<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
 	
     <div class="row-form">
        <span class="label" >Kategori <small class="wajib">*</small></span>
       
        <select name="category" id="category" class="input" >
             <?php
            echo '<option value="">--Kategori--</option>';
            
                $List=$list_category;
                while($data = each($List)) {
					
                    $category=isset($_POST['category'])?$_POST['category']:$detail->category_code;
                   ?>
                    <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$category?"selected":""; ?> >
                 <?php echo $data['value'];?></option>
                 <?php
                }
            ?>
            </select>
    </div>
    <div class="row-form">
        <span class="label">Nomor Body<small class="wajib">*</small></span>
       
        <input type="text" class="input" name="nomor" id="nomor" placeholder="Nomor"  size="35" value="<?php echo $detail->nomor;?>"/>
        
    </div>
     <div class="row-form">
        <span class="label">Nama</span>
       
        <input type="text" class="input" name="name" id="name" placeholder="Nama"  size="35" value="<?php echo $detail->name;?>"/>
        
    </div>
      <div class="row-form">
        <span class="label">Merk</span>
       
        <input type="text" class="input" name="merk" id="merk" placeholder="Merk"  size="10" value="<?php echo $detail->merk;?>"/>
       
    </div>
     <div class="row-form">
        <span class="label">Type</span>
       <input type="text" class="input" name="type" id="type" placeholder="Type"  size="10" value="<?php echo $detail->type;?>"/>
    </div>
     <div class="row-form">
        <span class="label">Load Factor</span>
       <input type="text" class="input" name="load_factor" id="load_factor"  size="4" value="<?php echo $detail->load_factor;?>"/>
    </div>
    <div class="row-form">
        <span class="label">Remarks</span>
       <textarea  class="input" name="remarks" id="remarks" placeholder="Remarks" cols="25" ><?php echo $detail->remarks;?></textarea>
    </div>
   <div class="row-form">
        <span class="label">Vendor</span>
        
        <input type="text" class="input" name="vendor" id="vendor" placeholder="Vendor"  size="35" value="<?php echo $detail->vendor_name;;?>"/>
        <input type="hidden" class="input" name="vendor_id" id="vendor_id"  size="4" value="<?php echo $detail->vendor_id;?>"/>
    </div>
    <div class="row-form">
         <span class="label" >Tidak Digunakan <small class="wajib"></small></span>
        <span style="display:inline-block">
       
        <input type="checkbox"  name="depricated" id="depricated"    <?php echo $detail->depricated=="1"?"checked=\"checked\"":""; ?>/>
           
        </span>
    </div>
        
  
</form>
</div>
