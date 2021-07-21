<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<!-- bootstrap datepicker -->
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
var $j = jQuery.noConflict();
$j(document).ready(function () {
	//alert('cek');
	$j("#form_input_data").on('keydown.autocomplete', '#name', function () {
		//$j(".search-by-anggota").on("keydown.autocomplete", function(e) {
						//	alert("<?php echo $url_jsonData."";?>/list_pegawai");								
			
				$j(this).typeahead({
					ajax: { 
						url: "<?php echo $url_jsonData."";?>/list_equipment?category="+$j("#category").val(),
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
						$j("#equipment_id").val(item.value);
					}
				});
		});
		
		$j("#form_input_data").on('keydown.autocomplete', '#contractor', function () {
		//$j(".search-by-anggota").on("keydown.autocomplete", function(e) {
							//alert("<?php echo $url_jsonData."";?>/list_contractor");								
			
				$j(this).typeahead({
					ajax: { 
						url: "<?php echo $url_jsonData."";?>/list_contractor",
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
						$j("#contractor_id").val(item.value);
					}
				});
		});
 
});
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
        <span class="label" >Kategori</span>
       
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
        <span class="label">Equipment</span>
       
        <input type="text" class="input" name="name" id="name" placeholder="Equipment"  size="35" value="<?php echo $detail->nomor;?>"/>
        <input type="hidden" class="input" name="equipment_id" id="equipment_id"  size="4" value="<?php echo $detail->equipment_id;?>"/>
    </div>
   <div class="row-form">
        <span class="label">Kontraktor</span>
        <?php 
			$nama=$detail->name;
		?>
        <input type="text" class="input" name="contractor" id="contractor" placeholder="contractor"  size="35" value="<?php echo $detail->contractor_name;;?>"/>
        <input type="hidden" class="input" name="contractor_id" id="contractor_id"  size="4" value="<?php echo $detail->partner_id;?>"/>
    </div>
     <!-- <div class="row-form">
                 <span class="label" >Digunakan <small class="wajib"></small></span>
                <span style="display:inline-block">
                <?php
         
           	 	$aktif=$detail->active;//trim($detail->active)==""?0:$detail->active;
          		?>
                <label >  
                	<input type="radio"  name="aktif" value="1"  <?php echo ($aktif=="1")?"checked":"";?>  />
                          Ya</label> 
                <label  style="margin-left:10px;"> 
                	<input type="radio" name="aktif" value="0"  <?php echo ($aktif=="0")?"checked":"";?> />
                          Tidak </label>
                </span>
            </div>
         -->
  
</form>
</div>
