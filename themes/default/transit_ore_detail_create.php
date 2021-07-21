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
// echo "<pre>";print_r($detail);echo "</pre>";
?>

<div class="responsive-form" >
 <form id="form-detail"   method="post" >
    <div class="row-form">
        <span class="label">Pilih Truck<small class="wajib">*</small></span>
        <select name="equipment_id" id="equipment_id" class="input" >
                <option value="">--Truck--</option>
                <?php
        
                foreach($trucks as $data) {
                    $truck=isset($_POST['truck'])?$_POST['truck']:$detail->equipment_id;
                    ?>
                    <option value="<?php echo $data->id;?>"  <?php echo $data->id==$detail->equipment_id?"selected":""; ?> >
                    <?php echo $data->name;?>
                    </option>
                <?php
                }
                ?>
        </select>
    </div>
    <div class="row-form">
        <span class="label">Pilih Tujuan<small class="wajib">*</small></span>
        <select name="tujuan_pengangkutan" id="tujuan_pengangkutan" class="input" >
            <option value="">--Tujuan--</option>
            <option value="BRG" <?php 'BRG'==$detail->tujuan?"selected":""; ?> >BRG</option>
            <option value="ETO" <?php 'ETO'==$detail->tujuan?"selected":""; ?> >ETO</option>
            <option value="EFO" <?php 'EFO'==$detail->tujuan?"selected":""; ?> >EFO</option>
        </select>
    </div>

<script>
    $('#tujuan_pengangkutan').on('change', function() {
        // var tujuan = $('#tujuan_pengangkutan').val();
        var tujuan = this.value ;
        switch (tujuan) {
            case 'BRG':
                $('#pilihbarge').css('display', 'inline-block');
                $('#pilihdome').css('display', 'none');
                $('#pilihlokasidome').css('display', 'none');
                $('#dome_id').val(null);
                $('#lokasi_dome_id').val(null);
                break;
            case 'EFO':
                $('#pilihdome').css('display', 'inline-block');
                $('#pilihlokasidome').css('display', 'inline-block');
                $('#pilihbarge').css('display', 'none');
                $('#barge_id').val(null);
                break;
            case 'ETO':
                $('#pilihdome').css('display', 'inline-block');
                $('#pilihlokasidome').css('display', 'inline-block');
                $('#pilihbarge').css('display', 'none');
                $('#barge_id').val(null);
                
                break;
            default:
                break;
        }
    });
</script>

    <div class="row-form" id="pilihbarge" style="display: none;">
        <span class="label">Pilih Barge</small></span>
        <select name="barge_id" id="barge_id" class="input" >
                <option value="">--Barges--</option>
                <?php
                foreach($barges as $data) {
                    $barges=isset($_POST['barges'])?$_POST['barges']:$detail->barge_id;
                    ?>
                    <option value="<?php echo $data->id;?>"  <?php echo $data->id==$detail->barge_id?"selected":""; ?> >
                    <?php echo $data->name;?>
                    </option>
                <?php
                }
                ?>
        </select>
    </div>
    <div class="row-form" id="pilihdome" style="display: none;">
        <span class="label">Pilih Dome</small></span>
        <select name="dome_id" id="dome_id" class="input" >
                <option value="">--Dome--</option>
                <?php
                foreach($domes as $data) {
                    $barges=isset($_POST['domes'])?$_POST['domes']:$detail->dome_id;
                    ?>
                    <option value="<?php echo $data->id;?>"  <?php echo $data->id==$detail->dome_id?"selected":""; ?> >
                    <?php echo $data->name;?>
                    </option>
                <?php
                }
                ?>
        </select>
    </div>
    <div class="row-form" id="pilihlokasidome" style="display: none;">
        <span class="label">Pilih Lokasi Dome</small></span>
        <select name="lokasi_dome_id" id="lokasi_dome_id" class="lokasi_dome_id input" >
                <option value="">--Lokasi Dome--</option>
                <?php
                foreach($domeLocations as $data) {
                    $domeLocations=isset($_POST['domeLocations'])?$_POST['domeLocations']:$detail->lokasi_dome_id;
                    ?>
                    <option value="<?php echo $data->id;?>"  <?php echo $data->id==$detail->lokasi_dome_id?"selected":""; ?> >
                    <?php echo $data->location_name;?>
                    </option>
                <?php
                }
                ?>
        </select>
    </div>
    <div class="row-form">
        <span class="label">Jumlah Ritase<small class="wajib">*</small></span>
        <input type="number" class="input" name="ritase" id="ritase" placeholder="Jumlah Ritase"  size="35" value="<?php echo $detail->ritase;?>"/>
    </div>
    <div class="row-form">
        <span class="label">Qty (Ton)<small class="wajib">*</small></span>
        <input type="number" class="input" name="quantity" id="quantity" placeholder="Qty (Ton)"  size="35" value="<?php echo $detail->quantity;?>"/>
    </div>

    <input type="hidden" class="input" name="transit_ore_id" id="transit_ore_id" value="<?php echo $transit_ore_id;?>"/>
    
</form>
</div>
