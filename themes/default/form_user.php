<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
//var $j = jQuery.noConflict();
$(document).ready(function () {
	$("#password_hash").val('');
	$("#form_input_data").on('keydown.autocomplete', '#pegawai', function () {
		//$j(".search-by-anggota").on("keydown.autocomplete", function(e) {
							//alert("<?php echo $url_jsonData."";?>/list_pegawai");								
			
				$(this).typeahead({
					ajax: { 
						url: "<?php echo $url_jsonData."";?>/list_pegawai",
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
						//var v =item.value;  // getting search input value
						//table.columns(0).search(v).draw();
						
						get_pegawai(item.value);
						$("#pegawai_id").val(item.value);
						//$("#html-path").html(item.text);
					}
				});
		});
		$("#form_input_data").on('change', '.level_id', function () {
			//alert($(this).val());
			var elem=$(this).attr("id");
			var indeks=elem.replace("level_id","");
			
			var nilai=$(this).val();
			get_level(nilai,indeks);
			//get_level($(this).val());
			/* var y = $(this);
			 var x=y.prop('selectedIndex')
			 alert(x);
			 var opt = y.options
			 alert(opt[x].text);
			  //var opt = $(this).options;
          // alert(y[opt].index);*/
			
		});
		
 
});
function get_level(level_id,indeks){
	//alert("<?php echo $url_getdata."";?>/get_level/"+level_id);
	
	$.ajax({
			type:"POST",
			url: "<?php echo $url_getdata."";?>/get_level/"+level_id,
			//data: $("#form_input_data").serialize(),
			success: function(data, status) {
				var obj2 = JSON.parse(data);
				$("#label-refname"+indeks).html(obj2.RefName);
				
				
			}
		});	
}
function get_pegawai(pegawai_id){
	alert("<?php echo $url_getdata."";?>/get_pegawai_user/"+pegawai_id);
	$("#label_username").html("");
	$("#username").val('').show();
	$("#password").show();
	$("#label_password").html("");
	$("#password_hash").val('').show();
	$("#retype-password").show();
	$.ajax({
			type:"POST",
			url: "<?php echo $url_getdata."";?>/get_pegawai_user/"+pegawai_id,
			//data: $("#form_input_data").serialize(),
			success: function(data, status) {
				var obj2 = JSON.parse(data);
				
				//$("#html-privileges").html(data);
				if(obj2.username!=="" && obj2.username!==null){
					//alert(obj2.username);
					$("#label_username").html(": "+obj2.username);
					$("#username").val(obj2.username).hide();
					if(obj2.password!=="" && obj2.password!==null){
						$("#password").hide();
						$("#label_password").html(": Password diambil dari user ERP");
						$("#password_hash").val(obj2.password).hide();
						$("#retype-password").hide();
					}
				}
				
			}
		});	
}
function addchild(){
	     
		var maxVal = 0;
		//alert($j( ".level_item" ));
		if (typeof $j( ".level_item" ) !== 'undefined') {
			$j( ".level_item" ).each(function( index ) {
			   /*if(obat_id===$('#obat_id'+index).val()){
				   add_child=false;
				   pesan_error = 'Obat '+obat+' sudah diinput';
			   }*/
			   maxVal = Math.max(maxVal , parseInt($(this).val()));
			   
			});
		}
		//alert("cek");
		indeks=maxVal+1;
		$j("#media-test").html(indeks);
		var html="<tr class=\"list-level\" id=\"list-level"+indeks+"\"><td ><select name=\"level["+indeks+"][level_id]\"  class=\"input level_id\" id=\"level_id"+indeks+"\" ><option value=\"\">-Level-</option><?php $List=$list_level; while($data = each($List)) { ?><option value=\"<?php echo $data['key'];?>\" <?php echo $level_id==$data['key']?"selected":""; ?>><?php echo $data['value'];?></option><?php }?></td><td><span id=\"label-refname"+indeks+"\"></span></td><td><input type=\"text\" class=\"input list-item\" name=\"level["+indeks+"][ref_id]\" /></td><td><a class=\"btn btn-xs\" href=\"javascript:return false;\" onclick=\"removeitem('list_item_level','list-level"+indeks+"');\" ><i class=\"fa fa-remove\"></i></a><input type=\"hidden\"  class=\"level_item\" name=\"level_item["+indeks+"]\" size=\"2\"  value=\""+indeks+"\" /></td></tr>";
		
		var body_cont=$j("#list_item_level").html();
		body_cont=body_cont.trim();
		
		if(body_cont==""){
			$j("#list_item_level").html(html);
		}else{
			$j(".list-level").parent().closest('tbody').append(html);
		}
			
	
}


