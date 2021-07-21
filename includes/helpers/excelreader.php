<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage PDF Helper
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class excelreader {
	
    
    public static function read($nama_file_dgn_path="",$array_colname="",$no_first_row_data="") {
		$path = "plugins/excel_reader/reader.php";
		 
		if(file_exists($path)) {
			include $path;
			 
			$data = new Spreadsheet_Excel_Reader();
			// Set output Encoding.
			$data->setOutputEncoding('CP1251');
		
            $data->read($nama_file_dgn_path);
	
            $no_row_header=$no_first_row_data-1;// NOMER ROW HEADER (JUDUL)
            $k=1;
            while($k <= $data->sheets[0]['numCols']) {
                //Cek kesesuaian format excel yang akan di upload
                if(trim($array_colname[$k-1])<>trim($data->sheets[0]['cells'][$no_row_header][$k])){
                   $cekformat='false';
                }
                $k++;
			}
            if(($cekformat<>'false') and (count($array_colname)==$data->sheets[0]['numCols'])){ //jika format benar
            
                for ($i = 1; $i <$no_first_row_data; $i++) {
    				for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
    					$product['header'][$i-1][$j-1]=$data->sheets[0]['cells'][$i][$j];
    				}
    			}
                
                for ($i = $no_first_row_data; $i <= $data->sheets[0]['numRows']; $i++) {
    				for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
    					$product['data'][$i-$no_first_row_data][$array_colname[$j-1]]=$data->sheets[0]['cells'][$i][$j];
    				}
    			}
                return $product;
            }else //jika format salah
            {
                 Core::fatalError("Format tidak sesuai dengan ketentuan!");
            }
           
		} else {
			Core::fatalError("Excel Reader Plugin not found!");
		}
	}
	
    public static function read2($nama_file_dgn_path="",$array_colname="",$no_first_row_data="") {
		$path = "plugins/excel_reader/reader.php";
		if(file_exists($path)) {
			include $path;
			
			$data = new Spreadsheet_Excel_Reader();
			// Set output Encoding.
			$data->setOutputEncoding('CP1251');
			
                $data->read($nama_file_dgn_path);
                   
    			$colname=$array_colname;//NAMA HEADER
                $first_row_data=$no_first_row_data;//ROR PERTAMA DATA YANG AKAN DIAMBIL
                $no_row_header=$no_first_row_data-1;// NOMER ROW HEADER (JUDUL)
                $k=1;
                
    			while($k <= $data->sheets[0]['numCols']) {
                    //Cek kesesuaian format excel yang akan di upload
                    if(trim($colname[$k-1])<>trim($data->sheets[0]['cells'][$no_row_header][$k])){
                       $cekformat='false';
                    }
                    $k++;
    			}
                if(($cekformat<>'false') and (count($colname)==$data->sheets[0]['numCols'])){ //jika format benar
    				
                    $keterangan['KodeMK']=$data->sheets[0]['cells'][2][3];
                    $keterangan['TahunAkademik']=$data->sheets[0]['cells'][3][3];
                    for ($i = $first_row_data; $i <= $data->sheets[0]['numRows']; $i++) {
    					for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
    						$product[$i-1][$colname[$j-1]]=$data->sheets[0]['cells'][$i][$j];
    					}
    				}
                    $hasil=array("data"=>$product,"Keterangan"=>$keterangan);
                    
    				return $hasil;
               }else //jika format salah
               {
                Core::fatalError("Fromat tidak sesuai dengan ketentuan!");
               }
           
			
		} else {
			Core::fatalError("Excel Reader Plugin not found!");
		}
	}
    
}
?>