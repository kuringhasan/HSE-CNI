<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $theme_path;?>css/form_search.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery-1.12.1.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>bootstrap/bootstrap/js/typeahead.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>bootstrap/bootstrap/js/hogan-3.0.0.min.js"></script>
<script>
$(function() {
	$("#category").change(function(){		
		var nilai=	$(this).val();	
		if(nilai=="daily"){
			$("#awal_row_data").val(6);
		}else{
			$("#awal_row_data").val(2);
		}
	}); 
	
	

	$('#download-excel').click(function(){
		if($("#crProdi").val()!=='' && $("#crTahunAkademik").val()!=="" && $("#crMataKuliah").val()!==""){
			$("#form_cari").attr("target","media-download");
			$("#form_cari").attr("action","<?php echo $url_excel;?>");
			$("#form_cari").submit();
			
		}else{
			alert('Isi dulu Prodi, Tahun Akademik dan Mata Kuliah');		
		}
		//jumlah_data($("#form_cari").serialize()+'&'+$("#form-list-data").serialize());
	});
		$("#Upload").button();
		/*$("#Upload").click(function(){
				$.ajax({
                		 	type	:'POST',
                			dataType:'html',
                			url		:urla,
                			data	:'aksi='+aksi+'&'+$("#frmBA").serialize(),
                			success	:function(msg){
                			   $("#daftar-ruang").html("ek");
                			
                			}
        		    });
			   
		   });
		});*/
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
function comboAjax(url_cmb,parentkode,idTarget,nilai,other_parameters,idloader, idframe)
	{
		//alert(url_cmb+'?nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters);
		$("#"+idTarget).hide();
		$("#"+idloader).show();
		$.ajax({
			   type:'POST',
			   dataType:'html',
			   url:url_cmb,
			   data:'nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters,
			   success:function(msg){
				  var obj=JSON.parse(msg);
				   $("#"+idloader).hide();
				   if (obj.kosong==false)
				   {	
					  $("#"+idTarget).fadeIn();
					  $("#"+idTarget).empty().append(obj.html);
				   }else{
					  // $("#"+idframe).fadeOut();
					   $("#"+idTarget).fadeIn();
					  $("#"+idTarget).empty().append(obj.html);
				   }
				   
			   }///akhisr sukses
		   }); //akhir $.ajax	
	}
</script>
<div class="row">
	<div class="col-md-7" style="border:0px solid #F00;padding:5px 2px 5px 6px;">
    
    <div class="form-search"  style="width:auto;">
    <div class="search-title">Pencarian Data</div>
    <div class="search-body responsive-form">
     <form  id="form_cari"  method="post" name="form2"  >
    
      <!--
       
        <div class="row-form">
          <span class="label" style="min-width:180px;width:auto">Tahun Akademik</span>
            <select name="crTahunAkademik" id="crTahunAkademik" class="input" >
                 <?php
                echo '<option value="">--Tahun Akademik--</option>';
                
                    $List=$ListTA;
                    while($data = each($List)) {
                    
                        $ta=isset($_POST['crTahunAkademik'])?$_POST['crTahunAkademik']:$tahun_akademik;
                       ?>
                        <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$ta
                        ?"selected":""; ?> >
                     <?php echo $data['value'];?></option>
                     <?php
                    }
                ?>
                </select>
            
        </div>
    
       <div class="row-form">
        <span class="label"  style="min-width:180px;width:auto;">Mata Kuliah</span>
        
         <select name="crMataKuliah" id="crMataKuliah" class="input">
                 <?php
                echo '<option value="">--mata kuliah--</option>';
                
                    
                ?>
                </select>
               <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderCariMK"/>
         <input type="hidden" class="input" name="kode_mk" id="kode_mk" size="5" value="<?php echo $detail->kurmkKode?>" />
    </div>
         <div class="row-form">
          <span class="label" style="min-width:180px;width:auto">Kelas Kuliah</span>
            <select name="crKelasKuliah" id="crKelasKuliah" class="input">
                 <?php
                echo '<option value="">--Kelas--</option>';
                
                    
                ?>
                </select>
               <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderKelasKuliah"/>
        </div>
        <div class="row-form">
          <span class="label" style="min-width:180px;width:auto">Urutkan Berdasarkan</span>
            <select name="crUrutkan" id="crUrutkan" class="input">
               <option value="">---</option>
               <option value="npm">NPM</option>
               <option value="nama">Nama</option>
                </select>
             
        </div>-->
    </form>
    
    </div>
            
        <div class="search-footer"><button type="button" class="btn btn-primary btn-xs" id="TampilkanData" >
        <i class="fa fa-search"></i>&nbsp;Tampilkan</button>
        <button type="button" class="btn btn-primary btn-xs" id="download-excel" >
        <i class="fa fa-download"></i>&nbsp;Download</button>
        </div>
    </div>
    </div><!-- col-mod-6 -->
      <script>
		setInterval(function(){
		$(".kedip").toggle();
		},600);
	</script>
    <style>
	.notice{
		border:0px solid #0CF;
		background-color:#FFC;
		padding:10px 10px 5px 10px;
		font-size:12px;
		margin-bottom:10px;
	}
	.notice-red{
		border:0px solid #0CF;
		background-color:#FFC;
		padding:10px 10px 5px 10px;
		font-size:1.3em;
		margin-bottom:10px;
		color:#F00;
		text-align:center;
	}
	.jadwal{
		font-size:0.8em;
	}
	</style>
    <div class="col-md-5" style="border:0px solid #F00;padding:5px 5px 5px 2px;">
    	<div class="form-search"  style="width:auto;max-width:600px;">
    	<div style="" class="notice">
        <ol>
            <li>File yang diupload adalah file excel sesuai format yang didownload di sebelah kiri</li>
            <li>Tidak boleh merubah format</li>
        </ol>
        </div>  
        
		<?php
             if ($_POST['Upload']){
                 echo "<div class='admin_info' style='padding:5px 5px 5px 5px;'>";
                echo "<b>".$Notice."</b><br>";
                echo "<font color='red'>".$nmFile."</font>";
                echo "</font>";
            } else
            {
				//echo "<pre>";print_r($jadwal_entry);echo "</pre>";
				$tahun_ajaran=$jadwal_entry['tahun_akademik']->Lengkap;
				$kedip="<div style=\"height:13px;\"><i class=\"fa fa-warning kedip\" style=\"font-size:1.3em;\"></i></div><br />";
				if(trim($jadwal_entry['status'])=="belum"){
					echo "<div class=\"notice-red\">".$kedip." Jadwal entry/upload nilai tahun ".$tahun_ajaran." belum dibuka<br /> <span class=\"jadwal\">Jadwal : ".$jadwal_entry['detail']['JadwalMulai']." - ".$jadwal_entry['detail']['JadwalAkhir']."</span></div>";
				}elseif(trim($jadwal_entry['status'])=="lewat"){
					echo "<div class=\"notice-red\">$kedip Jadwal entry/upload nilai tahun ".$tahun_ajaran." sudah ditutup<br /> <span class=\"jadwal\">Jadwal : <br />".$jadwal_entry['detail']['JadwalMulai']." - ".$jadwal_entry['detail']['JadwalAkhir']."</span></div>";
				}elseif(trim($jadwal_entry['status'])=="not_found"){
					echo "<div class=\"notice-red\">$kedip Jadwal entry/upload nilai tahun ".$tahun_ajaran." belum diatur<br /></div>";
				}else{
					
                	formuplod($url_upload,$tahun_akademik);
				}
            }
		?>
            
        <?php
            
         function formuplod($url_action,$TahunAkademik){
            
        ?>
        <div class="responsive-form" style="width:100%" >
        <form class="responsive-form" id="form-upload" name="form1" method="post" enctype="multipart/form-data" action="<?php echo $url_action;?>">
         <div class="row-form">
    <span class="label" style="">Kategori</span>
            <select name="category" id="category" class="input" >
                 <option value="">--Pilih--</option>
                        <option value="daily"  >Daily</option>
                        <option value="weekly"  >Weekly</option>
                        <option value="monthly"  >Monthly</option>
                    
                </select>
            
        </div>
        <div class="row-form">
                <span class="label" >File Excel</span>  <input name="file_excel" type="file" id="file_excel" class="input" style="border:0;">
        </div>
         <div class="row-form">
            <span class="label"  >Awal Row Data</span>
        
            <input type="text" class="input" name="awal_row_data" id="awal_row_data" size="1"  value="2" />
        </div>
    	<div class="row-form">
            <span class="label"  >Jumlah Sheet</span>
        
            <input type="text" class="input" name="jml_sheet" id="jml_sheet" size="1"   />
        </div>
        <div class="row-form" style="margin-top:8px;">
                <span class="label" >&nbsp;</span> 
                      <button name="Upload" type="submit" id="Upload" value="Upload" class="btn btn-primary btn-xs" ><i class="fa fa-fw fa-upload"></i>&nbsp;Upload</button>
                    
        </div>
        </form>
        </div>
        
        <?php 
        }
        ?>  
        </div> 
    </div><!-- col-->
</div>
<iframe src="" name="media-download" id="media-download" style="border:0px solid #F00;height:400px;"></iframe>
<div class="search-result">
<img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:34px; vertical-align:middle; border:0px;" id="loader_listdata"/>
    <div id="tabeldata" style="min-height:390px; width:auto;">
       
    </div>      
</div>

<div id="konfirmupload">
</div>