function removeitem(id_parent,id_elem)
 {
	var list = document.getElementById(id_parent);   // Get the <ul> element with id="myList"
	 var elem_child = document.getElementById(id_elem);
	list.removeChild(elem_child);  
 }
function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		for (var key in errors){
			$("#"+key).addClass("error");
			$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			
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
.list-item{
	width:100%;
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
//echo "<pre>";print_r($user);echo "</pre>";
?>
<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
 	
    <div class="row-form">
        <span class="label">Nama Lengkap</span>
       
        <input type="text" name="full_name" id="full_name"  class="input"  value="<?php echo $user->Name;?>" placeholder="Nama Lengkap" size="45"/>
        
    </div>
      <?php
	if($action=="edit"){
	?>
   <div class="row-form">
        <span class="label">Username</span>
        <?php echo $user->Username;?>
       
    </div>
     <?php
	}
	if($action=="add"){
	?>
   <div class="row-form">
        <span class="label">Username</span>
        <?php 
			$Username=$detail->Username;
		?>
        <span id="label_username"></span>
        <input type="text" class="input" name="username" id="username" placeholder="Username"  size="35" value="<?php echo $Username;?>"/>
        
    </div>
   
    
    <div class="row-form">
        <span class="label">Password</span>
        <?php 
			$nama=$detail->name;
		?>
        <span id="label_password"></span>
        <input type="password" class="input" name="password" id="password" size="12" value="<?php echo $nama;?>"/>
       
        
    </div>
    
     <div class="row-form" id="retype-password">
        <span class="label">Re-type Password</span>
        <?php 
			$nama=$detail->name;
		?>
        <input type="password" class="input" name="password2" id="password2" size="12" value="<?php echo $nama;?>"/>
        
    </div>
   <?php
	}//end if $action
	?>
     <div class="row-form">
        <span class="label">E-Mail</span>
      
        <input type="text" class="input" name="email" id="email" placeholder="E-Mail"  size="35" value="<?php echo $user->Email;?>"/>
        
    </div>
     <div class="row-form">
        <span class="label">No. HP</span>
    
        <input type="text" class="input" name="hp" id="hp" placeholder="No. HP"  size="15" value="<?php echo $user->NoHP;?>"/>
        
    </div>
      <div class="row-form">
      <table class="table table-bordered table-hover dataTable" id="tabel-level"  style="width:100%">
      <thead>
      <tr>
      	<th >Role</th>
        <th style="width:160px">Ref Nama</th>
         <th style="width:60px">Ref ID</th>
         <th style="width:25px"></th>
      </tr>
      </thead>
      <tbody id="list_item_level">
      <?php
	  if(isset($usel_level) and !empty($usel_level)){
		  foreach($usel_level as $key=>$value){
		  ?>
		   <tr class="list-level" id="list-level0">
			<td >
			<select name="level[0][level_id]"  class="input level_id" id="level_id0" >
				  <?php
				echo '<option value="">-Role-</option>';
				$List=$list_level;
				while($data = each($List)) {
				   ?>
			  <option value="<?php echo $data['key'];?>" <?php echo $value->LevelID==$data['key']?"selected":""; ?>><?php echo $data['value'];?></option>
			  <?php
			  
				}
			 ?>
			</select></td>
			<td><span id="label-refname0"><?php echo $value->RefName;?></span></td>
			 <td> <input type="text" class="input list-item" name="level[0][ref_id]"  size="3" value="<?php echo $value->RefID;?>"/></td>
			 <td style="text-align:center"> <a class="btn btn-xs" href="javascript:return false;" onclick="removeitem('list_item_level','list-level0');" ><i class="fa fa-remove"></i></a><input type="hidden" class="level_item" name="level_item[0]" size="2"  value="0" /></td>
		  </tr>
		  <?php
		  }//end foreach
	  }
	  ?>
      </tbody>
      </table>
      <a class="btn-xs" href="javascript:return false;" onclick="addchild();" >Add Level</a>
      </div>
  
</form>
</div>
