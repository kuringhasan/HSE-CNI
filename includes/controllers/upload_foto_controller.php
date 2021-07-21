<?php
/**
 * @package Admin
 * @subpackage Cari Dosen Controller
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
 * @author Alan Ridwan <alanrm82@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Upload_Foto_Controller extends Web_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
	   global $dcistem;
        
		$tpl  = new View("upload_foto");
       
         $db   = $dcistem->getOption("framework/db"); 
         $NoDaftar=	$_SESSION['framework']['ref_id'];
        
        $path_fotolama="foto/".$NoDaftar.".jpg";
        //echo $profil->FileFoto;exit;
    
    if (isset($_POST['Upload']))
    	{
            
            $tmpFile=$_FILES['fileFoto']['tmp_name'];
            $typeFile=$_FILES['fileFoto']['type'];
            $fileSize=$_FILES['fileFoto']['size'];
            $fileName=$_FILES['fileFoto']['name'];
            
			//Upload foto
			if ($fileSize<=100000)
			{
                /*$currettime=date("dmYHis");
	            $nmBalik=strrev($fileName);
	            list($extFile,$nmFile)=explode(".",$nmBalik); */
				$nmFileBaru=$NoDaftar.".jpg";
                $path_fotolama="foto/".$nmFileBaru;
				if(file_exists($path_fotolama))
                    unlink($path_fotolama);    
	            if (move_uploaded_file($tmpFile,"foto/".$nmFileBaru))
	            {   
		         	$nilai=array("caFileFoto"=>$nmFileBaru);
	    			if($db->update("tbmcalonanggota",$nilai,"caNoPendaftaran='".$NoDaftar."'"))
                        $konfirmasi="Foto tos diupload";
	            }
	            else
	            	$konfirmasi="Upload gagal";
			}
			else 
				$konfirmasi="File terlalu besar";
			$tpl->Notice=$konfirmasi;
         }
	    
		$this->tpl->content = $tpl;
		$this->tpl->render();
	    
   }
  
}
 

?>