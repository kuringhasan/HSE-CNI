    <style type='text/css'>
        #divProgress{
            border:2px solid #ddd; 
            padding:10px; 
            width:100%; 
            height:265px; 
            margin-top: 20px;
            overflow:auto; 
            background:#f5f5f5;
			font-size:11px;
			font-family:"Courier New";
        }
		#col-id{
			width:50px;
			display:inline-block;
		}
		#persen_id{
			
		}
 		#col-members{
			width:200px;
			display:inline-block;
			vertical-align:top;
			border-left:#000 solid 1px;
			margin-bottom:2px;
		}
		#col-tanggal{
			width:80px;
			display:inline-block;
			margin-bottom:2px;
		}
		#col-sapi{
			width:100px;
			display:inline-block;
		}
		#col-jenis{
			width:30px;
			display:inline-block;
			margin-bottom:2px;
		}
		#col-status{
			width:60px;
			display:inline-block;
			vertical-align:top;
			border-left:#000 solid 1px;
			margin-bottom:2px;
		}
		#col-persen{
			width:40px;
			display:inline-block;
			vertical-align:top;
			border-left:#000 solid 1px;
			margin-bottom:2px;
		}
		#col-message{
			width:280px;
			display:inline-block;
			vertical-align:top;
			border-left:#000 solid 1px;
			margin-bottom:2px;
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
            ifrm.setAttribute("src", "<?php echo $url_sync;?>"); 
			alert("<?php echo $url_sync;?>");
            ifrm.style.width = 0+"px"; 
            ifrm.style.height = 0+"px"; 
            ifrm.style.border = 0; 
            document.body.appendChild(ifrm); 
			//document.getElementById("media-sync").appendChild(ifrm);
        }    
		//window.setInterval(check_sync, 60000);//300000=5meni
        function ajax_stream(){
            ifrm = document.createElement("IFRAME"); 
			var pilih=document.getElementById("pilih_migrasi").value;
			if(pilih=="anggota"){
				
            	ifrm.setAttribute("src", "<?php echo $url_sync;?>"); 
			}
			if(pilih=="sapi"){
            	ifrm.setAttribute("src", "<?php echo $url_sync_sapi;?>"); 
			}
			if(pilih=="event"){
				//alert("<?php echo $url_sync_event;?>/event");
            	ifrm.setAttribute("src", "<?php echo $url_sync_event;?>/event"); 
			}
			
			
			if(pilih=="perkawinan"){
				//alert("<?php echo $url_sync_event;?>/perkawinan");
            	ifrm.setAttribute("src", "<?php echo $url_sync_event;?>/perkawinan"); 
			}
			if(pilih=="pkb"){
				alert("<?php echo $url_sync_event;?>/pkb");
            	ifrm.setAttribute("src", "<?php echo $url_sync_event;?>/pkb"); 
			}
			if(pilih=="kelahiran"){
				
            	ifrm.setAttribute("src", "<?php echo $url_sync_kelahiran;?>"); 
			}
			if(pilih=="ganti_pemilik"){
				
            	ifrm.setAttribute("src", "<?php echo $url_sync_gantipemilik;?>"); 
			}
			if(pilih=="ganti_eartag"){
				
            	ifrm.setAttribute("src", "<?php echo $url_sync_gantieartag;?>"); 
			}
			if(pilih=="pengobatan"){
				
            	ifrm.setAttribute("src", "<?php echo $url_sync_pengobatan;?>"); 
			}
			if(pilih=="mutasi"){
				
            	ifrm.setAttribute("src", "<?php echo $url_sync_mutasi;?>"); 
			}
			if(pilih=="update"){
				
            	ifrm.setAttribute("src", "<?php echo $url_update;?>"); 
			}
			if(pilih=="update_cow"){
				
            	ifrm.setAttribute("src", "<?php echo $url_update_cow;?>"); 
			}
			if(pilih=="logistik"){
				
            	ifrm.setAttribute("src", "<?php echo $url_sync_logistik;?>"); 
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
     
        <h1>Untuk melakukan Migrasi/Update Data silahkan pilih kemudian klik tombol <strong style="color:#F00">Migrasi Data..</strong> </h1>
        <div class='float_left'>
           
             
        </div>
        <div class='float_right'>
             
           
        </div>
    		 <select name="pilih" id="pilih_migrasi">
             <option value="">--pilih--</option>
              <option value="sapi">Sapi</option>
             <!-- <option value="event">Event/Pelayanan</option>
             <option value="perkawinan">Event Perkawinan</option>
             <option value="pkb">Event PKB</option>
             <option value="kelahiran">Event Kelahiran</option>
             <option value="ganti_pemilik">Event Ganti Pemilik</option>
             <option value="ganti_eartag">Event Ganti Eartag</option>
             <option value="pengobatan">Event Pengobatan</option>
             <option value="mutasi">Event Mutasi</option>-->
          
             </select>
             <a onclick="ajax_stream();" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-exchange"></i> Migrasi/Update Data..</a>
             
             <a onclick="check_sync();" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-check"></i> Check Migrasi </a>
        <div id="divProgress"></div>
        <div class="row-form">
            <div id='progress_wrapper'  style="float:left">
                    <div id="progressor" ></div>
            </div>
       		 <div id="persen_id" style="float:right;width:60%;margin-top: 9px;">&nbsp;</div>
       </div>
      
    </div>
