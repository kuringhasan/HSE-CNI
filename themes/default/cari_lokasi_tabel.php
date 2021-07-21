<link type="text/css" href="<?php echo $theme_path;?>css/tabel.css" rel="stylesheet" />
<script>
    $(function(){
     
    }) 
function pilihlokasi(kode,nama)
{
		 $('#label_kode_lokasi').html(kode);
		  $('#kode_lokasi_for_get').val(kode);
		  $('#label_nama_lokasi').html(nama);
		  $('#nama_lokasi_for_get').val(nama);
}
</script>
<style>
    .garis{
	   text-decoration: underline;
	   cursor: pointer;
	}
	.tengah{
		text-align:center;
	}
	.kiri{
		text-align:left;
	}
</style>
<?php

?>
<form action="<?php echo $action_simpan; ?>" method="post" name="form_listdata" id="form_listdata">
		
            <?php
			$jumlah_data=$listdata->JumlahData;
            if ($jumlah_data>0)
            {
            ?>
            <table  cellpadding="0" cellspacing="0" class="bordered" >
                <tr>
                	<th class="header" style="width:5%;">No</th>
                    <th class="header" style="width:12%;">Kode</th>
                    <th class="header" style="width:65%;">Lokasi</th>
                    <th class="header" style="width:10%;"></th>
                </tr>
            <?php
			$i=0;
			
			while($data=current($listdata->ListData))
			{
				$td = (($i+1) % 2 ) ? $td ="td1":"td2";
			?>
           <tr>
           <td class='<?php echo $td;?> tengah' style="text-align:center;"><?php echo $NoAwal+$i;?></td>
           	<td class='<?php echo $td;?> isi kiri'><?php echo $data->lbKode;?></td>
            <td class='<?php echo $td;?> isi kiri'><?php echo $data->LokasiBidangNama;?></td>
            <td class='<?php echo $td;?> isi tengah'>
            <button type="button" class="pilih"  onclick="pilihlokasi('<?php echo $data->lbKode;?>','<?php echo $data->LokasiBidangNama;?>');">Pilih</button>
        
			<script type="text/javascript">    
                $(function(){
                    $(".pilih").button({
                            text:true,
                            icons:{
                                 primary:'ui-icon-check'
                            }
                    });	
				  
                });
            </script>
            </td>
             <?php   
			$i++;
			next($listdata->ListData);
			}
			?>
            </table>
            <table style="width: 100%;" >
                <tr>
                    <td style="width: 50%; text-align:left;height:55px;">
                        <span style="vertical-align: middle;">
                            <?php
                            $hal = $_POST['hal']==''?1:$_POST['hal'];

                            if ($hal == $first)
                            {
                            ?>
                                <input type="button" class="button" value="first" name="first" readonly="readonly"/>
                            <?php
                            } else {
                            ?>
                                <input type="button" class="button" value="first" name="first" onmousedown="document.getElementById('hal').value='<?php echo $first; ?>';pilih_tampil()" />
                            <?php
                                }
                            ?>
                            <?php
                            if ($hal == $first || $jumlah_data <= $tampil) 
                            {
                            ?>
                                <input type="button" class="button" value="prev" name="prev" readonly="readonly"/>
                            <?php
                            } else {
                            ?>
                                <input type="button" class="button" value="prev" name="prev" onmousedown="document.getElementById('hal').value='<?php echo $prev; ?>';pilih_tampil()" />
                            <?php
                                }
                            ?>                    

                            <?php
                            if ($hal == $last || $jumlah_data <= $tampil) 
                            {
                            ?>
                                <input type="button" class="button" value="next" name="next" readonly="readonly" />
                            <?php

                            } else {
                            ?>
                                <input type="button" class="button" value="next" name="next" onmousedown="document.getElementById('hal').value='<?php echo $next; ?>';pilih_tampil()" />
                            <?php
                                }
                            ?>                        

                            <?php
                            if ($hal == $last)
                            {
                            ?>
                                <input type="button" class="button" value="last" name="last" readonly="readonly"/> 
                            <?php
                            } else {
                            ?>
                                <input type="button" class="button" value="last" name="last" onmousedown="document.getElementById('hal').value='<?php echo $last; ?>';pilih_tampil()" /> 
                            <?php
                            }
                            ?>                                   
                            Total data : <?php echo $jumlah_data; ?>
                        </span>
                        <img src="<?php echo url::base()."themes/default/images/loading50.gif"?>" style="border: none; vertical-align: middle; padding: 0; margin: 0;" class="hidden" id="spinner2"/>
                    </td>
                    <td style="text-align:right;">Tampilkan 
                        <select id="tampil" name="tampil" onchange="pilih_tampil()">
                            <option value="10" <?php echo $_POST['tampil']=='10'?"selected":""; ?> >10</option>
                            <option value="25" <?php echo $_POST['tampil']=='25'?"selected":""; ?> >25</option>
                            <option value="50" <?php echo $_POST['tampil']=='50'?"selected":""; ?> >50</option>
                        </select> Hal <select name="hal" id="hal" onchange="pilih_tampil()">
                            <?php
                        		for($i=1;$i<=$jmlpage;$i++) {
                   					?><option value="<?php echo $i;?>" <?php echo $i==$hal?"selected":""; ?> ><?php echo $i;?></option><?php
                        		}
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php
            }
            else
                echo "<div id='adminKosong'>Tidak Ada Data yang Ditampilkan.</div>";
            ?> 
</form>