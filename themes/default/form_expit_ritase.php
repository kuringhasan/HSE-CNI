<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
var $m = jQuery.noConflict();
$m(document).ready(function () {
	
	$m("#frm_tanggal").datepicker({
		 format: 'dd/mm/yyyy',
		 autoclose: true
	});
	$m("#truck").keyup(function(){
			var dt=$m(this).val()
			if (dt.length==0){
				$m("#truck_id").val('');
			}
	}); 
	$m("#form_input_ritase").on('keydown.autocomplete', '#truck', function () {
		//alert("<?php echo $url_jsonData."";?>/list_truck");
		$m(this).typeahead({
			ajax: { 
				url: "<?php echo $url_jsonData."";?>/list_truck",
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
			   //console.log(item);
				
				
				//get_pegawai(item.value);
				$m("#truck_id").val(item.value);
				//$("#html-path").html(item.text);
			}
		});
	});

	comboAjax('<?php echo $url_comboAjax;?>/list_pit','<?php echo $detail->contractor_id;?>','pit_id','<?php echo $detail->lokasi_pit_id;?>','','loaderPIT');	
	$m("#kontraktor").change(function(){		
		var parentkode=	$m(this).val();	
		comboAjax('<?php echo $url_comboAjax;?>/list_pit',parentkode,'pit_id','','','loaderPIT');	
	});
	comboAjax('<?php echo $url_comboAjax;?>/list_lokasi_dome','<?php echo $detail->tujuan_pengangkutan;?>','lokasi_dome','<?php echo $detail->location_id;?>','','loaderLokasiDome');	 
	$m("#tujuan").change(function(){		
		var parentkode=	$m(this).val();
		if(	parentkode=="ETO" || parentkode=="EFO"){ 
			comboAjax('<?php echo $url_comboAjax;?>/list_lokasi_dome',parentkode,'lokasi_dome','','','loaderLokasiDome');
			$m("#row_lokasi_dome").show();	
			$m("#row_dome_id").show();
			$m("#row_barge_id").hide();	
		}
		if(	parentkode=="BRG" ){ 
			$m("#row_lokasi_dome").hide();	
			$m("#row_dome_id").hide();
			$m("#row_barge_id").show();	
		}
	}); 
	
	comboAjax('<?php echo $url_comboAjax;?>/list_domes','<?php echo $detail->location_id;?>','dome_id','<?php echo $detail->dome_id;?>','','loaderDome');	 
	$m("#lokasi_dome").change(function(){		
		var parentkode=	$m(this).val();	
		comboAjax('<?php echo $url_comboAjax;?>/list_domes',parentkode,'dome_id','','','loaderDome');	
	}); 
	
	
});

function comboAjax(url_cmb,parentkode,idTarget,nilai,other_parameters,idloader, idframe)
	{
		//alert(url_cmb+'?nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters);
		$m("#"+idTarget).hide();
		$m("#"+idloader).show();
		$m.ajax({
			   type:'POST',
			   dataType:'html',
			   url:url_cmb,
			   data:'nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters,
			   success:function(msg){
				  var obj=JSON.parse(msg);
				   $m("#"+idloader).hide();
				   if (obj.kosong==false)
				   {	
					  $m("#"+idTarget).fadeIn();
					  $m("#"+idTarget).empty().append(obj.html);
				   }else{
					  // $("#"+idframe).fadeOut();
					   $m("#"+idTarget).fadeIn();
					  $m("#"+idTarget).empty().append(obj.html);
				   }
				   
			   }///akhisr sukses
		   }); //akhir $.ajax	
	}

function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		 $j('.row-error').remove();
		for (var key in errors){
			$("#"+key).addClass("error");
			alert(key);
			if(key=='verifikasi'){
				$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}else{
				$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}
			
		}
	 }
}
function ubah_angka(dta){
		var harga_satuan= convert2angka(dta.value);
		var new_value=accounting.formatNumber(harga_satuan, 2,".",",");
		var value_arr=new_value.split(",");
		
		if(value_arr[1]=="00"){
			dta.value=value_arr[0];
		}else{
			dta.value=new_value;
		}
		//dta.value=new_value[0];//accounting.formatNumber(harga_satuan, 2,".",",");
		
	}
function convert2angka(nilai){
	// asumsi inputan : titik=ribuan, koma=desimal
	var hasil="";
	if(nilai !== ''){
		var str=String(nilai);
		var posisi_koma		=str.indexOf(',');
		var posisi_titik	=str.indexOf('.');
		if(posisi_koma>=0 && posisi_titik>=0){
			
				hasil = str.split('.').join('');
				//hasil	=str.replace(".","")
				hasil = hasil.split(',').join('.');
				//hasil	= hasil.replace(",",".")
			
		}else if(posisi_koma>=0 && posisi_titik==-1){
			//hasil	= str.replace(",",".")
			hasil = str.split(',').join('.');
		}else if(posisi_koma==-1 && posisi_titik>=0){
			hasil = str.split('.').join('');
			//hasil	= str.replace(".","")
		}else{
			hasil = str;
		}
	}else{
		hasil='0';
	}
	
	return hasil;//outpot, tanpa tanda komo, penanda komo adalah titik
}	
</script>
<style>
.tbl tr td{
	font-size:13px;
}

