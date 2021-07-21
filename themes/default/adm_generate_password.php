<script>
$(function() {
	 $("#generate1").button();
	  $("#generate1").click(function(){
						
		var url_gen='<?=$url_generate;?>';
		
		$("#loader").show();
		$.ajax({
			   type:'POST',
			   dataType:'html',
			   url:url_gen,
			   
			   success:function(msg){
			       $("#keterangan").html(msg);	
				   $("#loader").hide();
			   }///akhisr sukses
		   });
		});
 });
			 
</script>

<button type="button"  name="generate1" id="generate1">Generate</button>
<img src="<?php echo $theme_path;?>images/loader-horizontal.gif" name="loader" id="loader"  style="display:none;" />
<div id="keterangan"></div>