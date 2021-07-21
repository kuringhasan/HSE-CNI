<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Odoo_Api_Controller extends Login_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        set_time_limit(3200);
	    ini_set("memory_limit","512M"); 
        $path   = "plugins/ripcord/ripcord.php";
        $domain_api  = "111.223.254.14";
        $port_api    = "8069";
        $url    = "http://".$domain_api.":".$port_api;
        $this->database      = "db_KPBS_Testing_New";
        $this->username     = "arin@dynamic.co.id";
        $this->password     = "1234";
		if(file_exists($path)) {
		  
			require_once($path);
            $master=new Master_Ref_Model();
             if($master->is_connected($domain_api,$port_api)){
                $this->common = ripcord::client("$url/xmlrpc/2/common");
                $this->models = ripcord::client("$url/xmlrpc/2/object");
                $this->uid=$this->connect($this->database,$this->username,$this->password);
             }
             
		} else {
			Core::fatalError("Ripcord XMLRPC Plugin not found!");
		}
        
	}

     public function connect($database,$username,$password) {
        return $this->common->authenticate($database, $username, $password, array());
    }
    public function sync_field() {
        $api=new Odoo_Api_Model();
        
        $api->sync_field();
       
    }
    public function get_partner($id) {
        
        $uid=$this->connect($this->database,$this->username,$this->password);
        $ids = $this->models->execute_kw($this->database, $uid, $this->password,
                'res.partner', 'search',array(array(
                                                array('id', '=', $id)
                                                )
                                        ),array('limit'=>1));
        
       $records = $this->models->execute_kw($this->database, $uid, $this->password,
                'res.partner', 'read', array($ids));  
                
       $data=$records[0];
       $img_file = 'foto/1610021.jpg';
       $imgData = base64_encode(file_get_contents($img_file));
       // Format the image SRC:  data:{mime};base64,{data};
        $src = 'data: '.mime_content_type($img_file).';base64,'.$imgData;
        echo $imgData;
        // Echo out a sample image
        echo '<img src="'.$src.'">';
                $file_img=$data['image'];
                $file_img = str_replace('data:image/png;base64,', '', $file_img);
                $file_img = str_replace(' ', '+', $file_img);
               $file_img = base64_decode($file_img);
               $file = 'foto/foto_'. $data['id']. '.png';
                $success = file_put_contents($file, $file_img);
              
              echo $data['image']."<br />";
              echo $imgData = base64_encode(file_get_contents($file));
              
                //echo "<img src=\"".$data12."\" />";
                echo '<pre>';print_r($records[0]);echo '</pre>';exit;
        return $records[0];
       
    }
    public function sync_partner() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $uid=$this->connect($this->database,$this->username,$this->password);
        $ids = $this->models->execute_kw($this->database, $uid, $this->password,
                'res.partner', 'search',array(array(
                                                array('x_sync', '=', false),
                                                array('x_is_member', '=', false)
                                                )
                                        ),array('limit'=>10));
        
       $records = $this->models->execute_kw($this->database, $uid, $this->password,
                'res.partner', 'read', array($ids)); 
       if(!empty($records)){     
            $list_hasil=array();
           
            while($data=current($records)){
               // echo '<pre>';print_r($data);echo '</pre>';
                $TglSkrg		=date("Y-m-d H:i:s");
               	$tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                if($data['x_is_member']==true){
                    // jika anggota
                    $cek_anggota=$db->select("C_ANGGOTA","anggota")->where("C_ANGGOTA='".$data['x_member_id']."'")->get(0);
                    if(!empty($cek_anggota)){
                        //update data
                    }else{
                        //insert data
                    }
                }else{
                    // jika bukan anggota
                    $id_odoo=$data['id'];
                    $id_odoo_val	=$master->scurevaluetable($id_odoo);
                    $is_company_val	=$master->scurevaluetable($data['is_company']);
                    $company_id     = $data['company_id'][0];
                    $company_id_val =$master->scurevaluetable($company_id);
               	   
    	        	$nama_val		=$master->scurevaluetable($data['name']);
    	         	
    	        	$is_customer_val=$master->scurevaluetable($data['customer']);
    	        	$is_vendor_val	=$master->scurevaluetable($data['supplier']);
    	        
    	        	//$sex_val			=$master->scurevaluetable($sex);
    	        	
    	        	/*$kota_lahir			=$_POST['kota_lahir'];
    	        	$kota_lahir_val		=$master->scurevaluetable($kota_lahir);
    	        	$tahun_lahir		=$_POST['tahun_lahir'];
    	        	$bulan_lahir		=$_POST['bulan_lahir'];
    	        	$tgl_lahir			=$_POST['tanggal_lahir'];
    	        	$tanggal_lahir		=$tahun_lahir."-".$bulan_lahir."-".$tgl_lahir;
    	        	$tanggal_lahir_val		=$master->scurevaluetable($tanggal_lahir);*/
    	        
    	        	$email_val		=$master->scurevaluetable($data['email']);
    	        	$hp_val		=$master->scurevaluetable($data['mobile']);
    	        	
    	            $provinsi			=$_POST['provinsi'];
    				$provinsi_val		=$master->scurevaluetable($provinsi);
    				$kota			=$_POST['kota'];
    				$kota_val		=$master->scurevaluetable($kota);
    				$alamat			=$_POST['alamat'];
    				$alamat_val		=$master->scurevaluetable($alamat);
    	        	$rt				=$_POST['rt'];
    	        	$rt_val		=$master->scurevaluetable($rt);
    	        	$rw				=$_POST['rw'];
    	        	$rw_val		=$master->scurevaluetable($rw);
    	        	$desa			=$_POST['desa'];
    	        	$desa_val		=$master->scurevaluetable($desa);
    	        	$kec			=$_POST['kecamatan'];
    	        	$kec_val		=$master->scurevaluetable($kec);
    	        	$kodepos			=$_POST['kodepos'];
    	        	$kodepos_val		=$master->scurevaluetable($kodepos);
                    $cek_ada=$db->select("PartnerID","tbmpartners")->where("PartnerOdooID=$id_odoo")->get(0);
                    if(!empty($cek_ada)){
                        //update data
                        $cols_and_vals="PartnerIsCompany=$is_company_val,PartnerCompanyID=$company_id_val,PartnerNama=$nama_val,
                        PartnerIsCustomer=$is_customer_val,PartnerIsVendor=$is_vendor_val,
                        PartnerHandPhone=$hp_val,PartnerEmail=$email_val,PartnerOdooID=$id_odoo_val,PartnerLastUpdated=$tgl_skrg_val";
        				$sqlin ="UPDATE tbmpartners SET $cols_and_vals WHERE PartnerOdooID=$id_odoo;";
                    }else{
                        //insert data
                        
                        $created_val		=$master->scurevaluetable($data['create_date']);
                        $cols="PartnerIsCompany,PartnerCompanyID,PartnerNama,PartnerIsCustomer,PartnerIsVendor,
                        PartnerHandPhone,PartnerEmail,PartnerOdooID,PartnerLastUpdated,PartnerCreated";
        				$values="$is_company_val,$company_id_val,$nama_val,$is_customer_val,$is_vendor_val,
                        $hp_val,$email_val,$id_odoo_val,$tgl_skrg_val,$created_val";
        	        	
        		        
        		       /* $cols="PartnerIsCompany,PartnerCompanyID,PartnerNama,PartnerIsCustomer,PartnerIsVendor,
                        PartnerHandPhone,PartnerEmail,
                        PartnerAlamatAsalJalan,PartnerAlamatAsalRT,PartnerAlamatAsalRW,
                        PartnerAlamatAsalKelurahan,PartnerAlamatAsalKecamatan,PartnerAlamatAsalKabupatenKode,
                        PartnerAlamatAsalKodePos,
                        PartnerStepUpdate,PartnerLastUpdated,PartnerCreated";
        				$values="$is_company_val,$company_id_val,$nama_val,$is_customer_val,$is_vendor_val,
                        $hp_val,$email_val,
                        $alamat_val,$rt_val,$rw_val,$desa_val,$kec_val,$kota_val,$kodepos_val,
                        $next_step_val,$tgl_skrg_val,$tgl_skrg_val";*/
        				$sqlin ="INSERT INTO  tbmpartners ($cols) VALUES($values);";
                        
                    }
                    $rsl_cust=$db->query($sqlin);
                    if(isset($rsl_cust->error) and $rsl_cust->error===true){
                        
                    }else{
                        // flagging x_sync di odoo
                         $this->models->execute_kw($this->database, $uid, $this->password, 'res.partner', 'write',
                            array(array($id_odoo), array('x_sync'=>"1")));
                    }
                   
                }
               // echo '<pre>';print_r($data);echo '</pre>';
                next($records);
            }        exit; 
       }else{
            echo "Tidak ada data yang harus disinkronisasi";
       }
                
        return $records[0];
       
    }
    
    public function sync_tpk($category="tpk") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $errorlevel=error_reporting();
        //error_reporting($errorlevel & ~E_NOTICE);
        $master=new Master_Ref_Model();
        
        
        switch($category){
            case "tpk":
                $list_ada=$db->select("id,mcp_type,name,address,is_active","mcp")->where("ifnull(sync,false)=false")->get();
                while($data=current($list_ada)){
                   // echo "<pre>"; print_r($list_ada);echo "</pre>";
                    $uid=$this->connect($this->database,$this->username,$this->password);
                    $ids = $this->models->execute_kw($this->database, $uid, $this->password,
                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', $data->id),array('x_category', '=', "tpk")
                                                    )
                                            ),array('limit'=>1));
                    
                   // print_r($ids);
                    $cek_data = $this->models->execute_kw($this->database, $uid, $this->password,'stock.location', 'read', array($ids));  
                    //echo "<pre>"; print_r($cek_data);echo "</pre>";
                    if(!empty($cek_data)){
                        $id_odoo=$ids[0]; 
                        $col_and_values=array("name"=>$data->name,
                                        "x_address"=>$data->address,
                                        "active"=>$data->is_active);
                        $this->models->execute_kw($this->database, $uid, $this->password, 'stock.location', 'write',
                                        array(array($id_odoo), $col_and_values));
                    
                        $data_updated = $this->models->execute_kw($this->database, $uid, $this->password,'stock.location', 'read', array($ids));  
                        //echo "<pre>"; print_r($data_updated);echo "</pre>";exit;
                    }else{
                        // insert
                        $col_and_values=array("name"=>$data->name,
                                            "x_tpk_erp"=>$data->id,
                                            "x_address"=>$data->address,
                                            "x_category"=>$category,
                                            "active"=>$data->is_active,
                                            "location_id"=>11);
                                            
                        $id_odoo = $this->models->execute_kw($this->database, $uid, $this->password,'stock.location', 'create',array($col_and_values));
                        //$get_data=$this->get_purchase($id);
                    }
                    $sql_up="UPDATE mcp SET sync=1 WHERE id=".$data->id."";
                    $db->query($sql_up);
                    next($list_ada);
                }
            break;
            case "kelompok":
                $list_ada=$db->select("id,mcp_id,name","kelompok")->where("ifnull(sync,false)=false")->orderBy("id desc")->get();
                while($data=current($list_ada)){
                   // echo "<pre>"; print_r($list_ada);echo "</pre>";
                   $uid=$this->connect($this->database,$this->username,$this->password);
                  // echo "connect ".$data->id." :".$uid."<br />";
                    $ids = $this->models->execute_kw($this->database, $uid, $this->password,
                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', $data->id),array('x_category', '=', 'kelompok')
                                                    )
                                            ),array('limit'=>1));
                    
                   // print_r($ids);
                    $cek_data = $this->models->execute_kw($this->database, $uid, $this->password,'stock.location', 'read', array($ids));  
                    //echo "<pre>"; print_r($cek_data);echo "</pre>";
                    if(!empty($cek_data)){
                        $id_odoo=$ids[0]; 
                        $col_and_values=array("name"=>$data->name);
                        $this->models->execute_kw($this->database, $uid, $this->password, 'stock.location', 'write',
                                        array(array($id_odoo), $col_and_values));
                    
                        $data_updated = $this->models->execute_kw($this->database, $uid, $this->password,'stock.location', 'read', array($ids));  
                        //echo "<pre>"; print_r($data_updated);echo "</pre>";exit;
                    }else{
                        // insert
                        $ids_tpk = $this->models->execute_kw($this->database, $uid, $this->password,
                                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', $data->mcp_id),array('x_category', '=', "tpk")
                                                    )
                                            ),array('limit'=>1));
                        $id_tpk_odoo=$ids_tpk[0]; 
                        $col_and_values=array("name"=>$data->name,
                                            "x_tpk_erp"=>$data->id,
                                            "x_category"=>$category,
                                            "location_id"=>$id_tpk_odoo);
                                            
                        $id_odoo = $this->models->execute_kw($this->database, $uid, $this->password,'stock.location', 'create',array($col_and_values));
                        //$get_data=$this->get_purchase($id);
                    }
                    $sql_up="UPDATE kelompok SET sync=1 WHERE id=".$data->id."";
                    $db->query($sql_up);
                    next($list_ada);
                }
            
            break;
            case "kelompok_harga":
            
                $list_ada=$db->select("id,kelompok_id,name","kelompok_harga")->where("ifnull(sync,false)=false")->orderBy("id desc")->get();
                while($data=current($list_ada)){
                   // echo "<pre>"; print_r($list_ada);echo "</pre>";
                   $uid=$this->connect($this->database,$this->username,$this->password);
                    $ids = $this->models->execute_kw($this->database, $uid, $this->password,
                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', $data->id),array('x_category', '=', 'kelompok_harga')
                                                    )
                                            ),array('limit'=>1));
                    
                   // print_r($ids);
                    $cek_data = $this->models->execute_kw($this->database, $uid, $this->password,'stock.location', 'read', array($ids));  
                    //echo "<pre>"; print_r($cek_data);echo "</pre>";
                    if(!empty($cek_data)){
                        $id_odoo=$ids[0]; 
                        $col_and_values=array("name"=>$data->name);
                        $this->models->execute_kw($this->database, $uid, $this->password, 'stock.location', 'write',
                                        array(array($id_odoo), $col_and_values));
                    
                        $data_updated = $this->models->execute_kw($this->database, $uid, $this->password,'stock.location', 'read', array($ids));  
                        //echo "<pre>"; print_r($data_updated);echo "</pre>";exit;
                    }else{
                        // insert
                        $ids_kelompok = $this->models->execute_kw($this->database, $uid, $this->password,
                                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', $data->kelompok_id),array('x_category', '=', "kelompok")
                                                    )
                                            ),array('limit'=>1));
                        $id_klp_odoo=$ids_kelompok[0]; 
                        $col_and_values=array("name"=>$data->name,
                                            "x_tpk_erp"=>$data->id,
                                            "x_category"=>$category,
                                            "location_id"=>$id_klp_odoo);
                                            
                        $id_odoo = $this->models->execute_kw($this->database, $uid, $this->password,'stock.location', 'create',array($col_and_values));
                        //$get_data=$this->get_purchase($id);
                    }
                    $sql_up="UPDATE kelompok_harga SET sync=1 WHERE id=".$data->id."";
                    $db->query($sql_up);
                    next($list_ada);
                }
            
            break;
        }
    }
    
    public function sync_member2odoo() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $errorlevel=error_reporting();
        //error_reporting($errorlevel & ~E_NOTICE);
        $master=new Master_Ref_Model();
        
        $this->sync_tpk("tpk");// sinkronisasi TPK
        $this->sync_tpk("kelompok");// sinkronisasi Kelompok
        $this->sync_tpk("kelompok_harga");// sinkronisasi Kelompok Harga
        $cek_ada=$db->select("ID_ANGGOTA,C_ANGGOTA,NAMA,ID_KELOMPOK,ID_KELOMPOK_HARGA,DIAWASI,
        STATUS_AKTIF,PATH_FOTO,TGL_MASUK,ALAMAT1,ALAMAT2,NO_TELP,NO_HP,TGL_LAHIR,JENIS_KELAMIN,NIK,
        NoKK,tempat_lahir,agama","anggota ang
        inner join kelompok kel on kel.id=ang.ID_KELOMPOK")->where("ifnull(sync,false)=false and kel.mcp_id =1 ")->get();
        //1=tpk pangalengan
        $hasil_sync=array();
        if(!empty($cek_ada)){
            //update data
            $k=0;
            while($data=current($cek_ada)){
       	        $member_id      =$data->C_ANGGOTA;
                $ids_kelompok = $this->models->execute_kw($this->database, $this->uid, $this->password,
                                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', $data->ID_KELOMPOK),array('x_category', '=', "kelompok")
                                                    )
                                            ),array('limit'=>1));
                $id_klp_odoo = $ids_kelompok[0]; // ID Kelompok  di Odoo
                
                $ids_kelompok_harga = $this->models->execute_kw($this->database, $this->uid, $this->password,
                                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', $data->ID_KELOMPOK_HARGA),array('x_category', '=', "kelompok_harga")
                                                    )
                                            ),array('limit'=>1));
                $id_kh_odoo = $ids_kelompok_harga[0]; // ID Kelompok Harga di Odoo
                
                $img_file    = "http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
                $imgData     = "";
                if(file_get_contents($img_file)){
                    $imgData = base64_encode(file_get_contents($img_file));
                }
                
                $ids = $this->models->execute_kw($this->database, $this->uid, $this->password,
                    'res.partner', 'search',array(array(
                                                    array('x_member_id', '=', $member_id)
                                                    )
                                            ),array('limit'=>1));
                $id_odoo    =$ids[0]; // ID Member di Odoo
                $cek_data   = $this->models->execute_kw($this->database, $this->uid, $this->password,
                    'res.partner', 'read', array($ids));  
                // echo "<pre>"; print_r($cek_data);echo "</pre>";
                $set_values="";
                if(!empty($cek_data)){
                    // update
                    $col_and_values=array("is_company"=>false,
                                        "customer"=>true,
                                        "supplier"=>true,
                                        "x_kelompok"=>$id_klp_odoo,
                                        "x_kelompok_harga"=>$id_kh_odoo,
                                        "x_is_member"=>true,
                                        "property_product_pricelist"=>2,// 2: anggota , 1= Public
                                        "x_member_id"=>$data->C_ANGGOTA);
                    $col_and_values=trim($imgData)==""?$col_and_values:array_merge($col_and_values,array("image"=>$imgData));
                    $col_and_values=trim($data->TGL_MASUK)==""?$col_and_values:array_merge($col_and_values,array("x_registration_date"=>$data->TGL_MASUK));
                    $col_and_values=trim($data->TGL_LAHIR)==""?$col_and_values:array_merge($col_and_values,array("x_birthdate"=>$data->TGL_LAHIR));
                                      
                    $result=$this->models->execute_kw($this->database, $this->uid, $this->password, 'res.partner', 'write',
                                    array(array($id_odoo), $col_and_values));
                    $sync=false;
                    $msqg_error="Gagal sync update";
                    $odoo_id="";
                    if($result==true){
                        //$cek_data2 = $this->models->execute_kw($this->database, $this->uid, $this->password,
                        //    'res.partner', 'read', array($ids));  
                        $get_partner = $this->models->execute_kw($this->database, $this->uid, $this->password,
                                    'res.partner', 'search',array(array(array('x_member_id', '=', $data->C_ANGGOTA))),array('limit'=>1));
                        $odoo_id=$get_partner[0];
                        $set_values="odoo_id=$odoo_id,sync='1'";
                        $sync=true;
                        $msqg_error="Sync update date";
                    }
                    $hasil_sync[$k]['sync']=$sync;
                    $hasil_sync[$k]['message']=$msqg_error;
                    $hasil_sync[$k]['odoo_id']=$odoo_id;
                    $hasil_sync[$k]['nomor_anggota']=$data->C_ANGGOTA;
                    $hasil_sync[$k]['nama_anggota']=$data->NAMA;
                    
                    // echo "<pre>"; print_r($get_partner);echo "</pre>"; exit;               
                }else{
                    // insert
                    $col_and_values=array("name"=>$data->NAMA,
                                        "is_company"=>false,
                                        "customer"=>true,
                                        "supplier"=>true,
                                        "x_kelompok"=>$id_klp_odoo,
                                        "x_kelompok_harga"=>$id_kh_odoo,
                                        "x_is_member"=>true,
                                        "property_product_pricelist"=>2,
                                        "x_member_id"=>$data->C_ANGGOTA);
                    $col_and_values=trim($imgData)==""?$col_and_values:array_merge($col_and_values,array("image"=>$imgData));
                    $col_and_values=trim($data->TGL_MASUK)==""?$col_and_values:array_merge($col_and_values,array("x_registration_date"=>$data->TGL_MASUK));
                    $col_and_values=trim($data->TGL_LAHIR)==""?$col_and_values:array_merge($col_and_values,array("x_birthdate"=>$data->TGL_LAHIR));
                              
                                        
                    $result = $this->models->execute_kw($this->database, $this->uid, $this->password,
                    'res.partner', 'create',array($col_and_values));
                    $sync=false;
                    $msqg_error="Gagal sync insert data";
                    $odoo_id="";
                    if(!is_array($result) and trim($result)<>""){
                        $odoo_id=$result;
                        $sync=true;
                        $set_values="odoo_id=$odoo_id,sync='1'";
                        $msqg_error="Sync insert data";
                    }
                    $hasil_sync[$k]['sync']=$sync;
                    $hasil_sync[$k]['message']=$msqg_error;
                    $hasil_sync[$k]['odoo_id']=$odoo_id;
                    $hasil_sync[$k]['nomor_anggota']=$data->C_ANGGOTA;
                    $hasil_sync[$k]['nama_anggota']=$data->NAMA;
                    
                }
                if(trim($set_values)<>""){
                    $sql_up="UPDATE anggota SET $set_values WHERE id=".$data->ID_ANGGOTA."";
                    $db->query($sql_up);
                }
                $k++;
                next($cek_ada);
            
            }
        }
        json_encode($hasil_sync);exit;
        //return json_encode($hasil_sync);
       
    }
  public function sync_product($data) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $errorlevel=error_reporting();
        error_reporting($errorlevel & ~E_NOTICE);
        $hasil_sync=array();
        if(!empty($data)){
            $msg_error="";
           $sync_success=false;
           $create_product=false;
           $is_ada=false;
           $product_id=$data->odoo_id;
           
           if(trim($data->odoo_id)<>""){
                $cek_product= $this->models->execute_kw($this->database, $this->uid, $this->password,
                        'product.template', 'search',array(array(array('id', '=', $data->odoo_id))));
          
                if(!empty($cek_product)){
                    $is_ada=true;
                }
           }
           if($is_ada==false){//insert
          
                $col_and_values=array("name"=>$data->name,
                        "display_name"=>$data->name,
                        "sale_ok"=>true,
                        "purchase_ok"=>1,
                        "type"=>"product",//product type : consu,service, product
                        "x_erp_id"=>$data->id,
                        "categ_id"=>1,
                        "price"=>$data->harga,
                        "uom_id"=>$data->unit_id,
                        "uom_po_id"=>$data->unit_id);//1: all, 2:All / Saleable
                 
                $product = $this->models->execute_kw($this->database, $this->uid, $this->password,
                    'product.template', 'create',array($col_and_values));
                          
                if(!is_array($product) and trim($product)<>""){
                    $product_id=$product;
                    $create_product=true;
                    $msg_error="Berhasil sync create Product";
                }else{
                    $msg_error="Gagal sync create Product Template";
                }
               
            }else{//update
            
                $col_and_values=array("name"=>$data->name,
                        "type"=>"product",//product type : consu,service, product
                        "price"=>$data->harga,
                        "x_erp_id"=>(int)$data->id,
                        "categ_id"=>1,
                        "uom_id"=>(int)$data->unit_id,
                        "uom_po_id"=>(int)$data->unit_id);//,
                        //"categ_id"=>1);//1: all, 2:All / Saleable
                       //print_r($col_and_values);
                $result= $this->models->execute_kw($this->database, $this->uid, $this->password, 'product.template', 'write',
                                array(array((int)$product_id), $col_and_values));
                // echo "<pre>";print_r($result);echo "</pre>";
               if(!is_array($result) and $result==true){
                   $msg_error="Berhasil sync update Product";
                    $create_product=true;	
                }else{
                    $msg_error="Gagal sync update Product Template. Error : ".$result['faultString'];//$result;
                }
            }
            if($create_product){
                $cek_pricelist= $this->models->execute_kw($this->database, $this->uid, $this->password,
                        'product.pricelist.item', 'search',array(array(array('pricelist_id', '=',2),
                                                                        array('product_tmpl_id', '=',(int)$product_id))));
 
                if(empty($cek_pricelist)){
                     $col_and_values_item=array("name"=>$data->name,
                                                "fixed_price"=>$data->harga,//$data->harga,
                                                "pricelist_id"=>2,
                                               // "product_id"=>$product_id,
                                                "product_tmpl_id"=>$product_id);//1: Public, 2:anggota
                     $product_item = $this->models->execute_kw($this->database, $this->uid, $this->password,
                    'product.pricelist.item', 'create',array($col_and_values_item));
                   
                    if(!is_array($product_item) and trim($product_item)<>""){
                        $sync_success=true;
                    }else{
                        $msg_error="Gagal sync crreate Product List";//$result;
                    }
                    
                }else{
                    $product_item_id=$cek_pricelist;// array
                    $col_and_values_item=array("name"=>$data->name,
                                                "fixed_price"=>$data->harga,//$data->harga,//$data->harga,
                                                "pricelist_id"=>2,
                                               // "product_id"=>$product_id,
                                                "product_tmpl_id"=>(int)$product_id);//1: Public, 2:anggota
                    $result= $this->models->execute_kw($this->database, $this->uid, $this->password, 'product.pricelist.item', 'write',
                                array($product_item_id, $col_and_values_item));  
                     
                    if($result==true){
                        $sync_success=true;
                    }else{
                        $msg_error="Gagal sync update Product List";//$result;
                    }
                }
            }
            if($sync_success){
                $sql_up="UPDATE barang SET odoo_id=$product_id,sync=1 WHERE id=".$data->id."";
                $db->query($sql_up);
                $hasil_sync['sync']=true;
                $hasil_sync['id']=$data->id;
                $hasil_sync['kode']=$data->kode;
                $hasil_sync['anggota_name']=$data->name;
                $hasil_sync['message']=trim($msg_error)==""?"Berhasil":$msg_error;
            }else{
                $hasil_sync['sync']=false;
                $hasil_sync['id']=$data->id;
                $hasil_sync['kode']=$data->kode;
                $hasil_sync['anggota_name']=$data->name;
                $hasil_sync['message']=$msg_error;
            }
          
        }
        
        return $hasil_sync;
            
    }
    public function sync_products() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $errorlevel=error_reporting();
        error_reporting($errorlevel & ~E_NOTICE);
        $master=new Master_Ref_Model();
        $list_ada=$db->select("id,odoo_id,kode,name,satuan,harga,created_by,updated_by,updated_time,display_print,unit_id","barang")
        ->where("ifnull(sync,false)=false")->get();
        
  
        $hasil_sync=array();
        
        while($data=current($list_ada)){
            $msg_error="";
           $sync_success=false;
           $create_product=false;
           $is_ada=false;
           $product_id=$data->odoo_id;
           
           if(trim($data->odoo_id)<>""){
                $cek_product= $this->models->execute_kw($this->database, $this->uid, $this->password,
                        'product.template', 'search',array(array(array('id', '=', $data->odoo_id))));
          
                if(!empty($cek_product)){
                    $is_ada=true;
                }
           }
           if($is_ada==false){//insert
          
                $col_and_values=array("name"=>$data->name,
                        "display_name"=>$data->name,
                        "sale_ok"=>true,
                        "purchase_ok"=>1,
                        "type"=>"product",//product type : consu,service, product
                        "x_erp_id"=>$data->id,
                        "categ_id"=>1,
                        "price"=>$data->harga,
                        "uom_id"=>$data->unit_id,
                        "uom_po_id"=>$data->unit_id);//1: all, 2:All / Saleable
                 
                $product = $this->models->execute_kw($this->database, $this->uid, $this->password,
                    'product.template', 'create',array($col_and_values));
                          
                if(!is_array($product) and trim($product)<>""){
                    $product_id=$product;
                    $create_product=true;
                    $msg_error="Berhasil sync create Product";
                }else{
                    $msg_error="Gagal sync create Product Template";
                }
               
            }else{//update
            
                $col_and_values=array("name"=>$data->name,
                        "type"=>"product",//product type : consu,service, product
                        "price"=>$data->harga,
                        "x_erp_id"=>(int)$data->id,
                        "categ_id"=>1,
                        "uom_id"=>(int)$data->unit_id,
                        "uom_po_id"=>(int)$data->unit_id);//,
                        //"categ_id"=>1);//1: all, 2:All / Saleable
                       //print_r($col_and_values);
                $result= $this->models->execute_kw($this->database, $this->uid, $this->password, 'product.template', 'write',
                                array(array((int)$product_id), $col_and_values));
                // echo "<pre>";print_r($result);echo "</pre>";
               if(!is_array($result) and $result==true){
                   $msg_error="Berhasil sync update Product";
                    $create_product=true;	
                }else{
                    $msg_error="Gagal sync update Product Template. Error : ".$result['faultString'];//$result;
                }
            }
            if($create_product){
                $cek_pricelist= $this->models->execute_kw($this->database, $this->uid, $this->password,
                        'product.pricelist.item', 'search',array(array(array('pricelist_id', '=',2),
                                                                        array('product_tmpl_id', '=',(int)$product_id))));
 
                if(empty($cek_pricelist)){
                     $col_and_values_item=array("name"=>$data->name,
                                                "fixed_price"=>$data->harga,//$data->harga,
                                                "pricelist_id"=>2,
                                               // "product_id"=>$product_id,
                                                "product_tmpl_id"=>$product_id);//1: Public, 2:anggota
                     $product_item = $this->models->execute_kw($this->database, $this->uid, $this->password,
                    'product.pricelist.item', 'create',array($col_and_values_item));
                   
                    if(!is_array($product_item) and trim($product_item)<>""){
                        $sync_success=true;
                    }else{
                        $msg_error="Gagal sync crreate Product List";//$result;
                    }
                    
                }else{
                    $product_item_id=$cek_pricelist;// array
                    $col_and_values_item=array("name"=>$data->name,
                                                "fixed_price"=>$data->harga,//$data->harga,//$data->harga,
                                                "pricelist_id"=>2,
                                               // "product_id"=>$product_id,
                                                "product_tmpl_id"=>(int)$product_id);//1: Public, 2:anggota
                    $result= $this->models->execute_kw($this->database, $this->uid, $this->password, 'product.pricelist.item', 'write',
                                array($product_item_id, $col_and_values_item));  
                     
                    if($result==true){
                        $sync_success=true;
                    }else{
                        $msg_error="Gagal sync update Product List";//$result;
                    }
                }
            }
            if($sync_success){
                $sql_up="UPDATE barang SET odoo_id=$product_id,sync=1 WHERE id=".$data->id."";
                $db->query($sql_up);
                $hasil_sync[$i]['sync']=true;
                $hasil_sync[$i]['id']=$data->id;
                $hasil_sync[$i]['kode']=$data->kode;
                $hasil_sync[$i]['anggota_name']=$data->name;
                $hasil_sync[$i]['message']=trim($msg_error)==""?"Berhasil":$msg_error;
            }else{
                $hasil_sync[$i]['sync']=false;
                $hasil_sync[$i]['id']=$data->id;
                $hasil_sync[$i]['kode']=$data->kode;
                $hasil_sync[$i]['anggota_name']=$data->name;
                $hasil_sync[$i]['message']=$msg_error;
            }
          
            $cek=$this->get_products($product_id);
            $i++;
            next($list_ada);
        }
        
        echo "<pre>";print_r($hasil_sync);echo "</pre>";
            
    }
    public function get_products($odoo_id) {
         $uid=$this->connect($this->database,$this->username,$this->password);
        $ids = $this->models->execute_kw($this->database, $uid, $this->password,
               'product.template', 'search',array(array(
                                               array('id', '=', $odoo_id)
                                                )
                                        ),array('limit'=>1));
      
      
    
    $records =$this->models->execute_kw($this->database, $uid, $this->password,'product.template', 'read',array($ids));
    $rslt=  $records[0];
      
     
    $item_ids = $this->models->execute_kw($this->database, $uid, $this->password,
        'product.pricelist.item', 'search_read',array(array(array('product_tmpl_id', '=', (int)$odoo_id))),
        array('fields'=>array('name', 'fixed_price', 'pricelist_id'), 'limit'=>10));
      
   
        if(!empty($item_ids))  {
            $rslt['item']=$item_ids;
        }    
        return $rslt;
    }
   public function sync_sales() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $errorlevel=error_reporting();
        //error_reporting($errorlevel & ~E_NOTICE);
        $master=new Master_Ref_Model();
        $api    = new Odoo_Api_Model();
        
        $msg_error  ="";
        if(!$master->is_connected("google.com")){
            $msg_error=date("d/m/Y H:i:s")." : Belum terhubung ke internet";
        }else{
            if(!$api->connect()){
                $msg_error=date("d/m/Y H:i:s")." : Tidak dapat terhubung ke server Odoo, silahkan periksa kembali URL, username atau password API";
            }
        }
       
        $hasil_sync=array();
        
        if(trim($msg_error)==""){
            $list_data=$db->select("apl.id,tanggal,date_format(tanggal,'%d%m%Y') as tgl,id_barang, brg.odoo_id odoo_barang_id,brg.name ref_nama_barang,
            brg.unit_id as uom_id,nama_barang,jumlah,apl.harga real_harga,sub_total,end_date,date_format(end_date,'%d-%m-%Y') as end_date2,
            pendapatan_id,sale_id,line_id,ang.odoo_id as odoo_anggota_id,ang.C_ANGGOTA,ang.NAMA","anggota_pendapatan_logistik apl
            inner join anggota_pendapatan ap on ap.id=apl.pendapatan_id
            inner join periode pe on pe.id=ap.periode_id
            inner join anggota ang on ang.ID_ANGGOTA=ap.anggota_id
            inner join barang brg on brg.kode=apl.id_barang")
            ->where("ifnull(apl.sync,false)=false and ap.periode_id>=24 and ap.anggota_id=6994")
            ->orderBy("tanggal asc")->lim();
            $jumlah = $db->numRow($list_data);
        // echo "<pre>";print_r($rw);echo "</pre>";
          //$dt=$db->fetchObject($list_data);echo "<pre>";print_r($dt);echo "</pre>";
          //exit;
          
            $for_sync=array();
            $j=0;
            if($jumlah>0){
                while($data=$db->fetchObject($list_data)){
                    
                    $for_sync[$data->tgl]['sale_id']=$data->sale_id;
                    $for_sync[$data->tgl]['tanggal']=$data->tanggal;
                    $for_sync[$data->tgl]['odoo_anggota_id']=$data->odoo_anggota_id;
                    $for_sync[$data->tgl]['nomor_anggota']=$data->C_ANGGOTA;
                    $for_sync[$data->tgl]['nama_anggota']=$data->NAMA;
                  
                    $rec1 = new stdClass;
                    $rec1->id               =$data->id;
                    $rec1->line_id          =$data->line_id;
                    $rec1->id_barang        =$data->id_barang;
                    $rec1->odoo_barang_id   =$data->odoo_barang_id;
                    $rec1->nama_barang      =$data->ref_nama_barang;
                    $rec1->description      =$data->nama_barang;
                    $rec1->qty              =$data->jumlah;
                    $rec1->uom_id              =$data->uom_id;
                    $rec1->harga            =$data->real_harga;
                    
                  //  $rec->item[$data->id]=$rec1;
                    //$item=$rec1;
                    //$rec->item[$data->tgl]=$rec1;
                    //$for_sync[$data->tgl]=$rec;
                    $for_sync[$data->tgl]['item'][$j]=$rec1;
                   
                    $j++;
                }
                 //echo '<pre>';print_r($for_sync);echo '</pre>';exit;
            }
            echo '<pre>';print_r($for_sync);echo '</pre>';exit;
            if(!empty($for_sync)){
                //update data
               
                while($rec=current($for_sync)){
           	        
                    $sync_data= $api->sync_sale((object)$rec);  
                    next($for_sync);
                    $hasil_sync['sync_data']=$sync_data;
                }
            }else{
                
            
            }
        }else{
            $hasil_sync['connected']=false;
            $hasil_sync['message']=$msg_error;
            $hasil_sync['sync_data']=array();;
        }
        
                
        return $hasil_sync;
       
    }
    public function get_purchase($id) {
         $uid=$this->connect($this->database,$this->username,$this->password);
        $ids = $this->models->execute_kw($this->database, $uid, $this->password,
                'purchase.order', 'search',array(array(
                                                array('id', '=', $id)
                                                )
                                        ),array('limit'=>1));
        
       $records = $this->models->execute_kw($this->database, $uid, $this->password,
                'purchase.order', 'read', array($ids));  
                echo '<pre>';print_r($records[0]);echo '</pre>';exit;
        return $records[0];
    }
    
     public function update_purchase($id) {
        /**  field status : state
         * draft    = Request for quotation RPQ
         * purchase = Purchase Order (PO))
         * ************************************** */
         $uid=$this->connect($this->database,$this->username,$this->password);
        $get_id=$this->models->execute_kw($this->database, $uid, $this->password,'purchase.order', 'search',
                array(array(array('id', '=', $id))));
        $id_contact=$get_id[0];   
        
       $this->models->execute_kw($this->database, $uid, $this->password, 'purchase.order', 'write',
            array(array($id_contact), array('state'=>"purchase")));
            
            $get_data=$this->get_purchase($id);
                echo '<pre>';print_r($get_data);echo '</pre>';exit;
        return $records[0];
    }
    public function insert_purchase() {
        /**  field status : state
         * draft    = Request for quotation RPQ
         * purchase = Purchase Order (PO))
         * ************************************** */
         $cek_order_line= $this->models->execute_kw($this->database, $this->uid, $this->password,
                'purchase.order.line', 'search',array(array(array('order_id', '=', 41))));
         
         $cek_order_line2= $this->models->execute_kw($this->database, $this->uid, $this->password,
    'purchase.order.line', 'search_read', array(array(array('order_id', '=', 41))),
                    array('fields'=>array('name', 'order_id', 'product_qty'), 'limit'=>5));
                
                 print_r($cek_order_line2);exit;
        $cek_data= $this->models->execute_kw($this->database, $this->uid, $this->password,
            'purchase.order', 'search',array(array(array('id', '=', 40))));
         
        
         $col_and_values_line=array(
                                    "name"=>"Susu",
                                    "order_id"=>40,
                                    "date_planned"=>date("Y-m-d H:i:s"),
                                    "product_id"=>2,
                                    "product_qty"=>100,
                                    "price_unit"=>6000,
                                    "product_uom"=>3);
                 $order_line_id = $this->models->execute_kw($this->database, $this->uid, $this->password,
                'purchase.order.line', 'create',array($col_and_values_line));
                
         //$this->models->execute_kw($this->database, $uid, $this->password,
   // 'purchase.order', 'unlink',array(array(40)));exit;
        /*$col_and_values=array("partner_id"=>7446,// a adin
                        "date_order"=>"2018-09-02 09:43:18",
                        "state"=>"draft",
                        "pricelist_id"=>2,
                        "order_line"=>array(array(0,false,array("date_planned"=>date("Y-m-d H:i:s"),
                                                            "product_id"=>36,
                                                            "product_qty"=>20,
                                                            "price_unit"=>20000))));*/
   /*   $col_and_values=array("partner_id"=>7446,// a adin
                        "date_order"=>"2018-09-08 09:43:18",
                        "state"=>"draft",
                        "pricelist_id"=>2);                   
    $purchase_id = $this->models->execute_kw($this->database, $uid, $this->password,
    'purchase.order', 'create',
    array($col_and_values));
     echo "order_id:".$purchase_id;
    print_r($purchase_id);*/
    $purchase_id=36;
   /* $col_and_values_line=array(
                        "name"=>"Ember",
                        "partner_id"=>5418,
                        "order_id"=>$purchase_id,
                        "date_planned"=>date("Y-m-d H:i:s"),
                        "product_id"=>36,
                        "product_qty"=>5,
                        "price_unit"=>20000,
                        "product_uom"=>1);
     $idl = $this->models->execute_kw($this->database, $uid, $this->password,
    'purchase.order.line', 'create',array($col_and_values_line));
    print_r($idl);*/
           
         $this->models->execute_kw($this->database, $uid, $this->password, 'purchase.order', 'write',
            array(array($purchase_id), array('state2'=>"purchase")));
        
        
        
           /* $get_data=$this->get_purchase($id);
            
                echo '<pre>';print_r($get_data);echo '</pre>';
                
                $ids_line = $this->models->execute_kw($this->database, $uid, $this->password,
                'purchase.order.line', 'search',array(array(
                                                array('order_id', '=', 21)
                                                )
                                        ),array('limit'=>1));
        
       $records = $this->models->execute_kw($this->database, $uid, $this->password,
                'purchase.order.line', 'read', array($ids_line));  
                echo '<pre>';print_r($records[0]);echo '</pre>';
                */
                
                exit;
        //return $records[0];
    }

 public function sync_pendapatan() {
    global $dcistem;
    $db   = $dcistem->getOption("framework/db"); 
    /**  field status : state
     * draft    = Request for quotation RPQ
     * purchase = Purchase Order (PO))
     * Model Purchases          : purchase.order
     * Model Journal Entries    : account.move
     * Model Sales              : sale.order
     * ************************************** */
      $list_pendapatan=$db->select("ap.id,anggota_id,odoo_id,C_ANGGOTA,NAMA,periode_id,date_format(start_date,'%d-%m-%Y') as start_date,end_date,
      date_format(end_date,'%d-%m-%Y') as end_date2,produksi,harga_per_kg,purchase_id,
      potongan_bpr,potongan_dkt,potongan_rp15,potongan_rp10,potongan_shr","anggota_pendapatan ap
      inner join periode pe on pe.id=ap.periode_id
      inner join anggota ang on ang.ID_ANGGOTA=ap.anggota_id")
      ->where("ifnull(ap.sync,false)=false and ifnull(pe.closed,0)=1 and ap.periode_id>=24 and anggota_id=6994")->get(0,5);
      //echo "<pre>";print_r($list_pendapatan);echo "</pre>"; 
      $list_pendapatan_notsync=array();
      if(!empty($list_pendapatan)){
        $j=0;
        while($hasil=current($list_pendapatan)){
            $periode="Periode ".$hasil->start_date." - ".$hasil->end_date2;
            $pendapatan_kotor =array(//purchase
                                        "product_odoo_id"=>46,// 46=Susu Segar
                                        "product_desc"=>$periode,// Susu Segar
                                        "qty"=>$hasil->produksi,// total qty susu satu periode
                                        "price_unit"=>$hasil->harga_per_kg,// harga satuan
                                        "product_uom"=>3);//3=kg;urchase
           
            $potongan['simpanan_pokok']   =array("name"=>"Simpanan Pokok",
                                        "debit"=>null,
                                        "credit"=>5000,
                                        "account"=>"40.01.02.01");  //account yang terkait simpanan pokok    
            $potongan['simpanan_wajib']   =array("name"=>"Simpanan Pokok",
                                        "debit"=>null,
                                        "credit"=>5000,
                                        "account"=>"40.01.02.01"); //account yang terkait simpanan wajib 
            $potongan['shr']              =array("name"=>"Simpanan Pokok",
                                        "debit"=>null,
                                        "credit"=>$hasil->potongan_shr,
                                        "account"=>"40.01.02.01");  //account yang terkait simpanan hari raya
            $potongan['simpanan_mt']      =array("name"=>"Simpanan Pokok",
                                        "debit"=>null,
                                        "credit"=>5000,
                                        "account"=>"40.01.02.01");  
            $potongan['simpanan_upp']     =array("debit"=>null,
                                        "credit"=>5000,
                                        "account"=>"40.01.02.01");  
            $potongan['simpanan_15']     =array("name"=>"Simpanan Pokok",
                                        "debit"=>null,
                                        "credit"=>$hasil->potongan_rp15,
                                        "account"=>"40.01.02.01");  
            $potongan['tabungan_10']     =array("debit"=>null,
                                        "credit"=>$hasil->potongan_rp10,
                                        "account"=>"40.01.02.01");  
            $potongan['tabungan_bpr']     =array("name"=>"Simpanan Pokok",
                                        "debit"=>null,
                                        "credit"=>$hasil->potongan_bpr,
                                        "account"=>"40.01.02.01");  
                                        
            $potongan['dkt']              =array("name"=>"Simpanan Pokok",
                                        "debit"=>null,
                                        "credit"=>$hasil->potongan_dkt,
                                        "account"=>"40.01.02.01"); 
            $potongan['iuran_desa']       =array("name"=>"Simpanan Pokok",
                                        "debit"=>null,
                                        "credit"=>5000,
                                        "account"=>"40.01.02.01"); 
                                        
            $potongan['tunggak_lalu']     =array("name"=>"Simpanan Pokok",
                                        "debit"=>null,
                                        "credit"=>5000,
                                        "account"=>"40.01.02.01"); 
            $potongan['potongan_um']     =array("debit"=>null,
                                        "credit"=>5000,
                                        "account"=>"40.01.02.01"); //account yang terkait potongan um
            $potongan['potongan_rumput'] =array("debit"=>null,
                                        "credit"=>5000,
                                        "account"=>"40.01.02.01"); //account yang terkait potongan rumput
            $list_sale=$db->select("id,tanggal,id_barang,nama_barang,jumlah,harga,sub_total,pendapatan_id,
            sale_id,sync","anggota_pendapatan_logistik apl")
            ->where("ifnull(apl.sync,false)=false and apl.pendapatan_id=".$hasil->id."")->get();
            $sales=array();
            $s=0;
            while($jual=current($list_sale)){
                $sales[$s]['id']=$jual->id;
                $sales[$s]['sale_id']=$jual->sale_id;
                $s++;
                next($list_sale);
            }
                           
            $list_pendapatan_notsync[$j]   =array("id"=>$hasil->id,
                                    "purchase_id"=>$hasil->purchase_id,
                                    "anggota_id"=>$hasil->anggota_id,
                                    "nomor_id"=>$hasil->C_ANGGOTA,
                                    "name"=>$hasil->NAMA,
                                    "id_odoo"=>$hasil->odoo_id,//5418,// id partner/member di odoo
                                    "periode_id"=>$hasil->periode_id,
                                    "periode_name"=>$periode,
                                    "closing_date"=>$hasil->end_date,
                                    "pendapatan_kotor"=>$pendapatan_kotor,
                                    "potongan"=>$potongan,
                                    "sales"=>$sales);
            $j++;
            next($list_pendapatan);
        }
     }                           
        
echo "<pre>";print_r($list_pendapatan_notsync);echo "</pre>";   exit;
     /** sync sebagai purchase */ 
     //$this->uid
     $hasil_sync=array();
     if(!empty($list_pendapatan_notsync)){
        $i=0;
       
        while($data=current($list_pendapatan_notsync)){
            //cek dulu, sudah sync belum
            $cek_ada_purchase=false;
            if(trim($data['purchase_id'])<>""){
                $cek_data= $this->models->execute_kw($this->database, $this->uid, $this->password,
                'purchase.order', 'search',array(array(array('id', '=', $data['purchase_id']))));
                if(!empty($cek_data)){
                    $cek_ada_purchase=true;
                }
                
            }
                
                
            $purchase_id=$data['purchase_id'];
          // echo  $cek_ada_purchase;exit;
            if($cek_ada_purchase==false or trim($purchase_id)==""){
                $col_and_values=array("partner_id"=>$data['id_odoo'],// a adin
                    "date_order"=>$data['closing_date'],
                    "state"=>"purchase",
                    "pricelist_id"=>2);   // 2 : anggota  
                $create_purchase = $this->models->execute_kw($this->database, $this->uid, $this->password,
                'purchase.order', 'create',array($col_and_values));
                if(!is_array($create_purchase) and trim($create_purchase)<>""){
                    $purchase_id=$create_purchase;
                }
            }
            $create_po=false;
            $msg_err="";
            if(!is_array($purchase_id) and trim($purchase_id)<>""){
                $cek_order_line= $this->models->execute_kw($this->database, $this->uid, $this->password,
                'purchase.order.line', 'search',array(array(array('order_id', '=', $purchase_id))));// cek apakah sudah memiliki order_line
                
               // echo "puchase_id:".$purchase_id;echo "<pre>";print_r($cek_order_line);echo "</pre>";exit;
                $set_values="";
                if(empty($cek_order_line)){
                    
                    $col_and_values_line=array(
                                        "name"=>"test",//$data['data']['pendapatan_kotor']['product_desc'],
                                        "order_id"=>$purchase_id,
                                        "date_planned"=>$data['closing_date'],
                                        "product_id"=>$data['pendapatan_kotor']['product_odoo_id'],
                                        "product_qty"=>$data['pendapatan_kotor']['qty'],
                                        "price_unit"=>$data['pendapatan_kotor']['price_unit'],
                                        "product_uom"=>$data['pendapatan_kotor']['product_uom']);
                                        
                  
                     $order_line_id = $this->models->execute_kw($this->database, $this->uid, $this->password,
                    'purchase.order.line', 'create',array($col_and_values_line));
                    echo "<pre>";print_r($order_line_id);echo "</pre>";//exit;
                    if(!isset($order_line_id['faultCode'])){
                        $create_po=true;
                        // update field sync pada tabel pendapatan
                        $set_values="purchase_id=$purchase_id,sync='1'";
                        /** sync sale payment */ 
                        
                    }else{
                        $set_values="purchase_id=$purchase_id";
                        $msg_err="Gagal generate Purchase Line";
                    }
                    
                }else{
                    $create_po=true;
                    $set_values="purchase_id=$purchase_id,sync='1'";
                }
                $sql_up="UPDATE anggota_pendapatan SET $set_values WHERE id=".$data['id']."";
                $db->query($sql_up);
            }else{
                $msg_err="Gagal generate Purchase";
            }
            if($create_po==true){
                $hasil_sync[$i]['sync']=true;
                $hasil_sync[$i]['pendapatan_id']=$data['id'];
                $hasil_sync[$i]['anggota_id']=$data['anggota_id'];
                $hasil_sync[$i]['anggota_name']=$data['name'];
                $hasil_sync[$i]['message']="Berhasil";
                
                /** 1. Received */
                /* $ce= $this->models->execute_kw($this->database, $this->uid, $this->password, 'purchase.order', 'write',
                    array(array($purchase_id), array('state'=>"received")));
                    print_r($ce);*/
                /** 2. A/P Invoice */
                
                /** 3. Jurnal Entri  */
                
               
                $col_and_values_je=array("name"=>"Potongan-potongan",//$data['data']['pendapatan_kotor']['product_desc'],
                                        "date"=>$data['closing_date'],
                                        "journal_id"=>3);//journal 3 : Miscellaneous Operations
                $je_id = $this->models->execute_kw($this->database, $this->uid, $this->password,
                    'account.move', 'create',array($col_and_values_je));
                
            }else{
                $hasil_sync[$i]['sync']=false;
                $hasil_sync[$i]['pendapatan_id']=$data['id'];
                $hasil_sync[$i]['anggota_id']=$data['anggota_id'];
                $hasil_sync[$i]['anggota_name']=$data['name'];
                $hasil_sync[$i]['message']=$msg_err;
            }
            $i++;
            next($list_pendapatan_notsync);
        }
     }
                   
    
    print_r($hasil_sync);
    echo "end ";exit;
                                
         
         
         
        $uid=$this->connect($this->database,$this->username,$this->password);
        $get_id=$this->models->execute_kw($this->database, $uid, $this->password,'purchase.order', 'search',
                array(array(array('id', '=', $id))));
        $id_contact=$get_id[0];   
        
       $this->models->execute_kw($this->database, $uid, $this->password, 'purchase.order', 'write',
            array(array($id_contact), array('state'=>"purchase")));
            
            $get_data=$this->get_purchase($id);
                echo '<pre>';print_r($get_data);echo '</pre>';exit;
        return $records[0];
    }

public function send_sale() {
        /**  field status : state
         * draft    = Request for quotation RPQ
         * purchase = Purchase Order (PO))
         * Model Sales              : sale.order
         * ************************************** */
         $uid=$this->connect($this->database,$this->username,$this->password);
        $get_id=$this->models->execute_kw($this->database, $uid, $this->password,'purchase.order', 'search',
                array(array(array('id', '=', $id))));
        $id_contact=$get_id[0];   
        
        $col_and_vals=array("state"=>"draft");
        $id = $this->models->execute_kw($this->database, $uid, $this->password,
            'purchase.order', 'create',
            array(array('name'=>"New Partner")));
        
        
       $this->models->execute_kw($this->database, $uid, $this->password, 'sale.order', 'write',
            array(array($id_contact), array('state'=>"purchase")));
            
            $get_data=$this->get_purchase($id);
                echo '<pre>';print_r($get_data);echo '</pre>';exit;
        return $records[0];
    }
   
 public function upload_foto() {
    global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $msg    = array();
   //echo '<pre>';print_r($_FILES['myimage']);echo '</pre>';
   //$hasil=$_POST;//json_decode(file_get_contents("php://input"));
    ///echo 'se<pre>';print_r($hasil);echo '</pre>ed';exit;
    if(trim($_POST['username'])<>"" and trim($_POST['password'])<>"" and  trim($_POST['client_secret'])<>""){
       
        $auth     = new Auth();
        $hasil_login=$auth->loginAPI($_POST['username'], trim($_POST['password']), "auth_api",trim($_POST['client_secret']));
        if($hasil_login==true) {
            $direktori = 'foto/';
			$target = $direktori.$_FILES['myimage']['name'];
           if(move_uploaded_file($_FILES['myimage']['tmp_name'], $target)){
               
                $sqlr="update tbmmahasiswa mhs
                    inner join  tbtmahasiswareg reg on reg.mhsRegNomorIdentitas=mhs.mhsNomorIdentitas
                    set mhs.mhsFileFoto='".$_FILES['myimage']['name']."' WHERE reg.mhsRegNPM='".$_POST['NPM']."'";
			    $rslr=$db->query($sqlr);
                if(isset($rslr->error) and $rslr->error===true){
    				$msg['success']=false;
    	            $msg['message']="Sukses upload. Gagal update. ".$rslr->query_last_message. $sqlr;
    			}else{
    			     $msg['success']=true;
                    $msg['message']="Sukses";
   			    }
                
            }else{
                $msg['success']=false;
                $msg['message']="Error, gagal upload di server target";
            }
        }else{
            $msg['success']=false;
            $msg['message']="Gagal login";
        }
    }else{
        $msg['success']=false;
        $msg['message']="User, password atau Kode API tidak boleh kosong";
    }
  
    echo json_encode($msg);
    exit;
 }
 

}
 

?>