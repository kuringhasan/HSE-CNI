<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>

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
	 <div class="row-form"><span class="label" >Kontraktor</span>: <?php echo $detail->contractor_name;?>
      
          
           <input type="hidden" class="input" name="expit_id" id="expit_id" size="3" value="<?php echo $detail->transit_ore_id;?>"/> 
    </div>
  <div class="row-form">
        <span class="label">Tanggal</span>: <?php echo $detail->tgl;?>
        
   </div>
   <div class="row-form">
            <span class="label" >Shift </span>: <?php echo $detail->shift;?>
       
        </div>
     
        <div class="row-form">
				  <span class="label">PIT  </span>: <?php echo $detail->pit_name;?>
				
		</div>
      <div class="row-form">
        <span class="label">Dump Truck</span>: <?php echo $detail->no_dump_truck;?>
        
    </div>
    <div class="row-form">
        <span class="label" >Tujuan </span>: <?php echo $detail->tujuan_pengangkutan;?>
     

     </div>   
     <div class="row-form" id="row_lokasi_dome">
          <span class="label">Lokasi Dome  </span>: <?php echo $detail->location_name;?>
        
       </div>
      <div class="row-form" id="row_dome_id">
          <span class="label">Dome  </span>: <?php echo $detail->dome_name;?>
        
       </div>
       <div class="row-form" id="row_barge_id">
          <span class="label">Barge  </span>: <?php echo $detail->barge_name;?>
       
          
       </div>
     <div class="row-form">
        <span class="label">Ritase</span>:  <?php echo $detail->ritase;?> rit
   </div>          
   
 </div>

