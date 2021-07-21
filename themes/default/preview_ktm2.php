  <script type="text/javascript" src="<?php echo $theme_path;?>webcam/jcrop/js/jquery.min.js"></script>

  <script type="text/javascript" src="<?php echo $theme_path;?>webcam/jcrop/js/jquery.Jcrop.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo $theme_path;?>webcam/jcrop/css/jquery.Jcrop.css" />
  
 <script>

  var $k = jQuery.noConflict();
	 $k(function () {
		
			

	});
	  // Simple event handler, called from onChange and onSelect
  // event handlers, as per the Jcrop invocation above
  function updateCoords(c)
  {
	  alert(c.x);
    $k('#x').val(c.x);
    $k('#y').val(c.y);
    $k('#w').val(c.w);
    $k('#h').val(c.h);
  };

  function checkCoords()
  {
    if (parseInt($k('#w').val())) return true;
    alert('Pilih area gambar yang akan diambil, kemudian klik  Crop Image');
    return false;
  };
  function clearCoords()
  {
    $k('#coords input').val('');
  };

       
 
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
  
 
 
   
  <div class="col-lg-12" style="text-align:center">
  <iframe id="frame_capture" name="frame_capture" style="width:255px;height:415px;border:1px solid #666;"></iframe>
  
  		
  </div>
  <div class="col-lg-12" style="text-align:center">
   <iframe id="frame_capture_logistik" name="frame_capture_logistik" style="width:415px;height:255px;border:1px solid #666;"></iframe>
  
  		
  </div>
 
</div>