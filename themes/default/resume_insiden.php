<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>

<link type="text/css" href="<?php echo $theme_path;?>bootstrap/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script  src="<?php echo $theme_path;?>bootstrap/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>bootstrap/plugins/datetimepicker/js/locales/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
  
<script>
var $j = jQuery.noConflict();
  $j(document).ready(function() {
   $j('#tanggal').datetimepicker({
      format: 'yyyy-mm-dd hh:ii:ss',
      language: 'id'
                });
});
</script>
<!-- <div class="row">
	<div class="col-md-12" style="">
    
    <div class="box box-solid">
   
    <div class="box-body">

    <div class="post">
         <div class="responsive-form">
            <div class="row-form">
               <h4>Data Pelaporan</h4>
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;"style="width: 200px;">Tanggal Pelaporan</span>
               <input type="text" disabled class="input has-required" id="noregister"  required="true"  value="<?php echo $tglpelapor; ?>">
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;">Pelapor</span>
               <input type="text" disabled class="input has-required" id="noregister"  required="true"  value="<?php echo $pelapor; ?>">
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;">Lokasi</span>
               <input type="text" disabled class="input has-required" id="noregister"  required="true"  value="<?php echo $lokasi; ?>">
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;">Mekanisme Kecelakaan</span>
               <input type="text" disabled class="input has-required" id="noregister"  required="true"  value="<?php echo $jenis_kecelakaan; ?>">
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;">Tingkat Keparahan</span>
               <input type="text" disabled class="input has-required" id="noregister"  required="true"  value="<?php echo $tingkat_keparahan; ?>">
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;">Jumlah Korban</span>
               <input type="text" disabled class="input has-required" id="noregister"  required="true"  value="<?php echo $jumlah_korban; ?>">
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;">Bantuan</span>
               <input type="text" disabled class="input has-required" id="noregister"  required="true"  value="<?php echo $bantuan; ?>">
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;">Foto</span>
            </div>
            <div class="container">
               <?php
               if($fotos!=null){

                  $List=$fotos;
                  while($data = each($List)) {
                  ?>
                     <img src='/files/hse/<?php echo $data['value']; ?>' width='30%'>
                  <?php
                  }
               }
               ?>
            </div>
         </div>
      </div>

    </div>
    </div>
    </div>
</div> -->

<style>
body {font-family: Arial;}

/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab a {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
  color: #000;
}

/* Change background color of buttons on hover */
.tab a:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab a.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>

