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

	
	comboAjax('<?php echo $url_comboAjax;?>/list_lokasi_dome','<?php echo $detail->eto_efo;?>','lokasi_dome','<?php echo $detail->location_id;?>','','loaderLokasiDome');	 
	$m("#asal").change(function(){		
		var parentkode=	$m(this).val();
		comboAjax('<?php echo $url_comboAjax;?>/list_lokasi_dome',parentkode,'lokasi_dome','','','loaderLokasiDome');
		$m("#row_lokasi_dome").show();	
		$m("#row_dome_id").show();
		
	}); 
	
	comboAjax('<?php echo $url_comboAjax;?>/list_domes','<?php echo $detail->location_id;?>','dome_asal','<?php echo $detail->dome_asal;?>','','loaderDome');	 
	$m("#lokasi_dome").change(function(){		
		var parentkode=	$m(this).val();	
		comboAjax('<?php echo $url_comboAjax;?>/list_domes',parentkode,'dome_asal','','','loaderDome');	
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
<div class="box box-primary" style="border:1px solid #CCC">
   
    <div class="box-body border-radius" style="">          
        <div class="responsive-form" >
        <form action="" method="post" name="form_input_ritase"  id="form_input_ritase">
        <div class="row-form">
            <span class="label">Tahun</span>
             <?php $tahun=isset($_POST['tahun'])?$_POST['tahun']:$detail->tahun;?>
            <input type="text" class="input" name="tahun" id="tahun" size="5" value="<?php echo $tahun;?>"/> 
       </div>
         <div class="row-form"><span class="label" >Kontraktor</span>
            <select name="contractor_id" id="contractor_id"  class="input" style=";">
              <?php
                    echo '<option value="">--Kontraktor--</option>';
                    $List=$list_contractor;
                    while($data = each($List)) {
                       
                       ?>
              <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$detail->contractor_id?"selected":""; ?> > <?php echo $data['value'];?></option>
              <?php
                   }
                    ?>
              </select>
              
        </div>
      
      <div class="row-form">
            <span class="label">Target Production</span>
             <?php $target_production=isset($_POST['target_production'])?$_POST['target_production']:$detail->target_production;?>
            <input type="text" class="input" name="target_production" id="target_production" size="15" value="<?php echo $target_production;?>"/> 
       </div>
    	<div class="row-form">
          <span class="label">Catatan <small class="wajib"></small></span>
          <textarea name="note" cols="35" rows="2" id="note"  class="input"  ><?php echo $detail->note;?></textarea>	
       </div>
       <div class="row-form">
          <?php echo $TombolGenerate;?>	
       </div>
       
                
        </form>
         </div>
         
         
         <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#plan_daily" data-toggle="tab">Plan Daily</a></li>
              <li><a href="#plan_weekly" data-toggle="tab">Plan Weekly</a></li>
              <li><a href="#plan_monthly" data-toggle="tab">Plan Monthly</a></li>
            </ul>
            <div class="tab-content">
            	 <div class="active tab-pane" id="plan_daily">
                 	 <table id="list_plan_daily" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th style="width:20px;">No</th>
                          <th>Tanggal</th>
                          <th>Plan (MT)</th>
                          
                        </tr>
                        </thead>
                       
                      </table>
                 </div><!-- end of plan_daily-->
                 <div class="tab-pane" id="plan_weekly">
                 	<table id="list_plan_weekly" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th style="width:20px;">No</th>
                          <th>Week</th>
                          <th>Rentang Tanggal</th>
                          <th>Plan (MT)</th>
                          
                        </tr>
                        </thead>
                       
                      </table>
                 </div><!-- end of plan_weekly-->
                 <div class="tab-pane" id="plan_monthly">
                 	<table id="list_plan_monthly" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th style="width:20px;">No</th>
                          <th>Bulan</th>
                        
                          <th>Plan (MT)</th>
                          
                        </tr>
                        </thead>
                       
                      </table>
                 </div><!-- end of plan_monthly-->
                                  
            </div><!-- end of tab-content-->
          </div><!-- end of nav-tabs-custom-->
         
         
         
         
         
         
         
         
         
         
         
         
     </div><!-- end box body -->
 </div><!-- end box -->

