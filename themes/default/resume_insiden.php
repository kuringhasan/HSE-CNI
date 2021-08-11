<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>

<link type="text/css" href="<?php echo $theme_path;?>bootstrap/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link href="<?php echo $theme_path;?>slim-select-master/dist/slimselect.min.css" rel="stylesheet" />


<script  src="<?php echo $theme_path;?>bootstrap/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>bootstrap/plugins/datetimepicker/js/locales/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

<script>
var $j = jQuery.noConflict();
  $j(document).ready(function() {
   $j('#tanggal').datetimepicker({
      format: 'yyyy-mm-dd hh:ii:ss',
      language: 'id'
                });
});
</script>

<style>
body {font-family: Arial;}

.nav li.active a{
   border-top:4px solid blue 
}
.nav li.active a:hover{
   border-top:4px solid blue 
}

/* Style the tab */
/* .tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
} */

/* Style the buttons inside the tab */
/* .tab a {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
  color: #000;
} */

/* Change background color of buttons on hover */
/* .tab a:hover {
  background-color: #ddd;
} */

/* Create an active/current tablink class */
/* .tab a.active {
  background-color: #ccc;
} */

/* Style the tab content */
/* .tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
} */





#grad1 {
    background-color: : #9C27B0;
    background-image: linear-gradient(120deg, #FF4081, #81D4FA)
}

#msform {
    text-align: center;
    position: relative;
    margin-top: 20px
}

#msform fieldset .form-card {
    background: white;
    border: 0 none;
    border-radius: 0px;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    padding: 20px 40px 30px 40px;
    box-sizing: border-box;
    width: 94%;
    margin: 0 3% 20px 3%;
    position: relative
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

#msform fieldset:not(:first-of-type) {
    display: none
}

#msform fieldset .form-card {
    text-align: left;
    color: #9E9E9E
}

#msform input,
#msform textarea {
    padding: 0px 8px 4px 8px;
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    font-size: 16px;
    letter-spacing: 1px
}

#msform input:focus,
#msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: none;
    font-weight: bold;
    border-bottom: 2px solid skyblue;
    outline-width: 0
}

#msform .action-button {
    width: 100px;
    background: skyblue;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button:hover,
#msform .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
}

#msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
}

select.list-dt {
    border: none;
    outline: 0;
    border-bottom: 1px solid #ccc;
    padding: 2px 5px 3px 5px;
    margin: 2px
}

select.list-dt:focus {
    border-bottom: 2px solid skyblue
}

.card {
    z-index: 0;
    border: none;
    border-radius: 0.5rem;
    position: relative
}

.fs-title {
    font-size: 25px;
    color: #2C3E50;
    margin-bottom: 10px;
    font-weight: bold;
    text-align: left
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
}

#progressbar .active {
    color: #000000
}

#progressbar li {
    list-style-type: none;
    font-size: 12px;
    width: 20%;
    float: left;
    position: relative
}

#progressbar #datainsiden:before {
    font-family: FontAwesome;
    content: "\f0ce"
}

#progressbar #korban:before {
    font-family: FontAwesome;
    content: "\f0c0"
}

#progressbar #dataalat:before {
    font-family: FontAwesome;
    content: "\f0ad"
}

#progressbar #tindakan:before {
    font-family: FontAwesome;
    content: "\f0ae"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
    content: "\f00c"
}

#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 18px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: skyblue
}

.radio-group {
    position: relative;
    margin-bottom: 25px
}

.radio {
    display: inline-block;
    width: 204;
    height: 104;
    border-radius: 0;
    background: lightblue;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    cursor: pointer;
    margin: 8px 2px
}

.radio:hover {
    box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
}

.radio.selected {
    box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
}

.fit-image {
    width: 100%;
    object-fit: cover
}
.stepdata {
   display: none;
}
</style>

<!-- <div class="container"> -->
  
<!-- </div> -->




