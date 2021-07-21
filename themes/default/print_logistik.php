<style>
.foto-kta {
 
  border: 1px rgba(0,0,0,.8) solid;

  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;

  -webkit-box-shadow: 1px 1px 3px 2px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 1px 1px 3px 2px rgba(0, 0, 0, 0.2);
  box-shadow: 1px 1px 3px 2px rgba(0, 0, 0, 0.2);
}
.footer{
	page-break-after:always;
}
.kta-text{
	font-family:Arial, Helvetica, sans-serif;
}
.kta-text table tr td{
	font-family:Arial, Helvetica, sans-serif;
}
</style>
<?php
while($data=current($list_data)){
?>
<page backtop="2mm" backbottom="2mm" backleft="0mm" backright="2mm"  >
	<div style="margin: 0px 0px 0px 0px;padding:0px 0px 0px 0px; width:255px; height:405px;display:inline-block;text-align:center;">
		 <div style="text-align:center;width:233px;display:inline-block;height:143px;margin-left:-3px;">  
           <img src="<?php echo $theme_path;?>images/logo-mini.png" width="100" style=" display:none"  />
			<div style=" font-family: Arial;font-size: 17px;width:100%;margin-top:5px;display:none">
               <b>KARTU PELAYANAN <br />BARANG-PAKAN</b>
            </div>
          </div>
          <div style="text-align:center;width:233px; margin-top:2px;display:inline-block; margin-left:-3px;">    
        
                <img src="<?php echo $data->url_foto;?>"  height="110px" style="" align="middle" class="foto-kta" />
            </div>
             <div style="text-align:center;width:233px; margin-top:5px;display:inline-block;font-weight:bold;margin-left:-3px;" class="kta-text">    
        		<?php echo $data->NAMA;?>
            </div>
             <div style="text-align:center;width:233px; display:inline-block; margin-top:8px;margin-left:-3px;border:" class="kta-text">    
                <table style="width:234px;;border-collapse:collapse;" border="1" >
                <tr>
                	<td style="width:20%; vertical-align:middle;border-right:0px;padding-bottom:1px">
                    <img src="<?php echo $data->BARCODE_LOGISTIK;?>"  style="width:auto;margin-top:1px;margin-left:1px;margin-bottom:1px;" height="72px;" /></td>
                    <td style="border-left:0px;width:80%;">
					<span style="font-weight:bold"><?php echo $data->NoAnggota;?></span><br />
                    <span style="font-size:.9em;line-height:1.5em;display:inline-block"><?php echo $data->TPK;?></span><br />
                    <span style="font-size:.8em;"><?php echo $data->Kelompok;?></span></td>
                </tr>
                </table>
			</div>
        
           
      
        
        </div>
        <div class="footer"></div>
   </page>
 <?php
 	next($list_data);
}
?>