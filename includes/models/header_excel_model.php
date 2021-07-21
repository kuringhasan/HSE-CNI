<?php
/**
 * @package Cek Header Format Excel
 * @subpackage Excel Model
 * 
 * @author Hasan
*/
 
defined("DCISTEM") OR die("No direct access allowed.");

class Header_Excel_Model extends Model {
	
	public function __construct() {
		
	}
	public function header_alphabet($jumlah_kolom) {
		$alphabet="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
        $ar=explode(",",$alphabet);
        $col=0;
        $no=0;
        $a=array();
        $level=0;
        while($col<$jumlah_kolom){
        	if($col % 26==0 and $no<>0){
        		$no=0;
        		$level++;
        		reset($ar);
        	}
        	if($level==0){
        		$a[$col]=$ar[$no];
        	}else{
        		$a[$col]=$ar[$level-1].$ar[$no];
        	}
        	$no++;
        	$col++;
        }
        return $a;
    }
   public function cek_salah_format_header($gol,$array_header=array()) {
      
   
      if ($gol<>"") {
            $colname=array();
            $header=$this->header_format();
			$colname=$header[$gol];
			/*if(count($colname)<>count($array_header)){
				$result['sukses']=false;
				$result['pesan']="Jumlah kolom tidak sama";
				 return $result;
			}else{*/
				
				$tmp_array=array_diff_assoc($array_header,$colname);
			//	print_r($tmp_array);
				if(count($tmp_array)==0){
					return false;
				}else{
					$result['sukses']=false;
					$result['data']=$tmp_array;
					 return $result;
				}
				
			//}
      }
    
     
     
   }
   public function header_format() {
    
		$colname['01']=array("A"=>"no","B"=>"kode_barang","C"=>"nama_barang","D"=>"noreg","E"=>"thn_perolehan","F"=>"asal_usul",
					"G"=>"jml_barang","H"=>"satuan","I"=>"harga_satuan","J"=>"harga_atribusi","K"=>"jml_harga","L"=>"kondisi",
					"M"=>"tgl_buku","N"=>"kd_SKPD","O"=>"luas","P"=>"alamat","Q"=>"nm_kecamatan","R"=>"nm_desa_kel","S"=>"status_hak",
					"T"=>"sertifikat_no","U"=>"sertifikat_tgl","V"=>"penggunaan","W"=>"ket");
		$colname['02']=array("A"=>"no","B"=>"kode_barang","C"=>"nama_barang","D"=>"noreg","E"=>"thn_perolehan","F"=>"asal_usul",
					"G"=>"jml_barang","H"=>"satuan","I"=>"harga_satuan","J"=>"harga_atribusi","K"=>"jml_harga","L"=>"kondisi",
					"M"=>"tgl_buku","N"=>"kd_SKPD","O"=>"merk","P"=>"ukuran","Q"=>"bahan","R"=>"no_rangka","S"=>"no_mesin",
					"T"=>"no_polisi","U"=>"no_bpkb","V"=>"ket");
			
		$colname['03']=array("A"=>"no","B"=>"kode_barang","C"=>"nama_barang","D"=>"noreg","E"=>"thn_perolehan","F"=>"asal_usul",
					"G"=>"jml_barang","H"=>"satuan","I"=>"harga_satuan","J"=>"harga_atribusi","K"=>"jml_harga","L"=>"kondisi",
					"M"=>"tgl_buku","N"=>"kd_SKPD","O"=>"alamat","P"=>"nm_kecamatan","Q"=>"nm_desa_kel","R"=>"luas_lantai",
					"S"=>"kontruksi_bangunan","T"=>"konstruksi_tingkat","U"=>"konstruksi_beton","V"=>"luas_tanah",
					"W"=>"status_tanah","X"=>"kode_tanah","Y"=>"ket");
		$colname['04']=array("A"=>"no","B"=>"kode_barang","C"=>"nama_barang","D"=>"noreg","E"=>"thn_perolehan",
					"F"=>"asal_usul","G"=>"jml_barang","H"=>"satuan","I"=>"harga_satuan","J"=>"harga_atribusi","K"=>"jml_harga",
					"L"=>"kondisi","M"=>"tgl_buku","N"=>"kd_SKPD","O"=>"alamat","P"=>"nm_kecamatan","Q"=>"nm_desa_kel","R"=>"ket");
		$colname['05']=array("A"=>"no","B"=>"kode_barang","C"=>"nama_barang","D"=>"noreg","E"=>"thn_perolehan",
					"F"=>"asal_usul","G"=>"jml_barang","H"=>"satuan","I"=>"harga_satuan","J"=>"harga_atribusi","K"=>"jml_harga",
					"L"=>"kondisi","M"=>"tgl_buku","N"=>"kd_SKPD","O"=>"buku_judul","P"=>"buku_spesifikasi","Q"=>"seni_asal_daerah",
					"R"=>"seni_pencipta","S"=>"seni_bahan","T"=>"hewan_jenis","U"=>"hewan_ukuran","V"=>"ket");
		$colname['06']=array("A"=>"no","B"=>"kode_barang","C"=>"nama_barang","D"=>"noreg","E"=>"thn_perolehan","F"=>"asal_usul",
					"G"=>"jml_barang","H"=>"satuan","I"=>"harga_satuan","J"=>"harga_atribusi","K"=>"jml_harga","L"=>"kondisi",
					"M"=>"tgl_buku","N"=>"kd_SKPD","O"=>"alamat","P"=>"nm_kecamatan","Q"=>"nm_desa_kel","R"=>"bangunan",
					"S"=>"konstruksi_tingkat","T"=>"konstruksi_beton","U"=>"luas_tanah","V"=>"status_tanah",
					"W"=>"kode_tanah","X"=>"ket");
      return $colname;
   } 
}
?>