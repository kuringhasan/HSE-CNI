<script>
$(function() {

		$("#Upload").button();
		/*$("#Upload").click(function(){
				$.ajax({
                		 	type	:'POST',
                			dataType:'html',
                			url		:urla,
                			data	:'aksi='+aksi+'&'+$("#frmBA").serialize(),
                			success	:function(msg){
                			   $("#daftar-ruang").html("ek");
                			
                			}
        		    });
			   
		   });
		});*/
});
</script>

<?php
     if ($_POST['Upload']){
		 echo "<div class='admin_info' style='padding:5px 5px 5px 5px;'>";
		echo "<b>".$Notice."</b><br>";
		echo "<font color='red'>".$nmFile."</font>";
		echo "</font>";
	} else
	{
		formuplod();
	}
	

	
 function formuplod(){
	
?>
<div class="admin_info" >
<form name="form1" method="post" enctype="multipart/form-data" action="">
<table width="514" border="0" cellpadding="2" cellspacing="3" >
  
  <tr>
    <td width="504" valign="middle" headers="100">
	<div class="biasa" align="center" >
		<table width="473" border="0" cellpadding="0" cellspacing="2">
		  
		  <tr>
			<td width="131" align="right">File  Foto </td>
			<td width="5">&nbsp;</td>
			<td width="329"><label>
			  <input name="fileFoto" type="file" id="fileFoto" >
			  <input name="Upload" type="submit" id="Upload" value="Upload" />
			</label></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			Max. 200kb 
			</td>
		  </tr>
		</table>
     </div>
   </td >
</tr>
</table>

</form>
</div>
<?php 
}
?>
<div id="konfirmupload">
</div>