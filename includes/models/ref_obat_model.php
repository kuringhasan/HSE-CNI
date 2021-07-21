<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Obat_Model extends Model {
  
	public function __construct() {
		
	}
    
    public function getObat($id,$format="object",$batas_akhir_tanggal_dipelihara="") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		  
          $batas_akhir_tanggal_dipelihara=trim($batas_akhir_tanggal_dipelihara)==""?"now()":"'".$batas_akhir_tanggal_dipelihara."'";
			$filter="c.id=".$id."";
            $data= $db->select("c.id,c.name no_eartag,koloni_name,birthplace,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') tanggal_alhir,
            datediff(current_date(),birthdate) as usia,current_date() as tgl_sekarang,posisi_eartag,tanggal_identifikasi,
            c.barcode,photo_path,created_time,created_by,ifnull(c.is_active,0) is_active,anggota_id,ifnull(is_need_verification,0) is_need_verification,
            case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,a.NAMA, type,tipe,laktasi_ke,metode_perolehan,gender,induk,
            a.ID_KELOMPOK kelompok_id,kel.name kelompok_name,mcp_id tpk_id,m.name tpk_name,
            case when ifnull(tanggal_identifikasi,'')<>'' then TIMESTAMPDIFF(YEAR,tanggal_identifikasi, $batas_akhir_tanggal_dipelihara) else null end jml_tahun_diperlihara, 
        case when ifnull(tanggal_identifikasi,'')<>'' then TIMESTAMPDIFF(MONTH,tanggal_identifikasi, $batas_akhir_tanggal_dipelihara) else null end jml_bulan_diperlihara,
        case when ifnull(tanggal_identifikasi,'')<>'' then DATEDIFF($batas_akhir_tanggal_dipelihara,tanggal_identifikasi) else null end jml_hari_diperlihara
            ","cow c
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join kelompok kel on kel.id=a.ID_KELOMPOK
            left join mcp m on m.id=kel.mcp_id
            left join kelompok_harga kh on kh.id=a.ID_KELOMPOK_HARGA
            ")->where($filter)->get(0);

					
			if(!empty($data)){
				$referensi	= $master->referensi_session();
				$rec    	= new stdClass;
                $rec->NamaTipeSapi	= $referensi['tipe_sapi'][$data->tipe];
                $jml_tahun= $data->jml_tahun_diperlihara;
                $sisa_bulan=$data->jml_bulan_diperlihara-($jml_tahun * 12);
               // $sisa_hari=$data->jml_bulan-($jml_tahun * 12);
                $jdl_bulan=$sisa_bulan==0?"":"<br />".$sisa_bulan." bln";
                $rec->LamaDiperlihara=$jml_tahun." thn".$jdl_bulan;
                $rec->tipe      = (trim($data->tipe)=="" and trim($data->type)<>"")?array_search($data->type,$referensi['tipe_sapi']):$data->tipe;
                $rec->StatusAktif		= $referensi['status_sapi'][$data->is_active];
                $rec->StatusVerifikasi	= $referensi['status_verifikasi'][$data->is_need_verification];
				$rec->MetodePerolehanNama		= $referensi['metode_perolehan'][$data->metode_perolehan];
                $rec->StatusSapiNama		= $referensi['status_sapi'][$data->status_sapi];
                $rec->Sex		= trim($data->gender)=="J"?"Jantan":(trim($data->gender)=="B"?"Betina":"");
			     $rec->TanggalIdentifikasi=$master->detailtanggal($data->tanggal_identifikasi,2);
                 $rec->TanggalLahir     =$master->detailtanggal($data->birthdate,2);
			//	$rec->url_foto= url::base()."foto/".$data->mhsFileFoto;
                if(trim($data->induk)<>""){
                    $rec->Induk=$this->getCow($data->induk);
                }
                $file_foto      = $data->photo_path;
                if(trim($data->photo_path)==""){
                    $file_foto ="cow.jpg";
                    $url_foto=url::base()."foto/sapi/".$file_foto;
                }/*else{
                    $url_foto="http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
                }*/
               
				$rec->url_foto=$url_foto;
				if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}				
			
			}else{
				return array();
			}
		}
	}
    
	public function json($category="list_obat",$query="",$array_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $List   = array();
        $filter = "";
        switch($category){
            case "list_obat":
                if(trim($query)<>""){
            	   $filter="(o.name like '%".$query."%' or o.name='".$query."' or oc.category like '%".$query."%' or oc.category = '".$query."')";
                }
                if(!empty($array_value)){
                   
                    if(isset($array_value['active']) and trim($array_value['active'])<>""){
                        $filter=trim($filter)<>""?$filter." and ifnull(o.active,'')=".$array_value['active']."":"ifnull(o.active,'')=".$array_value['active']."";
                    }
                   
                }
                $listdata= $db->select("o.id,o.name,o.category category_code,oc.category category_name,o.active","keswan_obat o
                left join keswan_obat_category oc on oc.code=o.category")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
      		            $lengkap=trim($data->category_code)==""?$data->name:$data->name." - ".$data->category_name;
            		    $List[$i]['ID']=$data->id;
            		    $List[$i]['Nama']=$data->name;
            		    $List[$i]['Lengkap']=$lengkap;
            		    $i++;
            		    next($listdata);
            		}
                }
               
          break;
          
      }
      return $List;
    } 

 
 
}
?>