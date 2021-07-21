<body style="margin: 0px 0px 0px 0px;" >
<?php
while($data=current($list_data)){
?>
<div style="margin: 0px 0px 0px 0px; width:255px; height:410px;display:inline-block;text-align:center;background-image: url(<?php echo $theme_path;?>images/blanko_ktm.png);background-repeat:no-repeat; ">

         
            
        
           
        
       <!--     <div style=" position: absolute;top: 57px; left: 80px;font-family: Arial;font-size: 13px;">
        
               <b>KARTU TANDA MAHASISWA</b>
        
            </div>-->
          <div style="text-align:center;width:240px; margin-top:120px;display:inline-block; ">    
        
                <img src="<?php echo $data->url_foto;?>" width="80px" height="115px" style="" align="middle" id="foto-ktm" />
        
            </div>
             <div style="text-align:center;width:240px; display:inline-block; ">    
                <img src="<?php echo $data->BARCODE_LOGISTIK;?>"  style="width:auto" />
        
            </div>
        
            <div style=" font-family: Arial;font-size: 12px;text-align:center;color:#FFF;padding-top:15px;width:240px;display:inline-block;">
        
                <b><?php echo strtoupper($profil->mhsNama);?><br /><?php echo $profil->mhsRegNPM;?></b>
        
            </div>
       		 <div style=" font-family: Arial;font-size: 12px;text-align:center;color:#FFF;padding-top:12px;width:240px;display:inline-block;">
        
                <b><?php echo "PROGRAM ".strtoupper($profil->jenjangNama);?><br /><?php echo strtoupper($profil->prodiNama);?></b>
        
            </div>
      
        
        </div>
 <?php
 	next($list_data);
}
?>
</body>