<div class="row">
	<div class="col-md-12" style="">
    
    <div class="box box-solid">

    <div class="box-body">
    <form action="" method="post" class="form" id="myForm">

      <?php 
         $pesan=array();
         if(isset($Hasil)){
               if ($Hasil['success']==true){
                  echo "<div class=\"alert alert-primary lbl_sukses\" style=\"padding:5px 5px 5px 5px;margin:5px 5px 8px 5px;\">".$Hasil['message']."</div>";
               }
               if ($Hasil['success']==false and trim($Hasil['message'])<>""){
                  echo "<div class=\"alert alert-danger error lbl_error\" style=\"padding:5px 5px 5px 5px;margin:5px 5px 8px 5px;\">".$Hasil['message']."</div>";
               }
         }
      ?>

      <div class="tab">
         <a class="tablinks" onclick="openCity(event, 'korban')">Data Korban</a>
         <a class="tablinks" onclick="openCity(event, 'alatterlibat')">Data Alat Yang Terlibat</a>
         <a class="tablinks" onclick="openCity(event, 'insiden')">Data Insiden</a>
      </div>

      <div id="korban" class="tabcontent" style="display: block;">
      <h3>Data Korban</h3>


         <div class="table-responsive">
         <table class="table table-bordered table-hover dataTable">
            <thead class="header-data"> 
            <tr>
               <th>Nama Korban</th>
               <th>NIK</th>
               <th>Atasan Langsung</th>
               <th>Umur</th>
               <th>Jabatan</th>
               <th>Department</th>
               <th>Masa kerja</th>
               <th>Bagian Luka</th>
               <!-- <th><i class="fa fa-gear"></i></th> -->
            </tr>
            </thead>
            <tbody>
               <!-- <tr><td colspan='4' style='text-align:center;'>Data Kosong</td></tr>         -->
               <?php 
               for ($i=0; $i < count($datakorban); $i++) { 
               ?>
               <tr>
                  <td><input type="text" name="nama_korban[]" value="<?php echo $datakorban[$i]->nama_korban; ?>" placeholder="Nama Korban"></td>
                  <td><input type="text" name="nik[]" value="<?php echo $datakorban[$i]->nik; ?>" placeholder="NIK"></td>
                  <td><input type="text" name="atasan_langsung[]" value="<?php echo $datakorban[$i]->atasan_langsung; ?>" placeholder="Atasan Langsung"></td>
                  <td><input type="number" name="umur[]" value="<?php echo $datakorban[$i]->umur; ?>" placeholder="Umur"></td>
                  <td>
                  <select id="jabatan"  name="jabatan[]" class="input has-required" required="true" >
                     <?php
                        echo '<option value="">--- Jabatan ---</option>';
                        $List=$list_jabatan;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$datakorban[$i]->kode_jabatan?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                  </select>
                  </td>
                  <td>
                  <select  id="department"  name="department[]" class="input has-required" required="true">
                     <?php
                        echo '<option value="">--- Department ---</option>';
                        $List=$list_departemen;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$datakorban[$i]->kode_department?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                  </select>
                  </td>
                  <td>
                  <select id="masakerja"  name="masakerja[]" class="input has-required" required="true">
                     <?php
                        echo '<option value="">--- Masa Kerja ---</option>';
                        $List=$list_masakerja;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$datakorban[$i]->kode_masa_kerja?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                  </select>
                  </td>
                  <td>
                  <select id="luka"  name="luka[]" class="input">
                     <?php
                        echo '<option value="">--- Bagian Luka ---</option>';
                        $List=$list_luka;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$datakorban[$i]->kode_bagian_luka?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                  </select>
                  </td>
                  <!-- <td><a class='btn btn-primary btn-xs' onclick='hapustbhritase("+index+")'><i class='fa fa-trash'></i></a></td> -->
               </tr>
               <?php
               }
               ?>
            </tbody>
         </table>
         </div>

         <script>
         // var $j = jQuery.noConflict();
         // $j(document).ready(function() {
         //    var jmlalat = 0;
         // });

         var jmlalat = 0;
         function tmbdtkorban(){
            jmlalat++;
            const alats = $("#alat").html();
            $("#data_alat").append(
               "   <tr id='dataalat"+jmlalat+"'>"+
               "      <td>"+
               "         <select name='alat[]' class='input has-required' required='true' style='width: 100%;'>"+
                           alats+
               "         </select>"+
               "      </td>"+
               "      <td  style='text-align: center;'><a class='btn btn-primary btn-xs' onclick='hpsdtkorban("+jmlalat+")'><i class='fa fa-trash'></i></a></td>"+
               "   </tr>"
            );
         }
         function hpsdtkorban(id){
            document.getElementById("dataalat"+id).remove();
         }
         function hpsdtkorbanedit(id){
            document.getElementById("dataalatedit"+id).remove();
         }
         </script>

         <div style="display: none;"  id="alat">
         <?php
            echo '<option value="">--- Alat Terlibat ---</option>';
            $List=$list_alat;
            while($data = each($List)) {
               ?>
               <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$alat?"selected":""; ?> >
                  <?php echo $data['value'];?></option>
               <?php
            }
         ?>
         </div>

      </div>

      <div id="alatterlibat" class="tabcontent">
      <h3>Data Alat Yang Terlibat</h3>
         <div class="table-responsive">
         <table class="table table-bordered table-hover dataTable">
            <thead class="header-data"> 
                  <tr>
                     <th >Alat Terlibat</th>
                     <th style="text-align: center;"><a href="javascript:void(0);" onclick="tmbdtkorban()" class="btn btn-primary btn-xs btn-tambah-data"><i class="fa fa-fw fa-plus-circle"></i></a></th>
                  </tr>
            </thead>
            <tbody id="data_alat">
                  <!-- <tr><td colspan='4' style='text-align:center;'>Data Kosong</td></tr>-->
                  <?php 
                  if($dataalat!=null){
                     for ($i=0; $i < count($dataalat); $i++) { 
                  ?>
                     <tr id='dataalatedit<?php echo $i ?>'>
                        <td >
                           <select name="alat[]" class="input has-required" required="true" style="width: 100%;">
                           <?php
                              echo '<option value="">--- Alat Terlibat ---</option>';
                              $List=$list_alat;
                              while($data = each($List)) {
                                 ?>
                                 <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$dataalat[$i]->kode_alat_terlibat?"selected":""; ?> >
                                    <?php echo $data['value'];?></option>
                                 <?php
                              }
                           ?>
                           </select>
                        </td>
                        
                        <td  style="text-align: center;">
                        <?php if($i != 0){ ?>
                        <a class='btn btn-primary btn-xs' onclick='hpsdtkorbanedit(<?php echo $i ?>)'><i class='fa fa-trash'></i></a>
                        <?php } ?>
                        </td>
                     </tr>
                  <?php
                     }
                  }else{
                  ?>
                  <tr>
                     <td >
                        <select name="alat[]" class="input has-required" required="true" style="width: 100%;">
                        <?php
                           echo '<option value="">--- Alat Terlibat ---</option>';
                           $List=$list_alat;
                           while($data = each($List)) {
                              ?>
                              <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$alat?"selected":""; ?> >
                                 <?php echo $data['value'];?></option>
                              <?php
                           }
                        ?>
                        </select>
                     </td>
                     <td  style="text-align: center;"></td>
                  </tr>
                  <?php 
                  }
                  ?>
            </tbody>
         </table>
         </div>
      </div>

      <div id="insiden" class="tabcontent">
      <h3>Data Insiden</h3>
            <input type="hidden" name="idResume" value="<?php echo $id; ?>">
            <!-- Post -->
            <div class="post">
               <div class="responsive-form">
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Kontraktor</span>
                     <select name="company" data-column="1" class="input" id="company" >
                                       <?php
                                       echo '<option value="">-- Kontraktor --</option>';
                                       $List=$list_contractor;
                                       while($data = each($List)) {
                                          ?>
                                    <option value="<?php echo $data['key'];?>" <?php echo $data['key']==$company?"selected":""; ?>><?php echo $data['value'];?></option>
                                    <?php
                                    
                                       }
                                    ?>
                                 </select>
                  </div>
                  <div class="row-form">
                     <span class="label" style="width: 200px;">No. Register</span>
                     <input readonly type="text" class="input has-required" id="noregister" name="noregister" required="true"  value="<?php if($noregister!=null){echo $noregister;}else{ echo $autonumber;} ?>">
                  </div>
                  
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Tanggal dan Jam</span>
                     <input type="text" class="input has-required" id="tanggal" style="width: 200px;"name="tanggal" style="width: 200px;"required="true"  value="<?php echo $tanggal; ?>">
                  </div>
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Keterangan Insiden</span>
                     <textarea name="keterangan" id="keterangan"  class="input  has-required" required="true" rows="1" cols="35"><?php echo $keterangan;?></textarea>
                     
                  </div>
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Shift Kerja</span>
                     <select id="shiftkerja"  name="shiftkerja" class="input has-required" required="true">
                     <?php
                        echo '<option value="">--- Shift Kerja ---</option>';
                        $List=$list_shiftkerja;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$shiftkerja?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                     </select>
                  </div>
                 <div class="row-form">
                  <span class="label" style="width: 200px;">Area Kerja</span>
                  <select id="areakerja"  name="areakerja" class="input has-required" required="true">
                  <?php
                        echo '<option value="">--- Area Kerja ---</option>';
                        $List=$list_areakerja;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$areakerja?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                     </select>
                  </div>
                  
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Jenis Insiden </span>
                     <select id="jenisinsiden"  name="jenisinsiden" class="input has-required" required="true">
                     <?php
                        echo '<option value="">--- Jenis Insiden ---</option>';
                        $List=$list_insiden;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$jenisinsiden?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                     </select>
                  </div>
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Cara Kerja </span>
                     <select id="carakerja"  name="carakerja" class="input has-required" required="true">
                     <?php
                        echo '<option value="">--- Cara Kerja ---</option>';
                        $List=$list_carakerja;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$carakerja?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                     </select>
                  </div>
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Kondisi Kerja </span>
                     <select id="kondisikerja"  name="kondisikerja" class="input has-required" required="true">
                     <?php
                        echo '<option value="">--- Kondisi Kerja ---</option>';
                        $List=$list_kondisikerja;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$kondisikerja?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                     </select>
                  </div>
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Faktor Pekerjaan </span>
                     <select id="faktorkerja"  name="faktorkerja" class="input has-required" required="true">
                     <?php
                        echo '<option value="">--- Faktor Pekerjaan ---</option>';
                        $List=$list_faktorkerja;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$faktorkerja?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                     </select>
                  </div>
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Faktor Pribadi</span>
                     <select id="faktorpribadi"  name="faktorpribadi" class="input has-required" required="true">
                     <?php
                        echo '<option value="">--- Faktor Pribadi ---</option>';
                        $List=$list_faktorpribadi;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$faktorpribadi?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                     </select>
                  </div>

                  <div class="row-form">
                  <span class="label" style="width: 200px;">Tindakan Perbaikan</span>
                  <select id="perbaikan"  name="perbaikan" class="input has-required" required="true">
                  <?php
                        echo '<option value="">--- Tindakan Perbaikan ---</option>';
                        $List=$list_perbaikan;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$perbaikan?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                     </select>
                  </div>
                 

                  <div class="row-form">
                     <span class="label" style="width: 200px;">Hari Kerja Hilang</span>

                     <input type="text" class="input has-required" id="harihilang" name="harihilang" size="50%" required="true"  value="<?php echo $harihilang; ?>">
                  </div>
                  <div class="row-form">
                     <span class="label" style="width: 200px;">Perkiraan Biaya Perbaikan</span>
                     <select id="perkiraanbiaya"  name="perkiraanbiaya" class="input has-required" required="true">
                     <?php
                        echo '<option value="">--- Perkiraan Biaya Perbaikan ---</option>';
                        $List=$list_perkiraanbiaya;
                        while($data = each($List)) {
                           ?>
                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$perkiraanbiaya?"selected":""; ?> >
                              <?php echo $data['value'];?></option>
                           <?php
                        }
                     ?>
                     </select>
                  </div>
               </div>
            </div>
      </div>
    
      <br>
      <button id="simpan" style="display: none;" type="submit" class="btn btn-success" value="simpan" name="simpan" >Simpan</button>
      <button id="progress" style="display: none;" type="submit" class="btn btn-success" value="simpan" name="progress" >Submit</button>
      <button id="done" style="display: none;" type="submit" class="btn btn-success" value="simpan" name="done" >Submit</button>

      <?php 
      if($state == 'done' && $state != 'new'){
      ?>
      <a onclick="confrm()" class="btn btn-success" value="simpan" name="simpan" >Simpan</a>
      <?php 
      }else{
      ?>
      <button type="submit" class="btn btn-success" value="simpan" name="simpan" >Simpan</button>
      <?php
      }
      ?>
      
      <?php
      if($state == 'draft' && $state != 'new'){
      ?>
      <a onclick="progress()" class="btn btn-warning"  name="simpan" >On Progress</a>
      <?php
      }
      if($state != 'done' && $state != 'new'){
      ?>
      <a onclick="done()" class="btn btn-danger"  name="simpan" >Done</a>
      <?php 
      }
      ?>
      </form>
     </div>
     </div><!-- box -->
    </div>
</div>

<script>

function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

function confrm(){
   if (confirm("Data Sudah Berstatus 'Done' Anda Yakin Untuk Merubah Data?")) {
      document.getElementById("simpan").click();
    }
    return false;
}
function progress(){
   if (confirm("Anda Yakin Unituk Mengganti Status On Progress?")) {
      document.getElementById("progress").click();
    }
    return false;
}
function done(){
   if (confirm("Anda Yakin Untuk Mengubah Status Done?")) {
      document.getElementById("done").click();
    }
    return false;
}
</script>