<div class="row">
	<div class="col-md-12">
   <!-- <form action="" method="post" class="form" id="myForm"> -->
    <div class="box box-solid">

    <div class="box-body" style="padding:20px;">
    <div class="row-form">
       
      <?php if($id_resume!=null){ ?>
      <div class="post">
         <div class="responsive-form">
            <div class="row-form">
               <span class="label" style="width: 200px;">Status Insiden </span>
                  <b><span style="color:<?php if($state=='draft'){ echo 'red'; }elseif($state=='on progress'){echo 'blue';}elseif($state=='done'){echo 'green';} ?>;"><?php echo $state; ?></span></b>
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;">Tanggal Awal Progress</span>
                  <b><span ><?php echo $tgl_awal_progress; ?></span></b>
            </div>
            <div class="row-form">
               <span class="label" style="width: 200px;">Tanggal Selesai Progress</span>
                  <b><span ><?php echo $tgl_selesai_progress; ?></span></b>
            </div>
         </div>
      </div>
      <?php }else{ ?>
         <div class="post">
         <div class="responsive-form">
            <div class="row-form">
               <span class="label" style="width: 200px;">Status Insiden </span>
                  <b><span style="color:red;">draft</span></b>
            </div>
         </div>
      </div>
      <?php } ?>

    <div class="container-fluid">
         <div class="row">
            <div class="text-center">
                  <br> 
                  <div class="card ">
                     <div class="row">
                        <div class="col-md-12 mx-0">
                              <!-- progressbar -->
                              <ul id="progressbar" style="padding: 0px;">
                                 <li class="active" id="datainsiden"><strong>Data Insiden</strong></li>
                                 <li id="korban"><strong>Data Korban</strong></li>
                                 <li id="dataalat"><strong>Data Alat Terlibat</strong></li>
                                 <li id="tindakan"><strong>Tindakan Perbaikan</strong></li>
                                 <li id="confirm"><strong>Finish</strong></li>
                              </ul> <!-- fieldsets -->
                              <fieldset>
                                 <div class="form-card">
                                    <form action="" id="form_insiden">
                                    <h2>Data Insiden</h2>
                                    <br>
                                    <input type="hidden" name="idResume" value="<?php echo $id_resume; ?>">
                                    <input type="hidden" name="id_insiden" value="<?php echo $id_insiden; ?>">
                                    <!-- Post -->
                                    <div class="post">
                                       <div class="responsive-form">
                                          <div class="row-form">
                                             <span class="label" style="width: 200px;">Kontraktor</span>
                                             <select required name="company" data-column="1" style="width: 80%;" class="form-control input" id="company" >
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
                                             <span class="label" style="width: 200px;"" >No. Register</span>
                                             <input readonly type="text" style="width: 80%;" class="form-control input has-required" id="noregister" name="noregister" required="true"  value="<?php if($noregister!=null){echo $noregister;}else{ echo $autonumber;} ?>">
                                          </div>
                                          <?php $date = explode(" ", $tanggal); ?>
                                          <div class="row-form">
                                             <span class="label" style="width: 200px;">Tanggal dan Jam</span>
                                             <input required type="datetime-local" style="width: 80%;" class="form-control input has-required" id="tanggal" style="width: 200px;"name="tanggal" style="width: 200px;"required="true"  value="<?php echo $date[0]."T".$date[1]; ?>">
                                          </div>
                                          <div class="row-form">
                                             <span class="label" style="width: 200px;">Keterangan Insiden</span>
                                             <textarea name="keterangan" id="keterangan"  style="width: 80%;" class="form-control input  has-required" required="true" rows="2" cols="35"><?php echo $keterangan;?></textarea>
                                             
                                          </div>
                                          <div class="row-form">
                                             <div class="label" style="width: 200px;">Shift Kerja</div>
                                             <select id="shiftkerja"  name="shiftkerja" style="width: 80%;" class="form-control input has-required" required="true">
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
                                          <select id="areakerja"  name="areakerja" style="width: 80%;" class="form-control input has-required" required="true">
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
                                             <select id="jenisinsiden"  name="jenisinsiden" style="width: 80%;" class="form-control input has-required" required="true">
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
                                             <select id="carakerja"  name="carakerja" style="width: 80%;" class="form-control input has-required" required="true">
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
                                             <select id="kondisikerja"  multiple style="width: 80%;" name="kondisikerja[]" class="input has-required" required="true">
                                             <?php
                                                echo '<option value="">--- Kondisi Kerja ---</option>';
                                                $List=$list_kondisikerja;
                                                while($data = each($List)) {
                                                   ?>
                                                   <option value="<?php echo $data['key'];?>"  <?php if($kondisikerja!=null){if (in_array($data['key'], $kondisikerja)){echo 'selected'; }} ?> >
                                                      <?php echo $data['value'];?></option>
                                                   <?php
                                                }
                                             ?>
                                             </select>
                                          </div>
                                          <div class="row-form">
                                             <span class="label" style="width: 200px;">Faktor Pekerjaan </span>
                                             <select id="faktorkerja" multiple style="width: 80%;" name="faktorkerja[]" class="input has-required" required="true">
                                             <?php
                                                echo '<option value="">--- Faktor Pekerjaan ---</option>';
                                                $List=$list_faktorkerja;
                                                while($data = each($List)) {
                                                   ?>
                                                   <option value="<?php echo $data['key'];?>"   <?php if($faktorkerja!=null){if (in_array($data['key'], $faktorkerja)){echo 'selected'; }} ?> >
                                                      <?php echo $data['value'];?></option>
                                                   <?php
                                                }
                                             ?>
                                             </select>
                                          </div>
                                          <div class="row-form">
                                             <span class="label" style="width: 200px;">Faktor Pribadi</span>
                                             <select id="faktorpribadi" multiple style="width: 80%;" name="faktorpribadi[]" class="input has-required" required="true">
                                             <?php
                                                echo '<option value="">--- Faktor Pribadi ---</option>';
                                                $List=$list_faktorpribadi;
                                                while($data = each($List)) {
                                                   ?>
                                                   <option value="<?php echo $data['key'];?>"  <?php if($faktorpribadi!=null){ if (in_array($data['key'], $faktorpribadi)){echo 'selected'; }} ?>>
                                                      <?php echo $data['value'];?></option>
                                                   <?php
                                                }
                                             ?>
                                             </select>
                                          </div>

                                          <!-- <div class="row-form">
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
                                          </div> -->
                                          

                                          <!-- <div class="row-form">
                                             <span class="label" style="width: 200px;">Hari Kerja Hilang</span>

                                             <input type="text" class="input has-required" id="harihilang" name="harihilang" size="80%" required="true"  value="<?php echo $harihilang; ?>">
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
                                          </div> -->
                                       </div>
                                    </div>
                                    </form>
                                 </div> <hr> <input type="button" name="next" class="next action-button btn btn-primary data_insiden" value="Next Step" />
                              </fieldset>
                              <fieldset class="stepdata">
                                 <div class="form-card">
                                    <form action="" id="form_korban">
                                    <input type="hidden" name="idResume" value="<?php echo $id_resume; ?>">
                                    <input type="hidden" name="id_insiden" value="<?php echo $id_insiden; ?>">

                                    <h2>Data Korban</h2>
                                    <br>
                                    <div class="table-responsive">
                                    <table class="table table-bordered table-hover dataTable">
                                       <thead class="header-data"> 
                                       <tr>
                                          <th>Nama Korban</th>
                                          <th>Employee ID</th>
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
                                          $jumlahkorban =  count($datakorban);
                                          if($jumlah_korban != null){
                                             $jumlahkorban = $jumlah_korban;
                                          }
                                          for ($i=0; $i < $jumlahkorban; $i++) { 
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

                                    </form>
                                 </div> <hr> <input type="button" name="previous" class="previous action-button-previous  btn btn-primary" value="Previous" /> <input type="button" name="next" class="next action-button btn btn-primary data_korban" value="Next Step" />
                              </fieldset>
                              <fieldset class="stepdata">
                                 <div class="form-card">
                                    <form action="" id="form_alat">
                                    <input type="hidden" name="idResume" value="<?php echo $id_resume; ?>">
                                    <input type="hidden" name="id_insiden" value="<?php echo $id_insiden; ?>">


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


                                    <h2>Data Alat terlibat</h2>
                                    <br>
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
                                    </form>
                                 </div><hr> <input type="button" name="previous" class="previous action-button-previous btn btn-primary" value="Previous" /> <input type="button" name="make_payment" class="next action-button btn btn-primary alat_terlibat" value="Next Step" />
                              </fieldset>
                              <fieldset class="stepdata">
                                 <div class="form-card">
                                    <form action="" id="form_tindakan">
                                    <input type="hidden" name="idResume" value="<?php echo $id_resume; ?>">
                                    <input type="hidden" name="id_insiden" value="<?php echo $id_insiden; ?>">

                                    <script>
                                    // var $j = jQuery.noConflict();
                                    // $j(document).ready(function() {
                                    //    var jmlalat = 0;
                                    // });

                                    var jmlalat = 0;
                                    function tmbdttindakan(){
                                       jmlalat++;
                                       const alats = $("#dttindakan").html();
                                       $("#data_tindakan").append(
                                          "   <tr id='datatindakan"+jmlalat+"'>"+
                                          "      <td>"+
                                          "         <select name='tindakan[]' class='input has-required form-control' required='true' style='width: 100%;'>"+
                                                      alats+
                                          "         </select>"+
                                          "      </td>"+
                                          "      <td><input type='text' name='deskripsi_tindakan[]' class='form-control'></td>"+
                                          "      <td  style='text-align: center;'><a class='btn btn-primary btn-xs' onclick='hpsdttindakan("+jmlalat+")'><i class='fa fa-trash'></i></a></td>"+
                                          "   </tr>"
                                       );
                                    }
                                    function hpsdttindakan(id){
                                       document.getElementById("datatindakan"+id).remove();
                                    }
                                    function hpsdttindakanedit(id){
                                       document.getElementById("datatindakanedit"+id).remove();
                                    }
                                    </script>

                                    <div style="display: none;"  id="dttindakan">
                                    <?php
                                       echo '<option value="">--- Tindakan Perbaiakan ---</option>';
                                       $List=$list_perbaikan;
                                       while($data = each($List)) {
                                          ?>
                                          <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$alat?"selected":""; ?> >
                                             <?php echo $data['value'];?></option>
                                          <?php
                                       }
                                    ?>
                                    </div>

                                    <h2>Tindakan Perbaikan</h2>

                                    <div class="table-responsive">
                                    <table class="table table-bordered table-hover dataTable">
                                       <thead class="header-data"> 
                                             <tr>
                                                <th >Tindakan Perbaikan</th>
                                                <th >Deskripsi</th>
                                                <th style="text-align: center;"><a href="javascript:void(0);" onclick="tmbdttindakan()" class="btn btn-primary btn-xs btn-tambah-data"><i class="fa fa-fw fa-plus-circle"></i></a></th>
                                             </tr>
                                       </thead>
                                       <tbody id="data_tindakan">
                                             <!-- <tr><td colspan='4' style='text-align:center;'>Data Kosong</td></tr>-->
                                             <?php 
                                             if($datatindakan!=null){
                                                for ($i=0; $i < count($datatindakan); $i++) { 
                                             ?>
                                                <tr id='datatindakanedit<?php echo $i ?>'>
                                                   <td >
                                                      <select name="tindakan[]" class="input has-required form-control" required="true" style="width: 100%;">
                                                      <?php
                                                         echo '<option value="">--- Tindakan Perbaiakan ---</option>';
                                                         $List=$list_perbaikan;
                                                         while($data = each($List)) {
                                                            ?>
                                                            <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$datatindakan[$i]->kode_tindakan_perbaikan?"selected":""; ?> >
                                                               <?php echo $data['value'];?></option>
                                                            <?php
                                                         }
                                                      ?>
                                                      </select>
                                                   </td>
                                                   <td><input type='text' name='deskripsi_tindakan[]' value="<?php echo $datatindakan[$i]->deskripsi; ?>" class='form-control'></td>
                                                   <td  style="text-align: center;">
                                                   <?php if($i != 0){ ?>
                                                   <a class='btn btn-primary btn-xs' onclick='hpsdttindakanedit(<?php echo $i ?>)'><i class='fa fa-trash'></i></a>
                                                   <?php } ?>
                                                   </td>
                                                </tr>
                                             <?php
                                                }
                                             }else{
                                             ?>
                                             <tr>
                                                <td >
                                                   <select name="tindakan[]" class="input has-required form-control" required="true" style="width: 100%;">
                                                   <?php
                                                      echo '<option value="">--- Tindakan Perbaiakan ---</option>';
                                                      $List=$list_alat;
                                                      while($data = each($List)) {
                                                         ?>
                                                         <option value="<?php echo $data['key'];?>"   >
                                                            <?php echo $data['value'];?></option>
                                                         <?php
                                                      }
                                                   ?>
                                                   </select>
                                                </td>
                                                <td><input type='text' name='deskripsi_tindakan[]' class='form-control'></td>
                                                <td  style="text-align: center;"></td>
                                             </tr>
                                             <?php 
                                             }
                                             ?>
                                       </tbody>
                                    </table>
                                    </div>

                                    <hr>

                                    <div class="post">
                                       <div class="responsive-form">
                                          <div class="row-form">
                                             <span class="label" style="width: 200px;">Hari Kerja Hilang</span>

                                             <input type="text" style="width: 80%;" class="form-control input has-required" id="harihilang" name="harihilang" size="80%" required="true"  value="<?php echo $harihilang; ?>">
                                          </div>
                                          <div class="row-form">
                                             <span class="label" style="width: 200px;">Perkiraan Biaya Perbaikan</span>
                                             <select id="perkiraanbiaya" style="width: 80%;" name="perkiraanbiaya" class="form-control input has-required" required="true">
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

                                    <br>
                                    </form>
                                 </div><hr> <input type="button" name="previous" class="previous action-button-previous btn btn-primary" value="Previous" /> <input type="button" name="make_payment" class="next action-button btn btn-primary tindakan" value="Confirm" />
                              </fieldset>
                              <fieldset class="stepdata">
                                 <div class="form-card">
                                    <!-- <h2>Selesai!!</h2> -->

                                    <div>
                                       <hr>
                                       <!-- Post -->
                                       <div class="post">
                                          <div class="responsive-form">
                                             <div class="row-form">
                                                <span class="label" style="width: 200px;">Kontraktor</span>
                                                <select disabled name="company"  data-column="1" style="width: 80%;" class="form-control input" id="company" >
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
                                                <input disabled type="text"  style="width: 80%;" class="form-control input has-required" id="noregister" name="noregister" required="true"  value="<?php if($noregister!=null){echo $noregister;}else{ echo $autonumber;} ?>">
                                             </div>
                                             <?php $date = explode(" ", $tanggal); ?>
                                             <div class="row-form">
                                                <span class="label" style="width: 200px;">Tanggal dan Jam</span>
                                                <input disabled type="datetime-local" style="width: 80%;" class="form-control input has-required" id="tanggal" style="width: 200px;"name="tanggal" style="width: 200px;"required="true"  value="<?php echo $date[0]."T".$date[1]; ?>">
                                             </div>
                                             <div class="row-form">
                                                <span class="label" style="width: 200px;">Keterangan Insiden</span>
                                                <textarea disabled name="keterangan" id="keterangan"  style="width: 80%;" class="form-control input  has-required" required="true" rows="2" cols="35"><?php echo $keterangan;?></textarea>
                                                
                                             </div>
                                             <div class="row-form">
                                                <span class="label" style="width: 200px;">Shift Kerja</span>
                                                <select disabled id="shiftkerja"  name="shiftkerja"  style="width: 80%;" class="form-control input has-required" required="true">
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
                                             <select disabled id="areakerja"  name="areakerja"   style="width: 80%;" class="form-control input has-required" required="true">
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
                                                <select disabled id="jenisinsiden"   style="width: 80%;" class="form-control jenisinsiden" class="input has-required" required="true">
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
                                                <select disabled id="carakerja"  name="carakerja"  style="width: 80%;" class="form-control input has-required" required="true">
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
                                                <select disabled id="kondisikerja2"  multiple style="width: 80%;" name="kondisikerja[]" class="input has-required" required="true">
                                                <?php
                                                   echo '<option value="">--- Kondisi Kerja ---</option>';
                                                   $List=$list_kondisikerja;
                                                   while($data = each($List)) {
                                                      ?>
                                                      <option value="<?php echo $data['key'];?>"  <?php if($kondisikerja!=null){if (in_array($data['key'], $kondisikerja)){echo 'selected'; }} ?> >
                                                         <?php echo $data['value'];?></option>
                                                      <?php
                                                   }
                                                ?>
                                                </select>
                                             </div>
                                             <div class="row-form">
                                                <span class="label" style="width: 200px;">Faktor Pekerjaan </span>
                                                <select disabled id="faktorkerja2" multiple style="width: 80%;" name="faktorkerja[]" class="input has-required" required="true">
                                                <?php
                                                   echo '<option value="">--- Faktor Pekerjaan ---</option>';
                                                   $List=$list_faktorkerja;
                                                   while($data = each($List)) {
                                                      ?>
                                                      <option value="<?php echo $data['key'];?>"   <?php if($faktorkerja!=null){if (in_array($data['key'], $faktorkerja)){echo 'selected'; }} ?> >
                                                         <?php echo $data['value'];?></option>
                                                      <?php
                                                   }
                                                ?>
                                                </select>
                                             </div>
                                             <div class="row-form">
                                                <span class="label" style="width: 200px;">Faktor Pribadi</span>
                                                <select disabled id="faktorpribadi2" multiple style="width: 80%;" name="faktorpribadi[]" class="input has-required" required="true">
                                                <?php
                                                   echo '<option value="">--- Faktor Pribadi ---</option>';
                                                   $List=$list_faktorpribadi;
                                                   while($data = each($List)) {
                                                      ?>
                                                      <option value="<?php echo $data['key'];?>"  <?php if($faktorpribadi!=null){ if (in_array($data['key'], $faktorpribadi)){echo 'selected'; }} ?>>
                                                         <?php echo $data['value'];?></option>
                                                      <?php
                                                   }
                                                ?>
                                                </select>
                                             </div>
                                          </div>
                                       </div>

                                       <div>
                                          <ul class="nav nav-tabs">
                                             <li class="active"><a style="color: #000;" href="#dt_korban">Data Korban</a></li>
                                             <li><a style="color: #000;" href="#dt_alat_terlibat">Data Alat Yang Terlibat</a></li>
                                             <li><a style="color: #000;" href="#dt_tindakan">Tindakan Perbaikan</a></li>
                                          </ul>

                                          <div class="tab-content">

                                             <div id="dt_korban" class="tab-pane fade in active"  style="padding: 10px;">
                                                <div class="table-responsive">
                                                   <table class="table table-bordered table-hover dataTable">
                                                      <thead class="header-data"> 
                                                      <tr>
                                                         <th>Nama Korban</th>
                                                         <th>Employee ID</th>
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
                                                         $jumlahkorban =  count($datakorban);
                                                         if($jumlah_korban != null){
                                                            $jumlahkorban = $jumlah_korban;
                                                         }
                                                         for ($i=0; $i < $jumlahkorban; $i++) { 
                                                         ?>
                                                         <tr>
                                                            <td><input disabled type="text" name="nama_korban[]" value="<?php echo $datakorban[$i]->nama_korban; ?>" placeholder="Nama Korban"></td>
                                                            <td><input disabled type="text" name="nik[]" value="<?php echo $datakorban[$i]->nik; ?>" placeholder="NIK"></td>
                                                            <td><input disabled type="text" name="atasan_langsung[]" value="<?php echo $datakorban[$i]->atasan_langsung; ?>" placeholder="Atasan Langsung"></td>
                                                            <td><input disabled type="number" name="umur[]" value="<?php echo $datakorban[$i]->umur; ?>" placeholder="Umur"></td>
                                                            <td>
                                                            <select disabled id="jabatan"  name="jabatan[]" class="input has-required" required="true" >
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
                                                            <select disabled id="department"  name="department[]" class="input has-required" required="true">
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
                                                            <select disabled id="masakerja"  name="masakerja[]" class="input has-required" required="true">
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
                                                            <select disabled id="luka"  name="luka[]" class="input">
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
                                             </div>

                                             <div id="dt_alat_terlibat" class="tab-pane fade"  style="padding: 10px;">
                                                <div class="table-responsive">
                                                   <table class="table table-bordered table-hover dataTable">
                                                      <thead class="header-data"> 
                                                            <tr>
                                                               <th >Alat Terlibat</th>
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
                                                                     <select disabled name="alat[]" class="input has-required" required="true" style="width: 100%;">
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
                                                               </tr>
                                                            <?php
                                                               }
                                                            }
                                                            ?>
                                                      </tbody>
                                                   </table>
                                                </div>
                                             </div>

                                             <div id="dt_tindakan" class="tab-pane fade"  style="padding: 10px;">
                                                <div class="table-responsive">
                                                   <table class="table table-bordered table-hover dataTable">
                                                      <thead class="header-data"> 
                                                            <tr>
                                                               <th >Tindakan Perbaikan</th>
                                                               <th >Deskripsi</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="data_tindakan">
                                                            <!-- <tr><td colspan='4' style='text-align:center;'>Data Kosong</td></tr>-->
                                                            <?php 
                                                            if($datatindakan!=null){
                                                               for ($i=0; $i < count($datatindakan); $i++) { 
                                                            ?>
                                                               <tr id='datatindakanedit<?php echo $i ?>'>
                                                                  <td >
                                                                     <select disabled name="tindakan[]" class="input has-required form-control" required="true" style="width: 100%;">
                                                                     <?php
                                                                        echo '<option value="">--- Tindakan Perbaiakan ---</option>';
                                                                        $List=$list_perbaikan;
                                                                        while($data = each($List)) {
                                                                           ?>
                                                                           <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$datatindakan[$i]->kode_tindakan_perbaikan?"selected":""; ?> >
                                                                              <?php echo $data['value'];?></option>
                                                                           <?php
                                                                        }
                                                                     ?>
                                                                     </select>
                                                                  </td>
                                                                  <td><input disabled type='text' name='deskripsi_tindakan[]' value="<?php echo $datatindakan[$i]->deskripsi; ?>" class='form-control'></td>
                                                               </tr>
                                                            <?php
                                                               }
                                                            }
                                                            ?>
                                                      </tbody>
                                                   </table>
                                                   </div>

                                                   <hr>

                                                   <div class="post">
                                                      <div class="responsive-form">
                                                         <div class="row-form">
                                                            <span disabled class="label" style="width: 200px;">Hari Kerja Hilang</span>

                                                            <input disabled type="text" style="width: 80%;" class="form-control input has-required" id="harihilang" name="harihilang" size="80%" required="true"  value="<?php echo $harihilang; ?>">
                                                         </div>
                                                         <div class="row-form">
                                                            <span class="label" style="width: 200px;">Perkiraan Biaya Perbaikan</span>
                                                            <select disabled id="perkiraanbiaya" style="width: 80%;" name="perkiraanbiaya" class="form-control input has-required" required="true">
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

                                          </div>
                                       </div>   

                                    </div>

                                    <br>
                                 </div><hr> <input type="button" name="previous" class="previous action-button-previous btn btn-primary confirm" value="Previous" /> 
                              </fieldset>
                        </div>
                     </div>
                  </div>
            </div>
         </div>
      </div>

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

