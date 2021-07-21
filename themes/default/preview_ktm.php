  <script type="text/javascript" src="<?php echo $theme_path;?>webcam/jcrop/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>webcam/webcam.min.js"></script>


  <script type="text/javascript" src="<?php echo $theme_path;?>webcam/jcrop/js/jquery.Jcrop.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo $theme_path;?>webcam/jcrop/css/jquery.Jcrop.css" />
  
 <script>

  var $j = jQuery.noConflict();
	 $j(function () {
				 
		  var jcrop_api;
			$j('#hasil').Jcrop({
			 	aspectRatio: 3/4,
      			onSelect: updateCoords
			},function(){
     
			  jcrop_api = this;
			});
			
			$j('#coords').on('change','input',function(e){
			  var x1 = $j('#x').val(),
				  x2 = $j('#x').val()+$j('#w').val(),
				  y1 = $j('#y').val(),
				  y2 = $j('#y').val()+$j('#h').val();
			  jcrop_api.setSelect([x1,y1,x2,y2]);
			});
			
			

	});
	  // Simple event handler, called from onChange and onSelect
  // event handlers, as per the Jcrop invocation above
  function updateCoords(c)
  {
	  alert(c.x);
    $j('#x').val(c.x);
    $j('#y').val(c.y);
    $j('#w').val(c.w);
    $j('#h').val(c.h);
  };

  function checkCoords()
  {
    if (parseInt($j('#w').val())) return true;
    alert('Pilih area gambar yang akan diambil, kemudian klik  Crop Image');
    return false;
  };
  function clearCoords()
  {
    $j('#coords input').val('');
  };

        // konfigursi webcam
        Webcam.set({
         width: 320,
			height: 240,
			
            image_format: 'jpg',
            jpeg_quality: 100
        });
        Webcam.attach( '#camera' );
 
        function preview() {
            // untuk preview gambar sebelum di upload
            Webcam.freeze();
            // ganti display webcam menjadi none dan simpan menjadi terlihat
            document.getElementById('webcam').style.display = 'none';
            document.getElementById('simpan').style.display = '';
        }
        
        function batal() {
            // batal preview
            Webcam.unfreeze();
            
            // ganti display webcam dan simpan seperti semula
            document.getElementById('webcam').style.display = '';
            document.getElementById('simpan').style.display = 'none';
        }
   
        function simpan() {
            // ambil foto
		
            Webcam.snap( function(data_uri) {
                // upload foto
                Webcam.upload( data_uri, '<?php echo $url_upload;?>', function(code, text) {
																			  } );
 
                // tampilkan hasil gambar yang telah di ambil
              document.getElementById('hasil').innerHTML = '<img id =\"cth-crop-image\" src="'+data_uri+'"/>';
			// document.getElementById('preview-crop').innerHTML = '<img class="jcrop-preview"  src="'+data_uri+'"/>';
				//$j("#cek_aja").val('<?php echo $url_foto_tmp;?>');
			
                Webcam.unfreeze();
            
                document.getElementById('webcam').style.display = '';
                document.getElementById('simpan').style.display = 'none';
            } );
        }
    </script>
 <style type="text/css">

/* Apply these styles only when #preview-pane has
   been placed within the Jcrop widget */

.jcrop-holder #preview-pane {
 
  border: 1px rgba(0,0,0,.4) solid;
  background-color: white;

  -webkit-border-radius: 6px;
  -moz-border-radius: 6px;
  border-radius: 6px;

  -webkit-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
}

/* The Javascript code will set the aspect ratio of the crop
   area based on the size of the thumbnail preview,
   specified here */
#preview-pane .preview-container {

  overflow: hidden;
}

</style>
 <div class="row">
  <form id="coords"
    class="coords"
   	method="post" onsubmit="return checkCoords();"
    action="<?php echo $url_upload;?>/upload" target="frame_capture">
     <div class="col-lg-6" style="text-align:center">
        <div id="camera"  >Capture</div>
         	<input type="hidden" id="x" name="x" size="5"/>
			<input type="hidden" id="y" name="y"  size="5"/>
			<input type="hidden" id="w" name="w" size="5"/>
			<input type="hidden" id="h" name="h" size="5"/>
             
            <div id="webcam" style="margin:3px 3px 3px 3px;" >
            <button type=button onClick="preview()" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-camera"></i>&nbsp;Capture</button>
            </div>
            <div id="simpan" style="display:none;margin:3px 3px 3px 3px;">
             <button type=button onClick="batal()" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-remove"></i> Remove</button>
              <button  type=button onClick="simpan();return false;" id="btn-simpan" style="font-weight:bold;" class="btn btn-primary btn-xs">
              <i class="fa fa-fw fa-save"></i> Save</button>
            </div>
      
     </div>
    <div class="col-lg-6" style="text-align:center;float:right">
      <div id="hasil"><img id="cth-crop-image" src="<?php echo $url_foto_tmp;?>" title="test" /></div>
  		<button type="submit"  class="btn btn-primary btn-xs" style="margin:3px 3px 3px 3px;" ><i class="fa fa-fw fa-crop"></i> Crop Image</button>
    </div>
    </form>
   
  <div class="col-lg-12" style="text-align:center">
  <iframe id="frame_capture" name="frame_capture" style="width:255px;height:415px;border:1px solid #666;"></iframe>
  
  		
  </div>
   <iframe id="frame_capture" name="frame_capture" style="width:415px;height:255px;border:1px solid #666;"></iframe>
  
  		
  </div>
</div>