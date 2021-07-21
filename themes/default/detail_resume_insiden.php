<?php  //echo "<pre>"; print_r($detail);echo "</pre>";?>
<style>

table tr td{
	font-size:12px;
}

body {font-family: Arial;}

/* Style the tab */
.tab {
overflow: hidden;
border: 1px solid #ccc;
background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
background-color: inherit;
float: left;
border: none;
outline: none;
cursor: pointer;
padding: 14px 16px;
transition: 0.3s;
font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
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
    <div class="col-md-12">
    	<!-- About Me Box -->
          <div class="box box-primary">
         
            <!-- /.box-header -->
            <div class="box-body" >

                <div class="tab">
                        <button class="tablinks" onclick="openCity(event, 'korban')">Data korban</button>
                        <button class="tablinks" onclick="openCity(event, 'alat')">Data Alat Terlibat</button>
                        <button class="tablinks" onclick="openCity(event, 'insiden')">Data Insiden</button>
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
                                        <td><?php echo $datakorban[$i]->nama_korban; ?></td>
                                        <td><?php echo $datakorban[$i]->nik; ?></td>
                                        <td><?php echo $datakorban[$i]->atasan_langsung; ?></td>
                                        <td><?php echo $datakorban[$i]->umur; ?></td>
                                        <td><?php echo $datakorban[$i]->nama_jabatan; ?></td>
                                        <td><?php echo $datakorban[$i]->name_department; ?></td>
                                        <td><?php echo $datakorban[$i]->nama_masa_kerja; ?></td>
                                        <td><?php echo $datakorban[$i]->nama_bagian_luka; ?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                        </table>
                        </div>
                </div>

                <div id="alat" class="tabcontent">
                <h3>Data Alat Terlibat</h3>
                <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable">
                        <thead class="header-data"> 
                                <tr>
                                <th>Alat Terlibat</th>
                                </tr>
                        </thead>
                        <tbody>
                                <!-- <tr><td colspan='4' style='text-align:center;'>Data Kosong</td></tr>         -->
                                <?php 
                                for ($i=0; $i < count($dataalat); $i++) { 
                                ?>
                                <tr>
                                        <td><?php echo $dataalat[$i]->nama_alat_terlibat; ?></td>
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
                <table>
                        <tr>
                                <td>Kontraktor</td>
                                <td>:</td>
                                <td><?php echo $company?></td>
                        </tr>
                        <tr>
                                <td>No Register</td>
                                <td>:</td>
                                <td><?php echo $noregister?></td>
                        </tr>
                        <tr>
                                <td>Tanggal Dan Jam</td>
                                <td>:</td>
                                <td><?php echo $tanggal?></td>
                        </tr>
                        <tr>
                                <td>Keterangan Insiden</td>
                                <td>:</td>
                                <td><?php echo $keterangan?></td>
                        </tr>
                        <tr>
                                <td>Shift Kerja</td>
                                <td>:</td>
                                <td><?php echo $shiftkerja?></td>
                        </tr>
                        <tr>
                                <td>Area kerja</td>
                                <td>:</td>
                                <td><?php echo $areakerja?></td>
                        </tr>
                        <tr>
                                <td>Jenis Insiden</td>
                                <td>:</td>
                                <td><?php echo $jenisinsiden?></td>
                        </tr>
                        <tr>
                                <td>Cara Kerja</td>
                                <td>:</td>
                                <td><?php echo $carakerja?></td>
                        </tr>
                        <tr>
                                <td>Kondisi Kerja</td>
                                <td>:</td>
                                <td><?php echo $kondisikerja?></td>
                        </tr>
                        <tr>
                                <td>Faktor Pekerjaan</td>
                                <td>:</td>
                                <td><?php echo $faktorkerja?></td>
                        </tr>
                        <tr>
                                <td>Faktor Pribadi</td>
                                <td>:</td>
                                <td><?php echo $faktorpribadi?></td>
                        </tr>
                        <tr>
                                <td>Tindakan Perbaikan</td>
                                <td>:</td>
                                <td><?php echo $perbaikan?></td>
                        </tr>
                        <tr>
                                <td>Hari Kerja Hilang</td>
                                <td>:</td>
                                <td><?php echo $harihilang?></td>
                        </tr>
                        <tr>
                                <td>Perkiraan Biaya Perbaikan</td>
                                <td>:</td>
                                <td><?php echo $perkiraanbiaya?></td>
                        </tr>
                        <tr>
                                <td>Tgl Awal Progress</td>
                                <td>:</td>
                                <td><?php echo $tgl_awal_progress?></td>
                        </tr>
                        <tr>
                                <td>Tgl Selesai Progress</td>
                                <td>:</td>
                                <td><?php echo $tgl_selesai_progress?></td>
                        </tr>

                </table>
                </div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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
</script>