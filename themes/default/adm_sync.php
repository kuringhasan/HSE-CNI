    <style type='text/css'>
        #divProgress{
            border:2px solid #ddd; 
            padding:10px; 
            width:100%; 
            height:265px; 
            margin-top: 20px;
            overflow:auto; 
            background:#f5f5f5;
			font-size:13px;
			font-family:"Trebuchet MS";
        }

        #progress_wrapper{
            border:2px solid #ddd;
            width:321px; 
            height:20px; 
            overflow:auto; 
            background:#f5f5f5;
            margin-top: 10px;
        }

        #progressor{
            background:#07c; 
            width:0%; 
            height:100%;
            -webkit-transition: all 1s linear;
            -moz-transition: all 1s linear;
            -o-transition: all 1s linear;
            transition: all 1s linear; 

        }

        .demo_container{
            width: 80%;
            margin:0 auto;
            padding: 30px;
            background: #FFF;
            margin-top: 50px;
        }

        .my-btn, .my-btn2{
            width: 200px;
            margin-top: 22px;
            margin-bottom: 5px;
            float: none;
            display: block;
        }

        h1{
            font-size: 22px;
            margin-bottom: 20px;
        }

        .float_left{
        	width: 48%;
            float: left;
        }

        .float_right{
        	width: 48%;
            float: right;
        }

        .demo_container::after {
            content: "";
            clear: both;
            display: block;
        }

        .ghost-btn {
            display: inline-block;
            text-decoration: none;
            border: 2px solid #3b8dbd;
            line-height: 15px;
            color: #3b8dbd;
            -webkit-border-radius: 3px;
            -webkit-background-clip: padding-box;
            -moz-border-radius: 3px;
            -moz-background-clip: padding;
            border-radius: 3px;
            background-clip: padding-box;
            font-size: 15px;
            padding: .6em 1.5em;
            -webkit-transition: all 0.2s ease-out;
            -moz-transition: all 0.2s ease-out;
            -o-transition: all 0.2s ease-out;
            transition: all 0.2s ease-out;
            background: #ffffff;
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
            cursor: pointer;
            zoom: 1;
            -webkit-backface-visibility: hidden;
            position: relative;
            margin-right: 10px;
        }
        .ghost-btn:hover {
            -webkit-transition: 0.2s ease;
            -moz-transition: 0.2s ease;
            -o-transition: 0.2s ease;
            transition: 0.2s ease;
            background-color: #3b8dbd;
            color: #ffffff;
        }

        .ghost-btn.active {
            border: 2px solid #D23725;
            color: #D23725;
        }

        .ghost-btn.active:hover {
             border: 2px solid #D23725;
             background: #FFF;
        }

        .ghost-btn:focus {
            outline: none;
        }
        .method_wrappers{
            margin-bottom: 20px;
        }
		.row-form{
			float:left;
			display:inline-block;
			width:100%;
			margin-bottom:0px;
			
		}
    </style>

    <script> 
	 function check_sync(){
            ifrm = document.createElement("IFRAME"); 
            ifrm.setAttribute("src", "<?php echo $url_check_sync;?>"); 
			//alert("<?php echo $url_check_sync;?>");
            ifrm.style.width = 0+"px"; 
            ifrm.style.height = 0+"px"; 
            ifrm.style.border = 0; 
            document.body.appendChild(ifrm); 
			//document.getElementById("media-sync").appendChild(ifrm);
        }    
		//window.setInterval(check_sync, 60000);//300000=5meni
        function ajax_stream(){
            ifrm = document.createElement("IFRAME"); 
			var pilih=document.getElementById("pilih_sync").value;
			if(pilih=="tpk"){
				
            	ifrm.setAttribute("src", "<?php echo $url_sync_tpk;?>"); 
			}
			if(pilih=="kelompok"){
            	ifrm.setAttribute("src", "<?php echo $url_sync_kelompok;?>"); 
			}
			if(pilih=="kelompok_harga"){
            	ifrm.setAttribute("src", "<?php echo $url_sync_kelompok_harga;?>"); 
			}
			if(pilih=="products"){
            	ifrm.setAttribute("src", "<?php echo $url_sync_products;?>"); 
			}
			
			if(pilih=="members"){
            	ifrm.setAttribute("src", "<?php echo $url_sync_members;?>"); 
			}
			if(pilih=="sales"){
				
            	ifrm.setAttribute("src", "<?php echo $url_sync_sales;?>"); 
			}
            ifrm.style.width = 0+"px"; 
            ifrm.style.height = 0+"px"; 
            ifrm.style.border = 0; 
            document.body.appendChild(ifrm);
			
        }
		//window.setInterval(ajax_stream, 600000);//300000=5meni
	 function simpan(dta){
		
		var nilai= dta.value;
		
		var hsl = $.ajax({
				type: "POST",
				url:'<?php echo "save.php";?>',
				data:"db="+$("#database_name").val()
			}).responseText;
		 $("#label_database").show();
		 $("#database_name").hide();
	}
	
    </script>
     <div id="media-sync"></div>
    <div class="demo_container" >
     <?php echo"<pre>"; print_r($versi);echo"</pre>";  ?>
        <h1><strong><i><?php echo $is_connected_odoo?"Connected to Odoo Server ".$url_odoo:"Not connected to Odoo";?></i></strong><br />
        Untuk melakukan sinronikasi manual, klik tombol <strong style="color:#F00">Sinkronisasi..</strong> </h1>
        <div class='float_left'>
           
             
        </div>
        <div class='float_right'>
             
           
        </div>
    		 <select name="pilih" id="pilih_sync">
             <option value="">--pilih--</option>
              <option value="tpk">TPK</option>
              <option value="kelompok">Kelompok</option>
             <option value="kelompok_harga">Kelompok Harga</option>
             <option value="products">Semua Product</option>
             <option value="members">Anggota KPBS</option>
             <option value="sales">Sales/Penjualan Barang & Pakan</option>
             </select>
             <a onclick="ajax_stream();" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-refresh"></i> Sinkronisasi..</a>
             
             <a onclick="check_sync();" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-check"></i> Check Sync </a>
        <div id="divProgress"></div>
        <div class="row-form">
            <div id='progress_wrapper'  style="float:left">
                    <div id="progressor" ></div>
            </div>
       		 <div id="persen_id" style="float:right;width:49%;margin-top: 9px;">&nbsp;</div>
       </div>
      
    </div>
