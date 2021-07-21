<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $theme_path;?>css/form_search.css" rel="stylesheet" />
<script>
$(function() {

		
});
</script>
<div class="row">
    <div class="col-xs-12">
     
      <div class="box">
        <div class="box-header text-center">
         
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <div style="border:1px solid #0CF;background-color:#FFC;padding:10px 10px 5px 10px;font-size:12px;margin-bottom:10px;">
        <ol>
        <li>File yang diimport adalah file excel sesuai dengan format upload karyawan</li>
        <li>Tidak boleh merubah apapun file import_karyawan.xlsx</li>
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
                formuplod($url_import);
            }
            
        
            
         function formuplod($url_action){
            
        ?>
        <div class="responsive-form" style="width:100%" >
        <form class="responsive-form" name="form1" method="post" enctype="multipart/form-data" action="<?php echo $url_action;?>" target="media-import">
        <div class="row-form">
                <span class="label" >File Excel</span>  
                <input name="file_excel" type="file" id="file_excel" class="input" style="border:0;">
        </div>
        <div class="row-form" style="margin-top:8px;">
                <span class="label" >&nbsp;&nbsp;</span> 
                      &nbsp;<button name="Upload" type="submit" id="Upload" value="Upload" class="btn btn-primary btn-xs" ><i class="fa fa-fw fa-upload"></i>&nbsp;Import</button>
        </div>
        </form>
        </div>
        
        <?php 
        }
        ?>
        
        <iframe  name="media-import"  id="media-import" style="width:100%;border:0px;" ></iframe>
 		</div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
 