<!--     
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
      ?> -->

      </div>
      </div>
      </div>

      <?php if($id_resume!=null){ ?>
      <div class="box box-solid">
         <div class="box-body" style="padding:20px;">
         <div class="row-form">
            <h3>Data pelaporan Insiden </h3>
            <div class="table-responsive">
            <table class="table table-bordered table-hover dataTable">
               <thead class="header-data"> 
               <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Pelapor</th>
                  <th>Lokasi</th>
                  <th>Mekanisme Kecelakaan</th>
                  <th>Tingkat Keparahan</th>
                  <th>Jml Korban</th>
                  <th>Bantuan</th>
                  <!-- <th><i class="fa fa-gear"></i></th> -->
               </tr>
               </thead>
               <tbody>

                  <!-- <tr><td colspan='4' style='text-align:center;'>Data Kosong</td></tr>         -->
                  <?php
                  $no=1;
                  for ($i=0; $i < count($data_pelaporan); $i++) { 
                  ?>
                  <tr>
                     <td><?php echo $no; ?></td>
                     <td><?php echo $data_pelaporan[$i]->tglpelapor; ?></td>
                     <td><?php echo $data_pelaporan[$i]->pelapor; ?></td>
                     <td><?php echo $data_pelaporan[$i]->lokasi; ?></td>
                     <td><?php echo $data_pelaporan[$i]->jeniskecelakaan; ?></td>
                     <td><?php echo $data_pelaporan[$i]->keparahan; ?></td>
                     <td><?php echo $data_pelaporan[$i]->jumlah_korban; ?></td>
                     <td><?php echo $data_pelaporan[$i]->bantuan; ?></td>
                  </tr>
                  <?php
                  $no++;
                  }
                  ?>
               </tbody>
            </table>
            </div>
         </div>
         </div>
      </div>
      <?php } ?>

      <!-- </form> -->
     </div>
   </div><!-- box -->

