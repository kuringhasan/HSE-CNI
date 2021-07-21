<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<!-- bootstrap datepicker -->
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
    
    function errorForm(msj_obj){
            if (jQuery.isEmptyObject(msj_obj)==false)
            {
                var errors=msj_obj;
                $m('.row-error').remove();
                for (var key in errors){
                    $m("#"+key).addClass("error");
                
                    $m("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
                    
                    
                }
            }
            
    }

    // function setTextField(ddl) {
    //     document.getElementById('tipe_text').value = ddl.options[ddl.selectedIndex].text;
    // }
</script>
<style>
/* .responsive-form #frmBulanLahir{
	margin-left:5px;
}
.responsive-form #frmTahunLahir{
	margin-left:5px;
}
.wajib{
	color:#F00;
}
@media screen and (max-width: 500px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
}
@media screen and (max-width: 320px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
} */
</style>
<?php
// echo "<pre>";print_r($detail->detail);echo "</pre>";
?>

<div class="responsive-form">
 <form id="form_input_data"   method="post" >
    <div class="row-form">
        <span class="label">Tanggal<small class="wajib">*</small></span>
        <input type="date" class="input" name="tanggal" id="tanggal" placeholder="Tanggal"  size="35" value="<?php echo $detail->tanggal['date'];?>"/>
    </div>
    <div class="row-form">
        <span class="label">Pilih Shit<small class="wajib">*</small></span>
        <select name="shift" id="shift" class="input" >
                <option value="">--Shift--</option>
                <option value="1"  <?php if($detail->shift==1){echo "selected";} ?> >1</option>
                <option value="2"  <?php if($detail->shift==2){echo "selected";} ?> >2</option>
                <option value="2"  <?php if($detail->shift==3){echo "selected";} ?> >3</option>
        </select>
    </div>
    <div class="row-form">
        <span class="label">Pilih Lokasi PIT<small class="wajib">*</small></span>
        <select name="lokasi_pit" id="lokasi_pit" class="input" >
                <?php
                echo '<option value="">--Lokasi PIT--</option>';
            
                $List=$list_lokasi_pit;
                while($data = each($List)) {
                    $lokasi_pit=isset($_POST['lokasi_pit'])?$_POST['lokasi_pit']:$detail->pit_id;
                    ?>
                    <option value="<?php echo $data['key'];?>"  <?php if($detail->pit_id==$data['key']){echo "selected";} ?> >
                    <?php echo $data['value'];?>
                    </option>
                <?php
                }
                ?>
        </select>
    </div>
    <div class="row-form">
        <span class="label">Pilih Kontraktor<small class="wajib">*</small></span>
        <select name="kontraktor" id="kontraktor" class="input" >
                <?php
                echo '<option value="">--Kontraktor--</option>';
            
                $List=$list_kontraktor;
                while($data = each($List)) {
                    $kontraktor=isset($_POST['kontraktor'])?$_POST['kontraktor']:$detail->contractor_id;
                    ?>
                    <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$detail->contractor_id?"selected":""; ?> >
                    <?php echo $data['value'];?>
                    </option>
                <?php
                }
                ?>
        </select>
    </div>
    <div class="row-form">
            <span class="label">Pilih Tujuan<small class="wajib">*</small></span>
            <select onchange="
            var tujuan = this.value ;
            switch (tujuan) {
                case 'BRG':
                    $('.pilihbarge').css('display', 'inline-block');
                    $('.pilihdome').css('display', 'none');
                    $('.pilihlokasidome').css('display', 'none');
                    $('.dome_id').val(null);
                    $('.lokasi_dome_id').val(null);
                    break;
                case 'EFO':
                    $('.pilihdome').css('display', 'none');
                    $('.pilihlokasidome').css('display', 'inline-block');
                    $('.pilihbarge').css('display', 'none');
                    $('.EFO').css('display', 'inline-block');
                    $('.ETO').css('display', 'none');
                    $('.lokasi_dome_id').val(null);
                    $('.barge_id').val(null);
                    break;
                case 'ETO':
                    $('.pilihdome').css('display', 'none');
                    $('.pilihlokasidome').css('display', 'inline-block');
                    $('.pilihbarge').css('display', 'none');
                    $('.ETO').css('display', 'inline-block');
                    $('.EFO').css('display', 'none');
                    $('.lokasi_dome_id').val(null);
                    $('.barge_id').val(null);
                    
                    break;
                default:
                    break;
            }
            " name="tujuan_pengangkutan" id="tujuan_pengangkutan" class="input tujuan_pengangkutan" >
                <option value="">--Tujuan--</option>
                <option value="BRG" <?php 'BRG'==$detail->tujuan?"selected":""; ?> >BARGE</option>
                <option value="ETO" <?php 'ETO'==$detail->tujuan?"selected":""; ?> >ETO</option>
                <option value="EFO" <?php 'EFO'==$detail->tujuan?"selected":""; ?> >EFO</option>
            </select>
        </div>

        <div class="pilihbarge row-form" style="display:none;">
            <span class="label">Pilih Barge</small></span>
            <select name="barge_id" id="barge_id" class=" barge_id input" >
                    <option value="">--Barges--</option>
                    <?php
                    foreach($barges as $data) {
                        $barges=isset($_POST['barges'])?$_POST['barges']:$detail->barge_id;
                        ?>
                        <option value="<?php echo "$data->id/$data->name";?>"  <?php echo $data->id==$detail->barge_id?"selected":""; ?> >
                        <?php echo $data->name;?>
                        </option>
                    <?php
                    }
                    ?>
            </select>
        </div>

        <div class="pilihlokasidome row-form" style="display: none;">
            <span class="label">Pilih Lokasi Dome</small></span>
            <select  onchange="
            var lokasi = this.value ;
            $('.pilihdome').css('display', 'inline-block');
            $('.lokasi').css('display', 'none');
            $('.lokasi_'+lokasi).css('display', 'inline-block');
            $('.dome_id').val(null);
            " name="lokasi_dome_id" id="lokasi_dome_id" class="lokasi_dome_id input" >
                    <option value="">--Lokasi Dome--</option>
                    <?php
                    foreach($domeLocations as $data) {
                        $domeLocations=isset($_POST['domeLocations'])?$_POST['domeLocations']:$detail->lokasi_dome_id;
                        ?>
                        <option value="<?php echo $data->id;?>"  <?php echo $data->id==$detail->lokasi_dome_id?"selected":""; ?> class="<?php echo $data->eto_efo; ?>" >
                        <?php echo $data->location_name;?>
                        </option>
                    <?php
                    }
                    ?>
            </select>
        </div>
<?php
//  var_dump($domes); 
 ?>

        <div class="pilihdome row-form"  style="display: none;">
            <span class="label">Pilih Dome</small></span>
            <select name="dome_id" id="dome_id" class="dome_id input" >
                    <option value="">--Dome--</option>
                    <?php
                    foreach($domes as $data) {
                        $domes=isset($_POST['domes'])?$_POST['domes']:$detail->dome_id;
                        ?>
                        <option value="<?php echo "$data->id/$data->name";?>"  <?php echo $data->id==$detail->dome_id?"selected":""; ?> style="display: none;" class="lokasi lokasi_<?php echo $data->location_id ?>">
                        <?php echo $data->name;?>
                        </option>
                    <?php
                    }
                    ?>
            </select>
        </div>

</form>
    <!-- <div class="row-form">
        <span class="label">Entry Time<small class="wajib">*</small></span>
        <input type="datetime-local" class="input" name="waktu_masuk" id="waktu_masuk" placeholder="Entry Time"  size="35" value="<?php echo $detail->entry_time;?>"/>
    </div>
    <div class="row-form">
        <span class="label">Sent Time<small class="wajib">*</small></span>
        <input type="datetime-local" class="input" name="waktu_kirim" id="waktu_kirim" placeholder="Sent Time"  size="35" value="<?php echo $detail->sent_time;?>"/>
    </div>
    <div class="row-form">
        <span class="label">Received Time<small class="wajib">*</small></span>
        <input type="datetime-local" class="input" name="waktu_terima" id="waktu_terima" placeholder="Received Time"  size="35" value="<?php echo $detail->received_time;?>"/>
    </div> -->
    <br>

    <!-- <a onclick="tbhritase()" class="btn btn-warning btn-xs">Tambah Ritase</a> -->
    <button onclick="tbhritase()" type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#ModalRitase">
    Tambah Ritase
    </button>
    <script>

    var rts = <?php echo json_encode($detail->detail) ?>;
    var ritases = (rts!=null)?rts:[];
    // var ritases = [];
    $( document ).ready(function() {
        var dtaritase = "";
        for (let index = 0; index < ritases.length; index++) {
            var bargename = (ritases[index]['barge_name']!='-')?ritases[index]['barge_name']:"";
            var domename = (ritases[index]['dome_name']!='-')?ritases[index]['dome_name']:"";
            dtaritase += "<tr><td>"+ritases[index]['tujuan_pengangkutan']+"("+bargename+domename+")</td>"+
                        "<td>"+ritases[index]['equipment_name']+"</td>"+
                        "<td>"+ritases[index]['ritase']+"</td>"+
                        "<td><a class='btn btn-primary btn-xs' onclick='hapustbhritase("+index+")'><i class='fa fa-trash'></i></a></td></tr>";
        }
        document.getElementById("dtaritase").innerHTML = dtaritase;
    });
    function tambahtbhritase(){
        var tujuan_pengangkutan = $('.tujuan_pengangkutan:last').val();
        
        var equipment = $('.equipment_id:last').val().split("-");
        var barge = ($('.barge_id:last').val() != "") ? $('.barge_id:last').val().split("/") : [];
        var dome = ($('.dome_id:last').val() != "") ? $('.dome_id:last').val().split("/") : [];

        var equipment_id = equipment[0];
        var barge_id = (barge.length == 0) ? null : barge[0];
        var dome_id = (dome.length == 0) ? null : dome[0];

        var equipment_name = equipment[1];
        var barge_name = (barge.length == 0) ? "" : barge[1];
        var dome_name = (dome.length == 0) ? "" : dome[1];
        
        var ritase = $('.ritase:last').val();
        var quantity = $('.quantity:last').val();

        var ritase = {
            'tujuan_pengangkutan' : tujuan_pengangkutan,
            'truck_id' : equipment_id,
            'barge_id' : barge_id,
            'dome_id' : dome_id,
            'equipment_name' : equipment_name,
            'barge_name' : barge_name,
            'dome_name' : dome_name,
            'ritase' : ritase,
            'quantity' : quantity,
        };

        ritases.push(ritase);
        var dtaritase = "";
        for (let index = 0; index < ritases.length; index++) {
            var bargename = (ritases[index]['barge_name']!='-')?ritases[index]['barge_name']:"";
            var domename = (ritases[index]['dome_name']!='-')?ritases[index]['dome_name']:"";
            dtaritase += "<tr><td>"+ritases[index]['tujuan_pengangkutan']+"("+bargename+domename+")</td>"+
                        "<td>"+ritases[index]['equipment_name']+"</td>"+
                        "<td>"+ritases[index]['ritase']+"</td>"+
                        "<td><a class='btn btn-primary btn-xs' onclick='hapustbhritase("+index+")'><i class='fa fa-trash'></i></a></td></tr>";
        }
        document.getElementById("dtaritase").innerHTML = dtaritase;
        // $('.dome_id:last').val(null);
        $('.lokasi_dome_id:last').val(null);
        $('.barge_id:last').val(null);
        $('.equipment_id:last').val(null);
        $('.tujuan_pengangkutan:last').val(null);
        $('.ritase:last').val(null);
        $('.quantity:last').val(null);
        $('.tbhritase:last').css('display', 'none');
        
    }
    function hapustbhritase(index){
        ritases.splice(index, 1);
        console.log(ritases);
        var dtaritase = "";
        for (let index = 0; index < ritases.length; index++) {
            dtaritase += "<tr><td>"+ritases[index]['tujuan_pengangkutan']+"("+ritases[index]['barge_name']+ritases[index]['dome_name']+")</td>"+
                        "<td>"+ritases[index]['equipment_name']+"</td>"+
                        "<td>"+ritases[index]['ritase']+"</td>"+
                        "<td><a class='btn btn-primary btn-xs' onclick='hapustbhritase("+index+")'><i class='fa fa-trash'></i></a></td></tr>";
        }
        document.getElementById("dtaritase").innerHTML = dtaritase;
    }
    function tbhritase(){
        // $('#tbhritase').css('display', 'inline-block');
        var tbritase=  $('#tbhritase').html();
        $j('#ModalRitase .modal-body').html(tbritase);
        $j('#ModalRitase .modal-footer').html(
        "<button type='button' class='btn btn-primary btn-xs' data-dismiss='modal'>Batal</button>"+
        "<a class='btn btn-primary btn-xs' onclick='tambahtbhritase()'>Tambah</a>");
        
    }
    
    function exittbhritase(){
        $('#tbhritase').css('display', 'none');
    }

    // $('.tujuan_pengangkutan').on('change', function() {
    //     // var tujuan = $('#tujuan_pengangkutan').val();
    //     var tujuan = this.value ;
    //     switch (tujuan) {
    //         case 'BRG':
    //             $('#pilihbarge').css('display', 'inline-block');
    //             $('#pilihdome').css('display', 'none');
    //             $('#pilihlokasidome').css('display', 'none');
    //             $('#dome_id').val(null);
    //             $('#lokasi_dome_id').val(null);
    //             break;
    //         case 'EFO':
    //             $('#pilihdome').css('display', 'inline-block');
    //             $('#pilihlokasidome').css('display', 'inline-block');
    //             $('#pilihbarge').css('display', 'none');
    //             $('#barge_id').val(null);
    //             break;
    //         case 'ETO':
    //             $('#pilihdome').css('display', 'inline-block');
    //             $('#pilihlokasidome').css('display', 'inline-block');
    //             $('#pilihbarge').css('display', 'none');
    //             $('#barge_id').val(null);
                
    //             break;
    //         default:
    //             break;
    //     }
    // });

    </script>

    <br>
    
    <div id="tbhritase" style="display:none">
    <div style="padding:15px;" class="responsive-form">
        <div class="row-form">
            <span class="label">Pilih Truck<small class="wajib">*</small></span>
            <select name="equipment_id" id="equipment_id" class="input equipment_id" >
                    <option value="">--Truck--</option>
                    <?php
            
                    foreach($trucks as $data) {
                        $truck=isset($_POST['truck'])?$_POST['truck']:$detail->equipment_id;
                        ?>
                        <option value="<?php echo "$data->id-$data->name";?>"  <?php echo $data->id==$detail->equipment_id?"selected":""; ?> >
                        <?php echo $data->name;?>
                        </option>
                    <?php
                    }
                    ?>
            </select>
        </div>
        
        <div class="row-form">
            <span class="label">Jumlah Ritase<small class="wajib">*</small></span>
            <input type="number" class="ritase input" name="ritase" id="ritase" placeholder="Jumlah Ritase"  size="35" value="<?php echo $detail->ritase;?>"/>
        </div>
        <div class="row-form">
            <span class="label">Qty (Ton)<small class="wajib">*</small></span>
            <input type="number" class="quantity input" name="quantity" id="quantity" placeholder="Qty (Ton)"  size="35" value="<?php echo $detail->quantity;?>"/>
        </div>

    </div>
    </div>


    <center><h5>LIST RITASE</h5></center>
    <table class="table table-bordered table-hover dataTable">
        <thead class="header-data"> 
        <tr>
            <th>Tujuan</th>
            <th>Truck</th>
            <th>Ritase</th>
            <th><i class="fa fa-gear"></i></th>
        </tr>
        </thead>
        <tbody id="dtaritase">
            <tr><td colspan='4' style='text-align:center;'>Data Kosong</td></tr>        
        </tbody>
    </table>






</div>

<script>
document.ready(function(){
    document.getElementById('tujuan_pengangkutan').onChange = function(){alert('Test')};
});
</script>