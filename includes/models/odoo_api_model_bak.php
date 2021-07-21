<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Odoo_Api_Model extends Model {
  
	public function __construct() {
	   $master         =new Master_Ref_Model();
	   $this->settings =$master->settings();
		set_time_limit(3200);
	    ini_set("memory_limit","512M"); 
        //$errorlevel=error_reporting();
        //error_reporting($errorlevel & ~E_NOTICE);
        $path   = "plugins/ripcord/ripcord.php";
        $domain_api  = $this->settings['odoo_ip'];//"111.223.254.14";
        $port_api    = $this->settings['odoo_port'];//"8069";
        $url    = "http://".$domain_api.":".$port_api;
        $this->database     = $this->settings['odoo_database'];//"db_kpbs_new";
        $this->username     = $this->settings['odoo_user'];//"hana@dynamic.co.id";
        $this->password     = $this->settings['odoo_password'];//"1234";
      /*  $this->database     = "demo_saas-113_1538780471";//$this->settings['odoo_database'];//"db_kpbs_new";
        $this->username     = "admin";//$this->settings['odoo_user'];//"hana@dynamic.co.id";
        $this->password     = "admin";//$this->settings['odoo_password'];//"1234";*/
		if(file_exists($path)) {
		  
			require_once($path);
          //   $this->common = ripcord::client("https://demo3.odoo.com/xmlrpc/2/common");
             
         // $this->common->version();
        //  echo "connected:".$this->connect();exit;
            $this->common = ripcord::client("$url/xmlrpc/2/common");
          // $this->common->version();exit;
            $this->models = ripcord::client("$url/xmlrpc/2/object");
            
           
           
           
             
		} else {
			Core::fatalError("Ripcord XMLRPC Plugin not found!");
		}
	}
    public function connect() {
        return $this->common->authenticate($this->database, $this->username, $this->password, array());
    }
    public function sync_field(){
        /** ========== tambah fiel x_erp_id ==========*/
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 146,// id model sebuah tabel, 146 ->product.template
                'name' => 'x_erp_id',
                'field_description' => 'ID ERP',
                'ttype' => 'integer',
                'state' => 'manual',
                'help' => 'ID products pada aplikasi ERP'// penjelasan of fields
               ))
        );
        exit;
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_birthdate',
                'field_description' => 'Birth Date',
                'ttype' => 'date',
                'state' => 'manual'//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_is_member',
                'field_description' => 'Is Member',
                'ttype' => 'boolean',
                'state' => 'manual'//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_kelompok',
                'field_description' => 'Kelompok',
                'ttype' => 'many2one',
                'state' => 'manual',
                'relation'=>'stock.location',
                'domain'=>"[('x_category','=','kelompok')]"//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_kelompok_harga',
                'field_description' => 'Kelompok Harga',
                'ttype' => 'many2one',
                'state' => 'manual',
                'relation'=>'stock.location',
                'domain'=>"[('x_category','=','kelompok_harga')]"//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_member_id',
                'field_description' => 'Member ID',
                'ttype' => 'char',
                'size' => '10',
                'state' => 'manual'//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_nik',
                'field_description' => 'No KTP',
                'ttype' => 'char',
                'size' => '20',
                'state' => 'manual'//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_no_kk',
                'field_description' => 'No Kartu Keluarga',
                'ttype' => 'char',
                'size' => '20',
                'state' => 'manual'//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_place_of_birth',
                'field_description' => 'Tempat Lahir',
                'ttype' => 'char',
                'size' => '70',
                'state' => 'manual'//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_registration_date',
                'field_description' => 'Tanggal Masuk KPBS',
                'ttype' => 'date',
                'state' => 'manual'//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 77,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_status',
                'field_description' => 'Status Anggota',
                'ttype' => 'selection',
                'selection' => "[(0,'Calon Anggota'),(1,'Aktif'),(2,'Non Aktif'),(3,'Beku'),(4,'Keluar')]",
                'state' => 'manual'//,
               ))
        );
        
        /** ========== TPK, Kelompok, Kelompok Harga model : 275-> stock.location ==========*/
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 275,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_tpk_erp',
                'field_description' => 'ID ERP',
                'ttype' => 'integer',
                'state' => 'manual'//,
               ))
        );
         $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 275,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_category',
                'field_description' => 'Kategory',
                'ttype' => 'selection',
                'selection' => "[('tpk','TPK/MCP'),('kelompok','Kelompok'),('kelompok_harga','Kelompok Harga')]",
                'state' => 'manual'//,
               ))
        );
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 275,// id model sebuah tabel, 77 ->res.partner
                'name' => 'x_address',
                'field_description' => 'Alamat',
                'ttype' => 'char',
                'size' => '150',
                'state' => 'manual'//,
               ))
        );
        /** ========== tambah fiel x_erp_id ==========*/
        $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'ir.model.fields', 'create', array(array(
                'model_id' => 146,// id model sebuah tabel, 146 ->product.template
                'name' => 'x_erp_id',
                'field_description' => 'ID ERP',
                'ttype' => 'integer',
                'state' => 'manual'//,
               ))
        );
    }
    public function sync_product_form_odoo() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $hasil_sync=array();
        $msg_error="";
        if(!$this->connect()){
            $msg_error="Tidak dapat terhubung ke server, silahkan cek kembali URL, username atau password API";
        }
        $hasil_sync=array();
        $kategori=$category;
        if(trim($msg_error)==""){
        
            $msg_error="";
           $sync_success=false;
           $create_product=false;
           $is_ada=false;
          // $product_id=$data->odoo_id;
           /*$cek_product= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'product.template', 'search_read',array(),array('fields'=>array('id','name', 'price'), 'limit'=>5));*/
            $list_data= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'product.product', 'search_read',array(),array('fields'=>array('id','name', 'list_price', 'x_erp_id')));
          // echo "<pre>"; print_r($list_data); echo "</pre>";exit;            
           if(trim($data->odoo_id)<>""){
                $cek_product= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'product.template', 'search_read',array(array(array('id', '=', $data->odoo_id))));
          
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
                        "x_erp_id"=>(int)$data->id,
                        "categ_id"=>1,
                        "price"=>$data->harga,
                        "uom_id"=>(int)$data->unit_id,
                        "uom_po_id"=>(int)$data->unit_id);//1: all, 2:All / Saleable
                 
                $product = $this->models->execute_kw($this->database, $this->connect(), $this->password,
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
                $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'product.template', 'write',
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
                $cek_pricelist= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'product.pricelist.item', 'search',array(array(array('pricelist_id', '=',2),
                                                                        array('product_tmpl_id', '=',(int)$product_id))));
 
                if(empty($cek_pricelist)){
                     $col_and_values_item=array("name"=>$data->name,
                                                "fixed_price"=>$data->harga,//$data->harga,
                                                "pricelist_id"=>2,
                                               // "product_id"=>$product_id,
                                                "product_tmpl_id"=>(int)$product_id);//1: Public, 2:anggota
                     $product_item = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'product.pricelist.item', 'create',array($col_and_values_item));
                   // print_r($product_item);exit;
                    if(!is_array($product_item) and trim($product_item)<>""){
                        $sync_success=true;
                    }else{
                        $msg_error="Gagal sync create Product List";//$result;
                    }
                    
                }else{
                    $product_item_id=$cek_pricelist;// array
                    $col_and_values_item=array("name"=>$data->name,
                                                "fixed_price"=>$data->harga,//$data->harga,//$data->harga,
                                                "pricelist_id"=>2,
                                               // "product_id"=>$product_id,
                                                "product_tmpl_id"=>(int)$product_id);//1: Public, 2:anggota
                    $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'product.pricelist.item', 'write',
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
                $hasil_sync['name']=$data->name;
                $hasil_sync['message']=trim($msg_error)==""?"Berhasil":$msg_error;
            }else{
                $hasil_sync['sync']=false;
                $hasil_sync['id']=$data->id;
                $hasil_sync['kode']=$data->kode;
                $hasil_sync['name']=$data->name;
                $hasil_sync['message']=$msg_error;
            }
             
        }else{
            $hasil_sync['sync']=false;
            $hasil_sync['message']=$msg_error;
        }
        return $hasil_sync;
            
    }
    public function sync_product($data) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $hasil_sync=array();
        $msg_error="";
        if(!$this->connect()){
            $msg_error="Tidak dapat terhubung ke server, silahkan cek kembali URL, username atau password API";
        }else{
            if(empty($data)){
                $msg_error="Data kosong";
            }
        }
        $hasil_sync=array();
        $kategori=$category;
        if(trim($msg_error)==""){
        
            $msg_error="";
           $sync_success=false;
           $create_product=false;
           $is_ada=false;
           $product_id=$data->odoo_id;
           
           if(trim($data->odoo_id)<>""){
                $cek_product= $this->models->execute_kw($this->database, $this->connect(), $this->password,
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
                        "x_erp_id"=>(int)$data->id,
                        "categ_id"=>1,
                        "price"=>$data->harga,
                        "uom_id"=>(int)$data->unit_id,
                        "uom_po_id"=>(int)$data->unit_id);//1: all, 2:All / Saleable
                 
                $product = $this->models->execute_kw($this->database, $this->connect(), $this->password,
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
                $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'product.template', 'write',
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
                $cek_pricelist= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'product.pricelist.item', 'search',array(array(array('pricelist_id', '=',2),
                                                                        array('product_tmpl_id', '=',(int)$product_id))));
 
                if(empty($cek_pricelist)){
                     $col_and_values_item=array("name"=>$data->name,
                                                "fixed_price"=>$data->harga,//$data->harga,
                                                "pricelist_id"=>2,
                                               // "product_id"=>$product_id,
                                                "product_tmpl_id"=>(int)$product_id);//1: Public, 2:anggota
                     $product_item = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'product.pricelist.item', 'create',array($col_and_values_item));
                   // print_r($product_item);exit;
                    if(!is_array($product_item) and trim($product_item)<>""){
                        $sync_success=true;
                    }else{
                        $msg_error="Gagal sync create Product List";//$result;
                    }
                    
                }else{
                    $product_item_id=$cek_pricelist;// array
                    $col_and_values_item=array("name"=>$data->name,
                                                "fixed_price"=>$data->harga,//$data->harga,//$data->harga,
                                                "pricelist_id"=>2,
                                               // "product_id"=>$product_id,
                                                "product_tmpl_id"=>(int)$product_id);//1: Public, 2:anggota
                    $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'product.pricelist.item', 'write',
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
                $hasil_sync['name']=$data->name;
                $hasil_sync['message']=trim($msg_error)==""?"Berhasil":$msg_error;
            }else{
                $hasil_sync['sync']=false;
                $hasil_sync['id']=$data->id;
                $hasil_sync['kode']=$data->kode;
                $hasil_sync['name']=$data->name;
                $hasil_sync['message']=$msg_error;
            }
             
        }else{
            $hasil_sync['sync']=false;
            $hasil_sync['message']=$msg_error;
        }
        return $hasil_sync;
            
    }
    public function sync_tpk($category="tpk",$data) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $errorlevel=error_reporting();
        //error_reporting($errorlevel & ~E_NOTICE);
        $msg_error="";
        if(!$this->connect()){
            $msg_error="Tidak dapat terhubung ke server, silahkan cek kembali URL, username atau password API";
        }else{
            if(empty($data)){
                $msg_error="Data kosong";
            }
        }
        $hasil_sync=array();
        $kategori=$category;
        if(trim($msg_error)==""){
            
            $sync_success   = false;
            $message        = "";
            switch($category){
                case "tpk":
                    $kategori="TPK";
                    $ids = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', (int)$data->id),array('x_category', '=', "tpk")
                                                    )
                                            ),array('limit'=>1));
                    
                   // print_r($ids);
                    $cek_data = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.location', 'read', array($ids));  
                    //echo "<pre>"; print_r($cek_data);echo "</pre>";
                    $id_odoo= !empty($ids)?$ids[0]:""; 
                    if(!empty($cek_data)){
                        $col_and_values=array("name"=>$data->name,
                                        "x_address"=>$data->address,
                                        "active"=>$data->is_active);
                        $result = $this->models->execute_kw($this->database, $this->connect(), $this->password, 'stock.location', 'write',
                                        array(array((int)$id_odoo), $col_and_values));
                    
                        if(!is_array($result) and $result==true){
                            $sync_success=true;
                            $message="Berhasil sync update Location TPK";
                        }else{
                            $message="Gagal sync update Location TPK. Error : ".$result['faultString'];//$result;
                        }
                    }else{
                        // insert
                        $col_and_values=array("name"=>$data->name,
                                            "x_tpk_erp"=>(int)$data->id,
                                            "x_address"=>$data->address,
                                            "x_category"=>$category,
                                            "active"=>$data->is_active,
                                            "location_id"=>11);
                                            
                        $create_tpk = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.location', 'create',array($col_and_values));
                        //$get_data=$this->get_purchase($id);
                        //echo "<pre>";print_r($create_tpk);echo "</pre>";exit;
                        if(!is_array($create_tpk) and trim($create_tpk)<>""){
                            $id_odoo=(int)$create_tpk;
                            $sync_success=true;
                            $message="Berhasil sync create Location TPK";
                        }else{
                            $message="Gagal sync create Location TPK";
                        }
                    }
                    if($sync_success){
                        $sql_up="UPDATE mcp SET sync=1 WHERE id=".$data->id."";
                        $db->query($sql_up);
                    }
                       
                break;
                case "kelompok":
                   $kategori="Kelompok";
                    $ids = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', (int)$data->id),array('x_category', '=', 'kelompok')
                                                    )
                                            ),array('limit'=>1));
                    
                   // print_r($ids);
                    $cek_data = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.location', 'read', array($ids));  
                   
                    $id_odoo= !empty($ids)?$ids[0]:""; 
                    if(!empty($cek_data)){
                        $ids_tpk = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', $data->mcp_id),array('x_category', '=', 'tpk')
                                                    )
                                            ),array('limit'=>1));
                        
                        if(!empty($ids_tpk)) {                   
                            $id_tpk_odoo=(int)$ids_tpk[0]; 
                            $col_and_values=array("name"=>$data->name,
                                                    "location_id"=>(int)$id_tpk_odoo);
                            $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'stock.location', 'write',
                                        array(array((int)$id_odoo), $col_and_values));
                           
                            if(!is_array($result) and $result==true){
                                $sync_success=true;
                                $message="Berhasil sync update Kelompok";
                            }else{
                                $message="Gagal sync update Kelompok";//$result;
                            }
                        }else{
                            $message="Gagal sync update Kelompok. ID TPK di Odoo tidak ada";//$result;
                        }
                    }else{
                        // insert
                        $ids_tpk = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', (int)$data->mcp_id),array('x_category', '=', "tpk")
                                                    )
                                            ),array('limit'=>1));
                        if(!empty($ids_tpk)) {   
                            $id_tpk_odoo=$ids_tpk[0]; 
                            $col_and_values=array("name"=>$data->name,
                                                "x_tpk_erp"=>(int)$data->id,
                                                "x_category"=>$category,
                                                "location_id"=>(int)$id_tpk_odoo);
                                                
                            $create_klp = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.location', 'create',array($col_and_values));
                            //$get_data=$this->get_purchase($id);
                            if(!is_array($create_klp) and trim($create_klp)<>""){
                                $id_odoo=(int)$create_klp;
                                $sync_success=true;
                                $message="Berhasil sync create Kelompok";
                            }else{
                                $message="Gagal sync create Kelompok";
                            }
                        }else{
                            $message="Gagal sync update Kelompok. ID TPK di Odoo tidak ada";//$result;
                        }
                    }
                    if($sync_success){
                        $sql_up="UPDATE kelompok SET sync=1 WHERE id=".$data->id."";
                        $db->query($sql_up);
                    }
                       
                break;
                case "kelompok_harga":
                    $kategori="Kelompok Harga";
                    $ids = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'stock.location', 'search',array(array( array('x_tpk_erp', '=', (int)$data->id),array('x_category', '=', 'kelompok_harga')
                                                    )),array('limit'=>1));
                    $cek_data = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.location', 'read', array($ids));  
                    //echo "<pre>"; print_r($cek_data);echo "</pre>";
                    $id_odoo= !empty($ids)?$ids[0]:""; 
                    if(!empty($cek_data)){
                         $ids_kelompok = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', (int)$data->kelompok_id),array('x_category', '=', "kelompok")
                                                    )
                                            ),array('limit'=>1));
                        if(!empty($ids_kelompok)) {   
                            $id_klp_odoo=$ids_kelompok[0]; 
                            $col_and_values=array("name"=>$data->name,
                                                "x_tpk_erp"=>(int)$data->id,
                                                "x_category"=>$category,
                                                "location_id"=>(int)$id_klp_odoo);
                            $result=$this->models->execute_kw($this->database, $this->connect(), $this->password, 'stock.location', 'write',
                                            array(array((int)$id_odoo), $col_and_values));
                            
                            if(!is_array($result) and $result==true){
                                $sync_success=true;
                                $message="Berhasil sync update Kelompok Harga";
                            }else{
                                $message="Gagal sync update Product List";//$result;
                            }
                        }else{
                            $message="Gagal sync update Kelompok Harga. Kelompok terkait belum ada di Odoo";//$result;
                        }
                    }else{
                        // insert
                        $ids_kelompok = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                                    'stock.location', 'search',array(array(
                                                    array('x_tpk_erp', '=', (int)$data->kelompok_id),array('x_category', '=', "kelompok")
                                                    )
                                            ),array('limit'=>1));
                        if(!empty($ids_kelompok)) {   
                            $id_klp_odoo=$ids_kelompok[0]; 
                            $col_and_values=array("name"=>$data->name,
                                                "x_tpk_erp"=>(int)$data->id,
                                                "x_category"=>$category,
                                                "location_id"=>(int)$id_klp_odoo);
                                                
                            $id_odoo = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.location', 'create',array($col_and_values));
                            if(!is_array($id_odoo) and trim($id_odoo)<>""){
                                $product_id=$product;
                                $sync_success=true;
                                $message="Berhasil sync create Kelompok Harga";
                            }else{
                                $message="Gagal sync create Kelompok Harga";
                            }
                        }else{
                            $message="Gagal sync insert Kelompok Harga. Kelompok terkait belum ada di Odoo";//$result;
                        }
                    }
                    if($sync_success){
                        $sql_up="UPDATE kelompok_harga SET  sync=1 WHERE id=".$data->id."";
                        $db->query($sql_up);
                    }
                break;
            }
            if($sync_success){
                $hasil_sync['sync']=true;
                $hasil_sync['id']=$data->id;
                $hasil_sync['name']=$data->name;
                $hasil_sync['category']=$kategori;
                $hasil_sync['message']=$message;
            }else{
                $hasil_sync['sync']=false;
                $hasil_sync['id']=$data->id;
                $hasil_sync['name']=$data->name;
                $hasil_sync['category']=$kategori;
                $hasil_sync['message']=$message;
            }
        }else{
            $hasil_sync['sync']=false;
            $hasil_sync['message']=$msg_error;
        }
        return $hasil_sync;
    }
    
    public function sync_member($data) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $errorlevel=error_reporting();
        //error_reporting($errorlevel & ~E_NOTICE);
        $master=new Master_Ref_Model();
         $msg_error="";
        if(!$this->connect()){
            $msg_error="Tidak dapat terhubung ke server, silahkan cek kembali URL, username atau password API";
        }else{
            if(empty($data)){
                $msg_error="Data kosong";
            }
        }
      // echo "<pre>";print_r($data);echo "</pre>";exit;
        $hasil_sync=array();
        if(trim($msg_error)==""){
            $status_anggota=trim($data->status)==""?0:$data->status;
   	        $member_id      =$data->C_ANGGOTA;
            $ids_kelompok = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                                'stock.location', 'search',array(array(
                                                array('x_tpk_erp', '=', $data->ID_KELOMPOK),array('x_category', '=', "kelompok")
                                                )
                                        ),array('limit'=>1));
            $id_klp_odoo = (int)$ids_kelompok[0]; // ID Kelompok  di Odoo
            
            $ids_kelompok_harga = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                                'stock.location', 'search',array(array(
                                                array('x_tpk_erp', '=', $data->ID_KELOMPOK_HARGA),array('x_category', '=', "kelompok_harga")
                                                )
                                        ),array('limit'=>1));
            $id_kh_odoo = (int)$ids_kelompok_harga[0]; // ID Kelompok Harga di Odoo
            
            $img_file    = "http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
            $imgData     = "";
            if(file_get_contents($img_file)){
                $imgData = base64_encode(file_get_contents($img_file));
            }
            
            $ids = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                'res.partner', 'search',array(array(
                                                array('x_member_id', '=', $member_id)
                                                )
                                        ),array('limit'=>1));
            $id_odoo    = (int)$ids[0]; // ID Member di Odoo
            $cek_data   = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                'res.partner', 'read', array($ids));  
            // echo "<pre>"; print_r($cek_data);echo "</pre>";
            $set_values ="";
            $sync       =false;
            if(!empty($ids)){
                // update
                $col_and_values=array("is_company"=>false,
                                    "customer"=>true,
                                    "supplier"=>true,
                                    "x_kelompok"=>$id_klp_odoo,
                                    "x_kelompok_harga"=>$id_kh_odoo,
                                    "x_is_member"=>true,
                                    "x_status"=>(int)$status_anggota,
                                    "property_product_pricelist"=>2,// 2: anggota , 1= Public
                                    "x_member_id"=>$data->C_ANGGOTA);
                $col_and_values=trim($imgData)==""?$col_and_values:array_merge($col_and_values,array("image"=>$imgData));
                $col_and_values=trim($data->TGL_MASUK)==""?$col_and_values:array_merge($col_and_values,array("x_registration_date"=>$data->TGL_MASUK));
                $col_and_values=trim($data->TGL_LAHIR)==""?$col_and_values:array_merge($col_and_values,array("x_birthdate"=>$data->TGL_LAHIR));
                                  
                $result=$this->models->execute_kw($this->database, $this->connect(), $this->password, 'res.partner', 'write',
                                array(array($id_odoo), $col_and_values));
                
                $msg_error="Gagal sync update";
                
                if(!is_array($result) and $result==true){
                    $set_values="odoo_id=$id_odoo,sync='1'";
                    $sync=true;
                    $msg_error="Sync update data anggota";
                }else{
                    $msg_error=$msg_error." Error : ".$result['faultString'];
                }
                     
            }else{
                // insert
                $col_and_values=array("name"=>$data->NAMA,
                                    "is_company"=>false,
                                    "customer"=>true,
                                    "supplier"=>true,
                                    "x_kelompok"=>$id_klp_odoo,
                                    "x_kelompok_harga"=>$id_kh_odoo,
                                    "x_is_member"=>true,
                                    "x_status"=>(int)$status_anggota,
                                    "property_product_pricelist"=>2,
                                    "x_member_id"=>$data->C_ANGGOTA);
                $col_and_values=trim($imgData)==""?$col_and_values:array_merge($col_and_values,array("image"=>$imgData));
                $col_and_values=trim($data->TGL_MASUK)==""?$col_and_values:array_merge($col_and_values,array("x_registration_date"=>$data->TGL_MASUK));
                $col_and_values=trim($data->TGL_LAHIR)==""?$col_and_values:array_merge($col_and_values,array("x_birthdate"=>$data->TGL_LAHIR));
                          
                                    
                $result = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                'res.partner', 'create',array($col_and_values));
              
                $msg_error="Gagal sync insert data";
                if(!is_array($result) and trim($result)<>""){
                    $id_odoo=$result;
                    $sync=true;
                    $set_values="odoo_id=$id_odoo,sync='1'";
                    $msg_error="Sync insert data";
                }
                
                
            }
           
            if(trim($set_values)<>""){
                $sql_up="UPDATE anggota SET $set_values WHERE ID_ANGGOTA=".$data->ID_ANGGOTA."";
                $db->query($sql_up);
            }
            $hasil_sync['sync']=$sync;
            $hasil_sync['message']=$msg_error;
            $hasil_sync['odoo_id']=$id_odoo;
            $hasil_sync['nomor_anggota']=$data->C_ANGGOTA;
            $hasil_sync['nama_anggota']=$data->NAMA;
            
               
        }else{
            $hasil_sync['sync']=false;
            $hasil_sync['message']=$msg_error;
        }
      //  print_r($hasil_sync);exit;
        return $hasil_sync;
       
    }
    
    public function sync_sale($data) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        
        /** 
         * state : draft (Quotation),sent (Quotation Sent), sale (Sales Order), done (Locked), cancel (Cancelled)
         * invoice_status : upselling (Upselling Oppotunity), invoiced (Fully Invoiced), to invoice (To Invoice), 
         *                  no (Nothing to Invoice)
         * 
         * trx_id   =>  xxx,
         * sale_id  =>  zzz,//sale_id Odoo
         * tanggal  =>  12/09/2018,
         * anggota_id       =>  12/09/2018,
         * anggota_odoo_id  =>  12/09/2018,
         * nama_anggota     =>  12/09/2018,
         * item     =>  1 =>    (id     =>
         *                      line_id =>yy1,
         *                      barang  => Beras}
         *              2 =>    (id     =>
         *                      line_id =>yy2,
         *                      barang  => Ember}
         *                                   */
        
      // echo '<pre>';print_r($data);echo '</pre>';exit;
        $errorlevel=error_reporting();
        //error_reporting($errorlevel & ~E_NOTICE);
        $master=new Master_Ref_Model();
        $msg_error="";
        if(!$this->connect()){
            $msg_error="Tidak dapat terhubung ke server, silahkan cek kembali URL, username atau password API";
        }else{
            if(empty($data)){
                $msg_error="Data kosong";
            }
        }
        
        $hasil_sync=array();
        if(trim($msg_error)==""){
           
            $msg_error="";
           $sync_success=false;
           $create_sale=false;
           $is_ada=false;
           $sale_id=$data->sale_id;
           
           if(trim($data->sale_id)<>""){
                $cek_sale= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'sale.order', 'search',array(array(array('id', '=', (int)$sale_id))));
                if(!empty($cek_sale)){
                    $is_ada=true;
                }
           }
           $exist_member=false;
           if(trim($data->odoo_anggota_id)<>""){
                 $cek_member= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'res.partner', 'search',array(array(array('id', '=', (int)$data->odoo_anggota_id))));
                 if(!empty($cek_member)){
                    $exist_member=true;
                }       
            }
            if($exist_member==true){
               if($is_ada==false){//insert
              
                     $col_and_values=array("partner_id"=>(int)$data->odoo_anggota_id,//$data['odoo_anggota_id'],// a adin
                            "order_date"=>$data->tanggal,
                            "validity_date"=>$data->tanggal,
                            "state"=>"draft",
                            "pricelist_id"=>2);   // 2 : anggota  
                     
                    $sale = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'sale.order', 'create',array($col_and_values));
                     
                    if(!is_array($sale) and trim($sale)<>""){
                        $sale_id=(int)$sale;
                        $create_sale=true;
                        $msg_error="Berhasil sync create sale";
                    }else{
                        $msg_error="Gagal sync create sale";
                    }
                   
                }else{//update
                     $col_and_values=array("partner_id"=>(int)$data->odoo_anggota_id,//$data['odoo_anggota_id'],// a adin
                            "order_date"=>$data->tanggal,
                            "validity_date"=>$data->tanggal,
                            "state"=>"draft",//sale
                            "pricelist_id"=>2);   // 2 : anggota  
                    $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'sale.order', 'write',
                                    array(array((int)$sale_id), $col_and_values));
                    // echo "<pre>";print_r($result);echo "</pre>";
                    if(!is_array($result) and $result==true){
                        $msg_error="Berhasil sync update sale";
                        $create_sale=true;	
                    }else{
                        $msg_error="Gagal sync update sale. Error : ".$result['faultString'];//$result;
                    }
                }
              }else{
                  $msg_error="Gagal sync sale, anggota belum disync";//$result;
              }
            //echo "create sale :".$create_sale." ".$msg_error;exit;
            if($create_sale){
                $val_and_values="odoo_id=$sale_id";
                foreach($data->item as $key=>$value){
                  
                    $is_line_ada=false;
                    $order_line_id=(int)$value->line_id;
                    $desc=str_replace(array('<b>', $value->nama_barang,'</b><br/>(',')'), array('','','',''), $value->description);
                    if(trim($value->line_id)<>""){
                        $cek_sale_line= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'sale.order.line', 'search',array(array(array('order_id', '=', (int)$sale_id),
                                                              array('id', '=', (int)$value->line_id))));
                         
                        if(!empty($cek_sale_line)){
                            $is_line_ada=true;
                        }
                    }
                    if($is_line_ada==false){//insert
                        $col_and_values_line=array(
                                                "name"=>$desc,//$data['data']['pendapatan_kotor']['product_desc'],
                                                "order_id"=>(int)$sale_id,
                                                "product_id"=>(int)$value->odoo_barang_id,//$data['pendapatan_kotor'],
                                                'product_uom' =>(int)$value->uom_id,
                                                "product_uom_qty"=>(int)$value->qty,//$data['jumlah'],
                                                "price_unit"=>(int)$value->harga,
                                                 "tax_id"=>"");
                                               // "tax_id"=>array(array(6,0,array(1))));
                    
                        $order_line = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                            'sale.order.line', 'create',array($col_and_values_line));
                  // echo 'create <pre>'; print_r($col_and_values_line); echo '</pre>';
                  // echo '<pre>';print_r($order_line);echo '</pre> end'; exit;
                        if(!is_array($order_line) and trim($order_line)<>""){
                            $order_line_id=(int)$order_line;
                            $sync_success=true;
                            
                        }else{
                            $msg_error="Gagal sync create sale order line";//$result;
                        }
                    
                    }else{//update
                        $line_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'sale.order.line', 'search',array(array(array('order_id', '=', (int)$sale_id),
                                                              array('id', '=', (int)$order_line_id))));
                        
                       $col_and_values_line=array(
                                                "name"=>$desc,//$data['data']['pendapatan_kotor']['product_desc'],
                                                "order_id"=>(int)$sale_id,
                                                "product_id"=>(int)$value->odoo_barang_id,//$data['pendapatan_kotor'],
                                                'product_uom' =>(int)$value->uom_id,
                                                "product_uom_qty"=>(int)$value->qty,//$data['jumlah'],
                                                "price_unit"=>$value->harga,
                                                "tax_id"=>array(array(6,0,array(1))));
                        $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'sale.order.line', 'write',
                                    array($line_ids, $col_and_values_line)); 
                          //echo 'update <pre>'; print_r($col_and_values_line); echo '</pre>';echo '<pre>';print_r($result);echo '</pre>end'; 
                        if(!is_array($result) and $result==true){
                            $sync_success=true;
                        }else{
                            $msg_error="Gagal sync update sale order line";//$result;
                        }
                    }
                    if($sync_success){
                        $val_and_values=$val_and_values.",line_id=$order_line_id,sync='1'";
                    }
                    
                    $sql_up="UPDATE logistik SET $val_and_values WHERE id=".$value->id."";
                   // echo $sql_up;
                    $db->query($sql_up);
                    
                }//end of foreach
                
            }
            if($sync_success){
                $TglSkrg=date("Y-m-d H:i:s");
                 /** ======== state -> sale ===================*/
                 $col_and_values=array("state"=>"sale",
                                        "confirmation_date"=>$TglSkrg);  
                 $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'sale.order', 'write',
                                array(array((int)$sale_id), $col_and_values));
                /** ========================================= */
                 /** ======== state -> locked ===================*/
                /* $col_and_values=array("state"=>"done");  
                 $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'sale.order', 'write',
                                array(array((int)$sale_id), $col_and_values));
                
                /** ======== state -> invoiced ===================*/
                /* $col_and_values=array("invoice_status"=>"invoiced");  
                 $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'sale.order', 'write',
                                array(array((int)$sale_id), $col_and_values));
                /** ========================================= */
                $hasil_sync['sync']=true;
                $hasil_sync['id']=$data->id;
                $hasil_sync['tanggal']=$data->tanggal;
                $hasil_sync['anggota']="[".$data->nomor_anggota."] ".$data->nama_anggota;
                $hasil_sync['message']=trim($msg_error)==""?"Berhasil":$msg_error;
                 $hasil_sync['data']=array("sale_id"=>$sale_id,"line_id"=>$order_line_id);
            }else{
                $hasil_sync['sync']=false;
                $hasil_sync['tanggal']=$data->tanggal;
                $hasil_sync['anggota']="[".$data->nomor_anggota."] ".$data->nama_anggota;
                $hasil_sync['message']=$msg_error;
            }
               
        }else{
            $hasil_sync['sync']=false;
            $hasil_sync['message']=$msg_error;
        }
        
        return $hasil_sync;
       
    }
   
    public function sync_purchase($data,$state="draft") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        
        /** KUHUSUs UNTUK PEMBELIAN SUSU KE PETERNAK DALAM SATU PERIODE
         * state : draft (RFQ),sent (RFQ Sent), to approve, purchase (Purchase Order), done (Locked), cancel (Cancelled)
         * invoice_status : no (Nothing to Bill), to invoice (To Invoice), invoiced (No to Bill Received)
         * 
         * trx_id   =>  xxx,
         * purchase_id  =>  zzz,//sale_id Odoo
         * tanggal  =>  12/09/2018,
         * anggota_id       =>  12/09/2018,
         * anggota_odoo_id  =>  12/09/2018,
         * nama_anggota     =>  12/09/2018,
         * item     =>  1 =>    (id     =>
         *                      product_odoo_id, // 2 : susu
         * =========================================================*/
        
        // echo '<pre>';print_r($data);echo '</pre>';
        //$errorlevel=error_reporting();
        //error_reporting($errorlevel & ~E_NOTICE);
        $msg_error="";
        if(!$this->connect()){
            $msg_error="Tidak dapat terhubung ke server, silahkan cek kembali URL, username atau password API";
        }else{
            if(empty($data)){
                $msg_error="Data kosong";
            }
        }
        
        $hasil_sync=array();
        if(trim($msg_error)==""){
           
           $msg_error       ="";
           $sync_success    =false;
           $create_purchase =false;
           $is_ada          =false;
           $purchase_id     =(int)$data->purchase_id;
          
           if(trim($purchase_id)<>""){
                $cek_purchase= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'purchase.order', 'search',array(array(array('id', '=', (int)$purchase_id))));
        
                if(!empty($cek_purchase)){
                    $is_ada=true;
                }
           }
        
           if(trim($data->odoo_anggota_id)<>""){
               if($is_ada==false){//insert
              
                    $col_and_values=array("partner_id"=>(int)$data->odoo_anggota_id,// a adin
                        "date_order"=>$data->closing_date,
                        "state"=>"draft",//"purchase",
                        "invoice_status"=>"no",
                         "company_id"=>1,
                        "pricelist_id"=>2);  
                    $purchase = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'purchase.order', 'create',array($col_and_values));
                    
                    if(!is_array($purchase) and trim($purchase)<>""){
                        $purchase_id=(int)$purchase;
                        $create_purchase=true;
                        $msg_error="Berhasil sync create purchase";
                    }else{
                        $msg_error="Gagal sync create purchase";
                    }
                   
                }else{//update
                
                     $col_and_values=array("partner_id"=>(int)$data->odoo_anggota_id,// a adin
                        "date_order"=>$data->closing_date,
                        "state"=>"draft",//"purchase",
                        "invoice_status"=>"no",
                         "company_id"=>1,
                        "pricelist_id"=>2);     // 2 : anggota  
                    
                    $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'purchase.order', 'write',
                                    array(array((int)$purchase_id), $col_and_values));
                    // echo "<pre>";print_r($result);echo "</pre>";
                    if(!is_array($result) and $result==true){
                        $msg_error="Berhasil sync update purchase";
                        $create_purchase=true;	
                    }else{
                        $msg_error="Gagal sync update purchase. Error : ".$result['faultString'];//$result;
                    }
                }
              }else{
                  $msg_error="Gagal sync PURCHASE, anggota belum disync";//$result;
              }
        //echo "create purchase :".$create_purchase." ".$msg_error;exit;
             $order_line_id="";
             $item=$data->pendapatan_kotor;
            if($create_purchase){
                
                $val_and_values="purchase_id=$purchase_id";
                $is_line_ada=false;
                $cek_purchase_line= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                'purchase.order.line', 'search',array(array(array('order_id', '=', (int)$purchase_id))));
                 
                $cek_del= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'purchase.order.line', 'unlink',array($cek_purchase_line));
                if($cek_del){  
                    $col_and_values_line=array(
                                        "name"=>$item->product_desc,//$data['data']['pendapatan_kotor']['product_desc'],
                                        "order_id"=>(int)$purchase_id,
                                        "date_planned"=>$data->closing_date,
                                        "product_id"=>(int)$item->product_odoo_id,
                                        "product_qty"=>(float)$item->qty,
                                        "qty_received"=>(float)$item->qty,
                                        "qty_invoiced"=>(float)$item->qty,
                                        "price_unit"=>(float)$item->price_unit,
                                        "company_id"=>1,
                                        "product_uom"=>(int)$item->product_uom);
                
                    $order_line = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'purchase.order.line', 'create',array($col_and_values_line));
                //echo '<pre>';print_r($order_line);echo '</pre>'; 
                    if(!is_array($order_line) and trim($order_line)<>""){
                        $order_line_id=$order_line;
                        $sync_success=true;
                        
                    }else{
                        $msg_error="Gagal sync create purchase order line";//$result;
                    }
                }
                
                $sql_up="UPDATE anggota_pendapatan SET $val_and_values WHERE id=".$data->id."";
                $db->query($sql_up);
                    
              
                
            }
            $origin_name    ="";
            $TglSkrg        =date("Y-m-d H:i:s");
            $picking_id     =$data->odoo_picking_id;
            $stock_move_id  =$data->odoo_stock_move_id;
            if($sync_success){
                 /** ======== state -> purchase ===================*/
                /* $col_and_values1=array("state"=>"to approve",
                                    "date_approve"=>$TglSkrg); */
             
                // ======== buat Icoming Products (stock.move), state:available ===================*/
                $pcs = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                'purchase.order', 'search_read',array(array(array('id', '=', (int)$purchase_id))),
                array('fields'=>array('id',	'name')));
                $origin_name=$pcs[0]['name'];
                
                $col_and_values_pick=array("state"=>"done",
                        "origin"=>$origin_name,
                        "partner_id"=>(int)$data->odoo_anggota_id,
                        "date"=>$data->closing_date,
                        "date_done"=>$data->closing_date,
                        "purchase_id"=>(int)$purchase_id,
                        "scheduled_date"=>$data->closing_date,
                        "location_id"=>2,//2 : Partner Location
                        "location_dest_id"=>15,//WH/Stock
                        "picking_type_id"=>5,
                        "show_validate"=>true,
                        "picking_type_id"=>1);//5:	Internal Transfers
                        
                if(trim($data->odoo_picking_id)==""){
                    $picking_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'stock.picking', 'search',array(array(array('purchase_id', '=', (int)$purchase_id))));
                    if(!empty($picking_ids)){
                        $picking_id=$picking_ids[0];
                    }
                }
                if(trim($picking_id)==""){//insert
                
                //terlebih dahulu delete picking dan stick move
                   /* $picking_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'stock.picking', 'search',array(array(array('purchase_id', '=', (int)$purchase_id))));
                    $stock_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'stock.move', 'search',array(array(array('picking_id', '=', (int)$picking_ids[0]))));
                    $del_stock= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'stock.move', 'unlink',array($stock_ids));
                    $del_picking= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'stock.picking', 'unlink',array($picking_ids));*/ // for delete
                    $picking = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                            'stock.picking', 'create',array($col_and_values_pick));
                    if(!is_array($picking) and trim($picking)<>""){
                      
                        $picking_id=$picking;
                    }
                }else{//update
                     $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'stock.picking', 'write',
                                    array(array((int)$picking_id), $col_and_values_pick));
                }//end if $data->odoo_picking_id)<>""

                if(trim($picking_id)<>""){
                    
                    $picking_id=(int)$picking_id;
                    $val_and_values_stock="pendapatan_id=".$data->id.",barang_id=".$item->product_erp_id.",odoo_picking_id=$picking_id,
                                        quantity=".$item->qty."";
                    $cols_stock="pendapatan_id,barang_id,odoo_picking_id,quantity";
                    $vals_stock="".$data->id.",".$item->product_erp_id.",".$picking_id.",".$item->qty."";
                    
                    if(trim($data->odoo_stock_move_id)==""){
                        $stock_move_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'stock.move', 'search',array(array(array('picking_id', '=', (int)$picking_id))));
                        if(!empty($stock_move_ids)){
                            $stock_move_id=$stock_move_ids[0];
                        }
                    }
                    
                    $col_and_values_move=array("name"=>"Pembelian Susu",
                                        "state"=>"done",
                                        "origin"=>$origin_name,
                                        "partner_id"=>(int)$data->odoo_anggota_id,
                                        "date"=>$data->closing_date,
                                        "date_expected"=>$data->closing_date,
                                        "picking_id"=>$picking_id,
                                        "company_id"=>1,//1 : KPBS Pangalengan
                                        "location_dest_id"=>15,
                                        "product_id"=>(int)$item->product_odoo_id,
                                        "product_uom"=>(int)$item->product_uom,
                                        "location_id"=>2,
                                        "location_dest_id"=>15,
                                        "product_uom_qty"=>(float)$item->qty,
                                        "quantity_done"=>(float)$item->qty);
                                       
                    if(trim($stock_move_id)==""){//insert
                        $move_id = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'stock.move', 'create',array($col_and_values_move));
                         if(!is_array($move_id) and trim($move_id)<>""){
                            $stock_move_id=$move_id;
                          
                         }
                    
                    }else{//update
                        $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'stock.move', 'write',
                                    array(array((int)$stock_move_id), $col_and_values_move));
                                   
                    }
                   /* $sm_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'stock.move', 'search',array(array(array('id', '=', (int)$stock_move_id))));
                     $cek_data = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.move', 'read', array($sm_ids));
                       echo "<pre>";print_r($cek_data);echo "</pre>";
                      $cek_data_ml = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.move.line', 'read', array($cek_data[0]['move_line_ids']));
                     
                      
                      echo "<pre>";print_r($cek_data_ml);echo "</pre>";*/
                    if(trim($stock_move_id)<>""){// cek kemali apakah stock.move ada di odoo, kalo ada update ke erp
                         $val_and_values_stock=$val_and_values_stock.",odoo_stock_move_id=$stock_move_id";
                         $cols_stock=$cols_stock.",odoo_stock_move_id";
                         $vals_stock=$vals_stock.",".$stock_move_id."";
                    }
                    
                    
                    $col_and_values3=array("picking_ids"=>array(array(6,0,array($picking_id))),
                                    "is_shipped"=>1,
                                    "invoice_status"=>"to invoice",
                                    "state"=>"purchase",
                                    "date_approve"=>$TglSkrg,
                                    "picking_count"=>1); 
                    $result3= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'purchase.order', 'write',
                            array(array((int)$purchase_id), $col_and_values3));
                    //upfate order line 
                      $col_and_values_line2=array("qty_received"=>(float)$item->qty,"qty_invoiced"=>(float)$item->qty,);
                      $result_ol= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'purchase.order.line', 'write',
                                    array(array((int)$order_line_id), $col_and_values_line2));
                                 
            
                    $sql_up="";
                    if(trim($data->odoo_picking_id)<>""){// cek di erp sudah ada belum
                        $sql_up="UPDATE logistik_stock_move SET $val_and_values_stock WHERE pendapatan_id=".$data->id.";";
                        
                    }else{
                        $sql_up="INSERT INTO logistik_stock_move ($cols_stock) VALUES($vals_stock);";
                    }
                  
                    $db->query($sql_up);
                
                }//end picking            
                    
                $hasil_sync['sync']=true;
                $data_hsl['id']=$data->id;
                $data_hsl['origin_name']=$origin_name;
                $data_hsl['product_name']=$purchase_id;
                $data_hsl['purchase_id']=$purchase_id;
                $data_hsl['line_id']=$order_line;
                $data_hsl['picking_id']=$picking_id;
                $data_hsl['stock_move_id']=$stock_move_id;
                $data_hsl['tanggal']=$data->closing_date;
                $data_hsl['anggota']="[".$data->nomor_id."] ".$data->name;
                $hasil_sync['message']=trim($msg_error)==""?"Berhasil":$msg_error;
                $hasil_sync['data']=$data_hsl;
            }else{
                $hasil_sync['sync']=false;
                $hasil_sync['message']=$msg_error;
                $data_hsl['id']=$data->id;
                $data_hsl['purchase_id']=$purchase_id;
                $data_hsl['line_id']=$order_line;
                $data_hsl['tanggal']=$data->closing_date;
                $data_hsl['anggota']="[".$data->nomor_id."] ".$data->name;
               
                
                $hasil_sync['data']=$data_hsl;
            }
               
        }else{
            $hasil_sync['sync']=false;
            $hasil_sync['message']=$msg_error;
        }
       //  echo '<pre>';print_r($hasil_sync);echo '</pre>';exit;
        return $hasil_sync;
       
    }
    public function sync_pendapatan($data) {
        
     
        $purchase=$this->sync_purchase($data);
        echo 'hasil sync purchase:<pre>';print_r($purchase);echo '</pre>';
       // exit;
        if($purchase['sync']==true){
            $purchase_id=(int)$purchase['data']['purchase_id'];
            $origin_name=$purchase['data']['origin_name'];
            $item=$data->pendapatan_kotor;
            
            // buat vendor bill
             // ======== invoice_status -> invoiced ===================
             $odoo_invoice_id     =$data->odoo_invoice_id;    
             if(trim($data->odoo_invoice_id)==""){
                $invoice_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                'account.invoice', 'search',array(array(array('purchase_id', '=', (int)$purchase_id))));
                if(!empty($invoice_ids)){
                    $odoo_invoice_id=$invoice_ids[0];
                }
            } 
            /*$col_and_values_bill=array("state"=>"open",
                        "origin"=>$origin_name,
                        "partner_id"=>(int)$data->odoo_anggota_id,
                        "company_id"=>1,//1 : KPBS Pangalengan
                        "date"=>$data->closing_date,
                        "date_due"=>$data->closing_date, 
                        "date_invoice"=>$data->closing_date,
                        "purchase_id"=>(int)$purchase_id,
                        "journal_id"=>2,//2 : Vendor Bill -> KPBS Pangalengan
                        "account_id"=>499,//499 : 21.01.01.01 HUTANG SUSU SEGAR ANGGOTA
                        "currency_id"=>13,//13 : IDR, 3 : USD
                        );*/
            //1. Create invoice
            
            $member_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                            'res.partner', 'search',array(array(array('id', '=', (int)$data->odoo_anggota_id))));
            $member = $this->models->execute_kw($this->database, $this->connect(), $this->password,'res.partner', 'read', array($member_ids));
                        $member=current($member);   
                           echo "member :<pre>";print_r($member);echo "</pre>";
            $account_payable=$member['property_account_payable_id'][0];
            $company_id=$member['company_id'][0];
            
            $journal_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                            'account.journal', 'search',array(array(array('type', '=','purchase'),array('company_id', '=',(int)$company_id))));
            $purchase_journal_id=$journal_ids[0];
            // get journal ()
            $col_and_values_bill=array("origin"=>$origin_name,
                        "partner_id"=>(int)$data->odoo_anggota_id,
                        "company_id"=>(int)$company_id,//1 : KPBS Pangalengan
                        "date_due"=>$data->closing_date, 
                        "date_invoice"=>$data->closing_date,
                        "account_id"=>$account_payable,//account receivable or account payable
                        "journal_id"=>$purchase_journal_id,//2 : Vendor Bill -> KPBS Pangalengan
                        "purchase_id"=>(int)$purchase_id,
                        "currency_id"=>13,//13 : IDR, 3 : USD
                        "type"=>"in_invoice"//‘out_invoice’ atau ‘in_invoice’ (customer dan supplier invoice) 
                        );
            if(trim($odoo_invoice_id)==""){  // insert 
                $bill_id = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                'account.invoice', 'create',array($col_and_values_bill)); 
                echo 'inv <pre>';print_r($bill_id);echo '</pre>';
                if(!is_array($bill_id) and trim($bill_id)<>""){
                    $odoo_invoice_id=$bill_id;
                }
                /* echo 'vendor bill :<pre>';print_r($bill_id);echo '</pre>';
                 $sm_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                            'stock.move', 'search',array(array(array('id', '=', (int)$stock_move_id))));
                         $cek_data = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.move', 'read', array($sm_ids));
                           echo "<pre>";print_r($cek_data);echo "</pre>";*/
            }else{//update
                $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'account.invoice', 'write',
                                    array(array((int)$odoo_invoice_id), $col_and_values_bill));
            }//end if $odoo_invoice_id==""
            $inv_line_id="";
            if(trim($odoo_invoice_id)<>""){//insert
               
                 /*$col_and_values_bill_line=array("name"=>$origin_name,
                                        "invoice_id"=>(int)$odoo_invoice_id,
                                        "partner_id"=>(int)$data->odoo_anggota_id,
                                        "price_unit"=>(float)$item->price_unit,
                                        "product_id"=>(int)$item->product_odoo_id,
                                        "quantity"=>$item->qty,
                                        "purchase_id"=>(int)$purchase_id,
                                        "account_id"=>499,//499 : 21.01.01.01 HUTANG SUSU SEGAR ANGGOTA
                                        "currency_id"=>13,//13 : IDR, 3 : USD
                                        "uom_id"=>(int)$item->product_uom);*/
                 //2. Create account.invoice.line 
                $col_and_values_bill_line=array("name"=>$origin_name,
                                        "invoice_id"=>(int)$odoo_invoice_id,
                                        "partner_id"=>(int)$data->odoo_anggota_id,
                                        "price_unit"=>(float)$item->price_unit,
                                        "product_id"=>(int)$item->product_odoo_id,
                                        "quantity"=>$item->qty,
                                        "account_id"=>$account_payable,//499 : 21.01.01.01 HUTANG SUSU SEGAR ANGGOTA
                                        "currency_id"=>13,//13 : IDR, 3 : USD
                                        "uom_id"=>(int)$item->product_uom);
                $inv_line_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                            'account.invoice.line', 'search',array(array(array('invoice_id', '=', (int)$odoo_invoice_id))));
                if(empty($inv_line_ids)){
                    $bill_line = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'account.invoice.line', 'create',array($col_and_values_bill_line)); 
                    echo 'inv line <pre>';print_r($bill_line);echo '</pre>';
                    if(!is_array($bill_line) and trim($bill_line)<>""){
                         $inv_line_id=$bill_line;
                         $inv_line_ids=array($inv_line_id);
                    }
                }else{
                     $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'account.invoice.line', 'write',
                                    array(array((int)$inv_line_ids[0]), $col_and_values_bill_line));
                }
                print_r($inv_line_ids);
                $TglSkrg        =date("Y-m-d H:i:s");
                if(!empty($inv_line_ids)){
                    // 3. open invoice using workflow
                    
                  /**  $col_and_values_open_bill=array("state"=>"draft");
                    $open_inv= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'account.invoice', 'write',
                                    array(array((int)$odoo_invoice_id), $col_and_values_open_bill));*/
                     $open_inv= $this->models->execute($this->database, $this->connect(), $this->password, 'account.invoice', 'action_invoice_open',(int)$odoo_invoice_id);
                    echo "<pre>";print_r($open_inv);echo "</pre>";
                    
                    // account.move
                    $col_and_values_acc_move=array("journal_id"=>2,//2 : Vendor Bill -> KPBS Pangalengan
                                                "partner_id"=>(int)$data->odoo_anggota_id,
                                                "name"=>"Pembelian Susu Ke Anggota",
                                                "date"=>$TglSkrg);
                    $journal_entry = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                    'account.move', 'create',array($col_and_values_acc_move)); 
                     if(!is_array($journal_entry) and trim($journal_entry)<>""){
                         $journal_entry_id=$journal_entry;
                         
                         $col_and_values_acc_move_line=array("move_id"=>(int)$journal_entry_id,//2 : Vendor Bill -> KPBS Pangalengan
                                                "account_id"=>499,//499 : 21.01.01.01 HUTANG SUSU SEGAR ANGGOTA
                                                "debit"=>"Pembelian Susu Ke Anggota",
                                                "date"=>$TglSkrg);
                    }
                }
                 $inv_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                            'account.invoice', 'search',array(array(array('id', '=', (int)$odoo_invoice_id))));
                 $cek_data_inv = $this->models->execute_kw($this->database, $this->connect(), $this->password,'account.invoice', 'read', array($inv_ids));
                
                 echo "<pre>";print_r($cek_data_inv);echo "</pre>";
                $cek_data_il = $this->models->execute_kw($this->database, $this->connect(), $this->password,'account.invoice.line', 'read', array($inv_line_ids));
                echo 'vendor bill line:<pre>';print_r($cek_data_il);echo '</pre>';
            }
           
                                
            exit;   
                
                  $pcs_je = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'account.move', 'search_read',array(array(array('stock_move_id', '=', (int)$stock_move_id))),
                        array('fields'=>array('id',	'name','stock_move_id','journal_id')));              
                        echo "je :<pre>";print_r($pcs_je);echo "</pre>";
                  // ========================================= create journal entry
                 $je_id=$pcs_je[0]['id'];                  
                 /* $col_and_values_je=array("name"=>"Potongan-potongan",
                                            "date"=>$TglSkrg,
                                            "partner_id"=>(int)$data->odoo_anggota_id,
                                            "journal_id"=>2,//2 : Vendor Bill -> KPBS Pangalengan
                                            "stock_move_id"=>(int)$stock_move_id
                                            );
                   $je_id = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'account.move', 'create',array($col_and_values_je)); 
                        echo "create je :<pre>";print_r($je_id);echo "</pre>";  */  
                   if(!is_array($je_id) and trim($je_id)<>""){
                         $col_and_values_je_line=array("move_id"=>(int)$je_id,
                                            "account_id"=>540,//540 : 30.01.00.02 SIMPANAN WAJIB UP MT
                                            "debit"=>2000);
                        $je_line_id = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'account.move.line', 'create',array($col_and_values_je_line)); 
                       // echo "je line :<pre>";print_r($je_line_id);echo "</pre>"; 
                           
                    }
               
                         
                         $col_and_values5=array("invoice_status"=>"invoiced","invoice_count"=>1,"invoice_ids"=>array(array(6,0,array($bill_id))));  
                 $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'purchase.order', 'write',
                                array(array((int)$purchase_id), $col_and_values5));
                         
                         $pcs = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'purchase.order', 'search_read',array(array(array('id', '=', (int)$purchase_id))),
                        array('fields'=>array('id',	'name','picking_ids','is_shipped','invoice_ids')));              
                        echo "value :<pre>";print_r($pcs);echo "</pre>";
                        
                        
                         $pcs1 = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'account.invoice', 'search_read',array(array(array('id', '=', (int)$bill_id))),
                        array('fields'=>array('id',	'name','state','journal_id')));   
                        
                        
                        
            
            
            // get invoice_line_id
            //$purchase['purchase_id']=54;//
            $inv_line_ids = $this->models->execute_kw($this->database, $this->connect(), $this->password,
            'account.invoice.line', 'search_read',array(array(array('purchase_id', '=', (int)$purchase['purchase_id']))),
            array('fields'=>array('id',	'invoice_id', 'name', 'partner_id'), 'limit'=>1));
            
            $inv_line =current($inv_line_ids);
            $invoice_line_id=(int)$inv_line['id'];
            $invoice_id=(int)$inv_line['invoice_id'][0];
            echo "inv : <pre>";print_r($inv_line_ids);echo "</pre> end";
            // sync sale
            foreach($data->sales as $key=>$value){
                /** update status sale menjadi payment
                 * state : draft (Quotation),sent (Quotation Sent), sale (Sales Order), done (Locked), cancel (Cancelled)
                 * invoice_status : upselling (Upselling Oppotunity), invoiced (Fully Invoiced), to invoice (To Invoice), 
                 *                  no (Nothing to Invoice) */
         
                 $col_and_values=array("validity_date"=>$data->closing_date,
                        "invoice_status"=>"invoiced");   // 2 : anggota  
                $result= $this->models->execute_kw($this->database, $this->connect(), $this->password, 'sale.order', 'write',
                                array(array((int)$key), $col_and_values));
                 echo "<pre>";print_r($result);echo "</pre>";
                if(!is_array($result) and $result==true){
                    $msg_error="Berhasil sync update sale";
                    $create_sale=true;	
                }else{
                    $msg_error="Gagal sync update sale. Error : ".$result['faultString'];//$result;
                }
            }
            // sync potongan2
            $create_success=false;
            $col_and_values_je=array("name"=>"Potongan-Potongan",//$data['data']['pendapatan_kotor']['product_desc'],
                                    "date"=>$data->closing_date,
                                    "journal_id"=>3,
                                   	"partner_id"=>(int)$data->odoo_anggota_id);//journal 3 : Miscellaneous Operations
            $create_move = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                'account.move', 'create',array($col_and_values_je));
             
            
            if(!is_array($create_move) and trim($create_move)<>""){
                $create_success=true;
            }else{
                $msg_error="Gagal sync create Journal Entry (account.move)";//$result;
            }    
            if($create_success==true){ 
                $move_id=$create_move;
                foreach($data->potongan as $key2=>$value2){
                        
                        $account_ids= $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'account.account', 'search',array(array(array('code', '=',$value2->account))));
                        $account_id=(int)$account_ids[0];
                        
                        $col_and_values_jline=array("name"=>$value2->name,
                                            "account_id"=>$account_id,// account.acccount
                                            "date"=>$data->closing_date,
                                            "credit"=>$value2->credit,
                                            "debit"=>$value2->debit,
                                           	"move_id"=>(int)$move_id,
                                            "invoice_id"=>(int)$invoice_id);// account.move -> journal entry
                        echo "create move : <pre>";print_r($col_and_values_jline);echo "</pre>";
                        $je_line = $this->models->execute_kw($this->database, $this->connect(), $this->password,
                        'account.move.line', 'create',array($col_and_values_je));
                       echo "<pre>";print_r($je_line);echo "</pre>";
                        if(!is_array($je_line) and trim($je_line)<>""){
                            $create_success=true;
                        }else{
                            $msg_error="Gagal sync create Journal Entry Item (account.move.line)";//$result;
                        }    
                    
                }
            }// create _success
        }
        
    }
}
?>