<?php 
$step = 0;
if($step_position!=null){
   $step = $step_position;
}
?>
<script>

$(document).ready(function(){
  $(".nav-tabs a").click(function(){
    $(this).tab('show');
  });
  $('.nav-tabs a').on('shown.bs.tab', function(event){
    var x = $(event.target).text();         // active tab
    var y = $(event.relatedTarget).text();  // previous tab
    $(".act span").text(x);
    $(".prev span").text(y);
  });
});


var index = ["data_insiden","data_korban","alat_terlibat","tindakan","confirm"];
var forms = ["form_insiden","form_korban","form_alat","form_tindakan"];
var i = 0;
var url = window.location.pathname;

window.onload = function() {
  var stepposition = <?php echo $step; ?>;
  active_step(stepposition);
}

$(document).ready(function(){


var current_fs, next_fs, previous_fs; //fieldsets
var opacity;

$(".next").click(function(){

   var form = $("#"+forms[i]).serialize();
   current_fs = $(this).parent();
   next_fs = $(this).parent().next();

   $.ajax({
      type: "POST",
      data: form+"&simpan="+forms[i],
      url: url,
      success: function(msg){
         alert(msg);
         console.log(msg);

         if(i==4){
            location.reload();
         }else{
            //Add Class Active
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
            step: function(now) {
            // for making fielset appear animation
            opacity = 1 - now;

            current_fs.css({
            'display': 'none',
            'position': 'relative'
            });
            next_fs.css({'opacity': opacity});
            },
            duration: 600
            });
         }
      }
   });

   // alert(form);

   i++;

});