input, select{
	font-weight:normal;
	margin-bottom:0px;
}
.form-group{
	margin:2px 2px 2px 2px;
	 position: relative;
}
.label_typeahead{
	width:300px;
	height:auto;
	white-space:pre-line;
	background-color:#06F
	margin:0px 0px 0px 0px;
}
.wajib{
	color:#F00;
}
.catatan{
	display:inline-block;
	font-size:0.7em;
}
.error{
	border:1px solid #F99;
	background-color:#FFC;
}
.lbl_error{
	color:#F00;
	padding-left:154px;
	margin-top:-5px;
	font-size:11px;
	display:block;
	font-style:italic;
	margin-bottom:3px;
}
.cat-tps, .cat-jaringan{
	display:none;
}
@media screen and (max-width: 500px) {
	.label{
		width:100%;
	}
	.lbl_error{
		padding-left:0px;
		
	}
}
</style>
<?php // echo "<pre>";print_r($detail);echo "</pre>";?>
<div class="responsive-form" >
<form action="" method="post" name="form_input_ritase"  id="form_input_ritase">
	 <div class="row-form"><span class="label" >Kontraktor</span>
        <select name="kontraktor" id="kontraktor"  class="input" style=";">
          <?php
                echo '<option value="">--Kontraktor--</option>';
                $List=$list_kontraktor;
                while($data = each($List)) {
                    $kontraktor=isset($_POST['kontraktor'])?$_POST['kontraktor']:$detail->partner_id;
                   ?>
          <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$detail->contractor_id?"selected":""; ?> > <?php echo $data['value'];?></option>
          <?php
               }
                ?>
          </select>
          
           <input type="hidden" class="input" name="expit_id" id="expit_id" size="3" value="<?php echo $detail->transit_ore_id;?>"/> 
    </div>
  <div class="row-form">
        <span class="label">Tanggal</span>
         <?php $tanggal=isset($_POST['frm_tanggal'])?$_POST['frm_tanggal']:$detail->tgl;?>
        <input type="text" class="input" name="frm_tanggal" id="frm_tanggal" size="12" value="<?php echo $tanggal;?>"/> 
   </div>
   <div class="row-form">
            <span class="label" >Shift <small class="wajib">*</small></span>
         
          <select  class="input" name="shift_id" id="shift_id"  style="width:auto"   >
             <?php
                    echo '<option value="">-shift-</option>';
                    $List=$list_shift;
                    while($data = each($List)) {
                       ?>
                <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$detail->shift?"selected":""; ?> >
                  <?php echo $data['value'];?></option>
                <?php
                    }
                 ?>
            </select>

        </div>
     
        <div class="row-form">
				  <span class="label">PIT  </span>
				  <select name="pit_id" id="pit_id" class="input">
				 <?php
				echo '<option value="">--PIT--</option>';
				
					
				?>
				</select>
			   <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderPIT"/>
			   </div>
      <div class="row-form">
        <span class="label">Dump Truck</span>
         <?php $truck_id=isset($_POST['truck_id'])?$_POST['truck_id']:$detail->equipment_id;?>
        <input type="hidden" class="input" name="truck_id" id="truck_id" size="4" value="<?php echo $truck_id;?>"/> 
        <input type="text" class="input" name="truck" id="truck" size="25" value="<?php echo $detail->no_dump_truck;?>" autocomplete="off"/> 
    </div>
    <div class="row-form">
        <span class="label" >Tujuan <small class="wajib">*</small></span>
     
      <select  class="input" name="tujuan" id="tujuan"  style="width:auto"   >
         <option value="">--</option>
            <option value="BRG"  <?php echo "BRG"==$detail->tujuan_pengangkutan?"selected":""; ?> >Barge</option>
            <option value="EFO"  <?php echo "EFO"==$detail->tujuan_pengangkutan?"selected":""; ?> >EFO</option>
            <option value="ETO"  <?php echo "ETO"==$detail->tujuan_pengangkutan?"selected":""; ?> >ETO</option>
            
        </select>

     </div>   
     <div class="row-form" id="row_lokasi_dome">
          <span class="label">Lokasi Dome  </span>
          <select name="lokasi_dome" id="lokasi_dome" class="input">
			 <?php
            echo '<option value="">--Lokasi Dome--</option>';
            
                
            ?>
            </select>
           <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderLokasiDome"/>
       </div>
      <div class="row-form" id="row_dome_id">
          <span class="label">Dome  </span>
          <select name="dome_id" id="dome_id" class="input">
			 <?php
            echo '<option value="">--Dome--</option>';
            
                
            ?>
            </select>
           <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderDome"/>
       </div>
       <div class="row-form" id="row_barge_id">
          <span class="label">Barge  </span>
          <select name="barge_id" id="barge_id" class="input" style="width:auto" >
			 <?php
                    echo '<option value="">-barge-</option>';
                    $List=$list_barges;
                    while($data = each($List)) {
                       ?>
                <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$detail->barge_id?"selected":""; ?> >
                  <?php echo $data['value'];?></option>
                <?php
                    }
                 ?>
            </select>
          
       </div>
     <div class="row-form">
        <span class="label">Ritase</span>
         <?php $ritase=isset($_POST['ritase'])?$_POST['ritase']:$detail->ritase;?>
        <input type="text" class="input" name="ritase" id="ritase" size="4" value="<?php echo $ritase;?>"/>&nbsp;rit
   </div>          
   <!--  <div class="row-form">
        <span class="label">Quantity</span>
         <?php $frm_qty=isset($_POST['frm_qty'])?$_POST['frm_qty']:$detail->quantity;?>
        <input type="text" class="input" name="frm_qty" id="frm_qty" size="10" value="<?php echo $frm_qty;?>"/>&nbsp; MT
   </div>-->
   
            
</form>
 </div>