$(".previous").click(function(){

   current_fs = $(this).parent();
   previous_fs = $(this).parent().prev();

   //Remove class active
   $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

   //show the previous fieldset
   previous_fs.show();

   //hide the current fieldset with style
   current_fs.animate({opacity: 0}, {
   step: function(now) {
   // for making fielset appear animation
   opacity = 1 - now;

   current_fs.css({
   'display': 'none',
   'position': 'relative'
   });
   previous_fs.css({'opacity': opacity});
   },
   duration: 600
   });

   // var form = $("#"+forms[i]).serialize();

   // $.ajax({
   //    type: "POST",
   //    data: form+"&simpan="+forms[i],
   //    url: "index.php",
   //    success: function(msg){
   //       alert(msg);
   //    }
   // });

   i--;
});

$('.radio-group .radio').click(function(){
$(this).parent().find('.radio').removeClass('selected');
$(this).addClass('selected');
});

$(".submit").click(function(){
return false;
})

});

function active_step(menu){
   current_fs = $("."+index[i]).parent();
   next_fs = $("."+index[menu]).parent();

   //Add Class Active
   for (let a = 0; a <= menu; a++) {
      var fs = $("."+index[a]).parent();
      $("#progressbar li").eq($("fieldset").index(fs)).addClass("active");
   }

   //show the next fieldset
   next_fs.show();

   if(menu!=0){
      //hide the current fieldset with style
      current_fs.animate({opacity: 0}, {
      step: function(now) {
      // for making fielset appear animation
      opacity = 1 - now;

      current_fs.css({
      'display': 'none',
      'position': 'relative'
      });
      next_fs.css({'opacity': opacity});
      },
      duration: 600
      });
   }   

   i=menu;
}

// function confrm(){
//    if (confirm("Data Sudah Berstatus 'Done' Anda Yakin Untuk Merubah Data?")) {
//       document.getElementById("simpan").click();
//     }
//     return false;
// }
// function progress(){
//    if (confirm("Anda Yakin Unituk Mengganti Status On Progress?")) {
//       document.getElementById("progress").click();
//     }
//     return false;
// }
// function done(){
//    if (confirm("Anda Yakin Untuk Mengubah Status Done?")) {
//       document.getElementById("done").click();
//     }
//     return false;
// }


setTimeout(function() {
   new SlimSelect({
      select: '#kondisikerja'
   })
   new SlimSelect({
      select: '#faktorkerja'
   })
   new SlimSelect({
      select: '#faktorpribadi'
   })
   new SlimSelect({
      select: '#kondisikerja2'
   })
   new SlimSelect({
      select: '#faktorkerja2'
   })
   new SlimSelect({
      select: '#faktorpribadi2'
   })
}, 300)

</script>
<script src="<?php echo $theme_path;?>slim-select-master/dist/slimselect.min.js"></script>