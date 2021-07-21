<?php
/**
 * @package Mahasiswa
 * @subpackage Fakultas Model
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Master_Ref_Model extends Model {
	
	
	public function __construct($Kode = "") {
		
		if(trim($Kode) <> "") {
			$this->getData("FakultasKode = '".$Kode."'");
		}
	}
    public function col_excel($jumlah_kolom=80) {
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
public function tableSortir($array_sortir,$current_sortir="",$kolom="") {

		$hasil="";
      	if (trim($current_sortir)<>"" and trim($current_sortir)<>"undefined"){
      		$asc=$array_sortir[$current_sortir];
      		unset($array_sortir[$current_sortir]);
      		$asc1=array($current_sortir=>$asc);
      		$array_sortir=array_merge($asc1,$array_sortir);
      		
      	}
      	$param=array();
      	if(count($array_sortir)>0){
	      	foreach($array_sortir as $key=>$value1){
	      		$rec=new stdClass;
	      		$rec->Sort=$value1;
	      		$rec->Class=(trim($value1)=="asc")?"sort-asc":"sort-desc";
	      		$srt=(trim($value1)=="asc")?"asc":"desc";
	      		$hasil=trim($hasil)==""?$key." ".$srt:$hasil.",".$key." ".$srt;
	      		$param[$key]=$rec;
	      	}
      	}
      	if ($kolom<>""){
      		$kolom=explode(" ",$kolom);
      		$koloms=array();
      		foreach($kolom as $key=>$value){
      			$cekboxNama="";
      			$koloms[$value]['Kolom']=$value;
      			$koloms[$value]['ID']="sortir".$value;
      			
      			$cekboxNama=(!isset($param[$value]->Sort) or trim($param[$value]->Sort)=="")?"":"checked=\"checked\""; 
      			$koloms[$value]['Input']="<input  name=\"sortir[$value]\" type=\"checkbox\" value=\"".$param[$value]->Sort."\"  ".$cekboxNama." id=\"sortir".$value."\" style=\"display:none;\" >";//
      			
      			$cls=(isset($param[$value]->Class))?$param[$value]->Class:"sort-normal";
      			$koloms[$value]['Class']=$cls;
      		}
      	}
		return array("parameter"=>$param,"hasil"=>$hasil,"kolom"=>$koloms);
		
	
	}
 public function listProvinsi() {
	global $dcistem;
	$db   = $dcistem->getOption("framework/db");

	$daftarProvinsi = $db->select("PropinsiKodeTercetak, PropinsiNama","tbrPropinsi")->ORDERBY("PropinsiKodeTercetak ASC")->get();
		$Hasillist=array();
	    while($data = current($daftarProvinsi)) {
			$nilai            = new stdClass;
			
			$nilai->KodeProvinsi      = $data->PropinsiKodeTercetak;
			$nilai->NamaProvinsi     = $data->PropinsiNama;
		
			$Hasillist[] = $nilai;
			
			next($daftarProvinsi);
		}
	return $Hasillist;

	}
	
	public function cariprovinsi($kd_prov="",$prov="") {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		if ($kd_prov<>""){
			$sortir=$filter."(PropinsiKodeTercetak='".$kd_prov."')";
		}
		elseif ($prov<>""){
			$sortir=$filter."(PropinsiNama='".$prov."')";
		}
		
		$provinsi = $db->select("PropinsiKodeTercetak, PropinsiNama","tbrPropinsi")->where($sortir)->get(0);
	
	   
			$nilai            = new stdClass;
			
			$nilai->KodeProvinsi      = $provinsi->PropinsiKodeTercetak;
			$nilai->NamaProvinsi     = $provinsi->PropinsiNama;
		
			
			
			//$data=$this->appendVariable();
		
		return $nilai;

	}
	
	
	public function listkabupaten($kd_prov="",$prov="") {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		//$kode_provinsi="32";
		if (trim($kd_prov)<>""){
			$sortir=$filter."(KabupatenPropinsi='".$kd_prov."')";
		}
		elseif (trim($prov)<>""){
			$sortir1=$filter."(PropinsiNama='".$prov."')";
		     $provinsi = $db->select("PropinsiKodeTercetak, PropinsiNama","tbrPropinsi")->where($sortir1)->get(0);
			//$provinsi= $pustaka->cariprovinsi("","Jawa Barat");
			$kode_provinsi=$provinsi->PropinsiKodeTercetak; 
			$sortir=$filter."(KabupatenPropinsi='".$kode_provinsi."')";
		}
		
		
		$listkabupaten     = $db->select("KabupatenLengkap,KabupatenPropinsi,KabupatenNama,KabupatenShow","tbrKabupaten")->Where($sortir)->orderBy("KabupatenLengkap ASC")->get();
			$Hasil=array();
		    while($data = current($listkabupaten)) {
				$nilai            = new stdClass;
				
				$nilai->KodeKabupaten       = $data->KabupatenLengkap;
				$nilai->KodeProvinsi     = $data->KabupatenPropinsi;
				$nilai->NamaKabupaten       = $data->KabupatenNama;
				$Hasil[] = $nilai;
				
				next($listkabupaten);
			}
		
		return $Hasil;
	}	
	
	public function carikabupaten($kd_kab="") {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db"); 
		
		if ($kd_kab<>""){
			$sortir=" pKodeProvinsi=kKodeProvinsi and (kKodeKota='".$kd_kab."')";
		}
	
		
		$listkabupaten     = $db->select("kKodeKota,kKodeProvinsi,pNgaranProvinsi,kNgaranKota","tbrkota")->join("tbrprovinsi")->Where($sortir)->get(0);
				$nilai            = new stdClass;		
				$nilai->KodeKabupaten       = $listkabupaten->kKodeKota;
				$nilai->KodeProvinsi     = $listkabupaten->kKodeProvinsi;
                $nilai->NamaProvinsi     = $listkabupaten->pNgaranProvinsi;
				$nilai->NamaKabupaten       = $listkabupaten->kNgaranKota;
		//print_r($listkabupaten);
		return $nilai;
	}		
	public function listBulan() {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$listBulan     = $db->select("BulanKode,BulanNama,BulanNamaSingkatan","tRefBulan")->orderBy("BulanKode ASC")->get();
			$Hasil=array();
		    while($data = current($listBulan)) {
				$nilai            = new stdClass;
				
				$nilai->KodeBulan      = $data->BulanKode;
				$nilai->NamaBulan     = $data->BulanNama;
				$nilai->Singkatan       = $data->BulanNamaSingkatan;
				$Hasil[] = $nilai;
				
				next($listBulan);
			}
		
		$tpl->Hasil=$Hasil;
		return $tpl->Hasil;
		
	
	}
	
	public function cariBulan($kdBln="") {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$sortir=$filter."(BulanKode='".$kdBln."')";
		$cariBulan = $db->select("BulanKode,BulanNama,BulanNamaSingkatan","tRefBulan")->where($sortir)->get(0);
 	
				$KodeBulan      = $cariBulan->BulanKode;
				$NamaBulan     = $cariBulan->BulanNama;
				$Singkatan       = $cariBulan->BulanNamaSingkatan;
	
		return array($KodeBulan,$NamaBulan,$Singkatan);
	
	}
	public function TanggalPanjang($Tanggal="YYYY-MM-DD") {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
 		list($Tahun,$Bulan,$Tanggal)=explode("-",$Tanggal);
		list($KodeBln,$NamaBln,$Singkatan)=$this->cariBulan($Bulan);
		$TanggalPanjang	=$Tanggal." ".$NamaBln." ".$Tahun;
	
		return $TanggalPanjang;
	
	}
    
public function formattanggal($value="",$bentukasal="",$bentukbaru="") {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
       
		/** Bentuk Tanggal Yang Diinginkan *
         * 1 : YYYY-MM-DD contoh : 2012-04-23
         * 2 : DD/MM/YYYY  contoh : 27/07/2012
         * 3 : DD NAMA_BULAN YYYY
         * */
         $tanggal="";
		 $bulan="";
		 $tahun="";
		 list($tgl,$jam) = explode(" ",$value);
		 
         if($bentukasal=='2')
         { 
         	
            list($tanggal,$bulan,$tahun)=explode("/",$tgl);
		
         }	
		 
         if($bentukasal=='1')
         {
            list($tahun,$bulan,$tanggal)=explode("-",$tgl);
         }
        
         $hasil=$bentukasal;
         switch($bentukbaru){
          case '1':
             $hasil=$tahun."-".$bulan."-".$tanggal;
             break;
          case '2':
             $hasil=$tanggal."/".$bulan."/".$tahun;
             break;
          case '3':
             $bulan=(int)$bulan;
             $hasil=$tanggal." ".$this->namabulanIN($bulan)." ".$tahun;
             break;
         }
	
		return trim($jam)==""?$hasil:$hasil." ".$jam;
	
	}
    public function namabulanIN($bulan,$singkatan=false){
        $namabulan="";
        switch ((int)$bulan)
        {
            case 1:
                $namabulan=$singkatan==false?"Januari":"Jan";
                break;
            case 2:
                $namabulan=$singkatan==false?"Februari":"Feb";
                break;
            case 3:
                $namabulan=$singkatan==false?"Maret":"Mar";
                break;
            case 4:
                $namabulan=$singkatan==false?"April":"Apr";
                break;
            case 5:
                $namabulan=$singkatan==false?"Mei":"Mei";
                break;
            case 6:
                $namabulan=$singkatan==false?"Juni":"Jun";
                break;
            case 7:
                $namabulan=$singkatan==false?"Juli":"Jul";
                break;
            case 8:
                $namabulan=$singkatan==false?"Agustus":"Agu";
                break;
            case 9:
                $namabulan=$singkatan==false?"September":"Sep";
                break;
            case 10:
                $namabulan=$singkatan==false?"Oktober":"Okt";
                break;
            case 11:
                $namabulan=$singkatan==false?"November":"Nov";
                break;
            case 12:
                $namabulan=$singkatan==false?"Desember":"Des";
                break;
           
        }
        return $namabulan;
    }
	public function listAgama() {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$daftarAgama = $db->select("AgamaKode,AgamaNama","tRefAgama")->get();
			$Hasil=array();
		    while($data = current($daftarAgama)) {
				$nilai            = new stdClass;	
				$nilai->KodeAgama      = $data->AgamaKode;
				$nilai->NamaAgama       = $data->AgamaNama;
				$Hasil[] = $nilai;	
				next($daftarAgama);		}
		return $Hasil;
	}
	public function cariAgama($kode="") {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$sortir="AgamaKode='".$kode."'";
		$Agama = $db->select("AgamaKode,AgamaNama","tRefAgama")->where($sortir)->get();
				$nilai            = new stdClass;
				
				$nilai->KodeAgama      = $Agama->AgamaKode;
				$nilai->NamaAgama       = $Agama->AgamaNama;
			
		return $nilai;
	
	
	}
	public function listGolDarah() {
    
	global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	
	$daftarGolDarah = $db->select("GolDarah","tRefGolDarah")->get();
		$Hasil=array();
	    while($data = current($daftarGolDarah)) {
			$nilai            = new stdClass;
			$nilai->GolDarah      = $data->GolDarah;
			$Hasil[] = $nilai;
			next($daftarGolDarah);
		}
	return $Hasil;
	}
	
    public function listarraybulan() {
    $bulan=array( "01"=>"Januari",
                "02"=>"Februari",
                "03"=>"Maret",
                "04"=>"April",
                "05"=>"Mei",
                "06"=>"Juni",
                "07"=>"Juli",
                "08"=>"Agustus",
                "09"=>"September",
                "10"=>"Oktober",
                "11"=>"Nopember",
                "12"=>"Desember"
                );
	return $bulan;
	}
    public function listarraytanggal() {
    $tanggal=array( "01"=>"1","02"=>"2","03"=>"3",
                "04"=>"4","05"=>"5","06"=>"6",
                "07"=>"7","08"=>"8","09"=>"9",
                "10"=>"10","11"=>"11","12"=>"12",
                "13"=>"13","14"=>"14","15"=>"15",
                "16"=>"16","17"=>"17","18"=>"18",
                "19"=>"19","20"=>"20","21"=>"21",
                "22"=>"22","23"=>"23","24"=>"24",
                "25"=>"25","26"=>"26","27"=>"27",
                "28"=>"28","29"=>"29","30"=>"30","31"=>"31"
                );
	return $tanggal;
	}	
    public function listarraytahun($tahunawal,$tahunakhir) {
        $tahun=array();
        for ($i=$tahunawal;$i<=$tahunakhir;$i++)
        {
             $tahun[$i]=$i;
        }
       
	return $tahun;
	}	
    
     public function cari4arrayhari($int_day,$lang="",$mulai_hari="senin") {
     
     	$mulai_hari=strtolower($mulai_hari);
     	$tahun=array();
        if ($mulai_hari=="senin"){
	        switch($lang){
	     		case "sunda":
	 				$tahun=array(0=>"Senen",
	                    1=>"Salasa",2=>"Rebo",
	                    3=>"Kemis",4=>"Jumaah",5=>"Saptu",6=>"Minggu");
	     		break;
	     		default:
		     		$tahun=array(0=>"Senin",
		                    1=>"Selasa",2=>"Rabu",
		                    3=>"Kamis",4=>"Jum'at",5=>"Sabtu",6=>"Minggu");
	     		break;
     		}
	    }
	    if ($mulai_hari=="minggu"){
	        switch($lang){
	     		case "sunda":
	 				$tahun=array(0=>"Minggu",1=>"Senen",
	                    2=>"Salasa",3=>"Rebo",
	                    4=>"Kemis",5=>"Jumaah",6=>"Saptu");
	     		break;
	     		default:
		     		$tahun=array(0=>"Minggu",1=>"Senin",
		                    2=>"Selasa",3=>"Rabu",
		                    4=>"Kamis",5=>"Jum'at",6=>"Sabtu");
	     		break;
     		}
	    }
	
	return $tahun[$int_day];
	}	
    public function konfirmlogin() {
        $konfirm=array();
        $konfirm[1]="Username harus diisi!";
        $konfirm[2]="Kombinasi username dan password salah!";
	return $konfirm;
	}	
    
	public function scurevaluetable($var,$type="string",$convert_number=true) {
    
          switch($type){
            case "string":
            	$var=str_ireplace("'","''",$var);
                $var=trim($var)==""?"null":"'".$var."'";
                break;
            case "number":
            	if($convert_number==true){
            		$var=trim($var)<>""?$this->konversi2angka($var):"";
            	}
                $var=trim($var)==""?"null":$var;                
                break;
        }
		return $var;
    }
	public  function html_sanitasi($string){
		$str = mb_convert_encoding ($string, "UTF-8");
	 	$hsl= htmlentities( $str, ENT_COMPAT, 'UTF-8');
		// var_dump($hsl);
	 	$hasil= html_entity_decode($hsl,ENT_COMPAT, 'UTF-8');
		return $hasil;
	}
	public function detailtanggal($var,$current_format=1) {
	 		/** 
     * Input Format :
     * 1 : Bentuk dd/mm/yyyy misal : 27/03/2013 00:00:00
     * 2 : Bentuk yyyy-mm-dd misal : 2013-03-27 00:00:00
     * */
     $hasil = array();
     if(trim($var)<>""){
         date_default_timezone_set("Asia/Jakarta");
         $var=strlen(trim($var))==10?$var." 00:00:00":$var;
         $timestamp="";
         $formatlain="";
         $NoHari=9;
         $NoBulan="";
         $date="";
         if($current_format==1){
         	list($tanggal,$jam)=explode(" ",$var);
         	list($tgl,$bln,$tahun)=explode("/",$tanggal);
         	list($haours,$menit,$detik)=explode(":",$jam);
         	$timestamp=mktime($haours,$menit,$detik,$bln,$tgl,$tahun);
         	$formatlain =date("Y-m-d H:i:s",$timestamp);
            $date       = date("Y-m-d",$timestamp);
         	$NoHari=date("N",$timestamp);
         	$NoBulan=date("n",$timestamp);
         }
         if($current_format==2){
         	$timestamp=strtotime($var);
         
         	$formatlain    = date("d/m/Y H:i:s",$timestamp);
            $date          = date("Y-m-d",$timestamp);
         	$NoHari=date("N",$timestamp);
         	$NoBulan=date("n",$timestamp);
        }	
        $hari	=$NoHari<>9?$this->namahari($NoHari):"";
        $bulan	=$NoBulan<>""?$this->namabulanIN($NoBulan):"";
        $bulan_singkatan=$NoBulan<>""?$this->namabulanIN($NoBulan,true):"";
        $hasil['Asli']=$var;
        $hasil['date']=$date;
        $hasil['FormatLain']=$formatlain;
        $hasil['Tanggal']=$timestamp<>""?date("d/m/Y",$timestamp):"";
        $tgl			=trim($timestamp)<>""?date("d",$timestamp):"";
        $hasil['Tgl']	=$tgl;
        $thn				=trim($timestamp)<>""?date("Y",$timestamp):"";
        $hasil['Tahun']		=$thn;
        $hasil['BulanAngka']	=$NoBulan;
        $hasil['IndoBulan']	=$bulan;
        $hasil['EngBulan']=$timestamp<>""?date("F",$timestamp):"";
        $hasil['IndoHari']=$hari;
        $hasil['EngHari']=$timestamp<>""?date("l",$timestamp):"";
        $hasil['Lengkap']	=$tgl." ".$bulan." ".$thn;
        $hasil['LengkapSingkatan']	=$tgl." ".$bulan_singkatan." ".$thn;
        
        $hasil['Jam']=$timestamp<>""?date("H:i:s",$timestamp):"";
     }
    return $hasil;     
	 }
public function namahari($no_hari,$mulai_hari="senin")
    {
        $nmhari="";
        $mulai_hari=strtolower($mulai_hari);
        if ($mulai_hari=="senin"){
	        switch ((int)$no_hari)
	        {
	            case 1:
	                $nmhari="Senin";
	                break;
	            case 2:
	                $nmhari="Selasa";
	                break;
	            case 3:
	                $nmhari="Rabu";
	                break;
	            case 4:
	                $nmhari="Kamis";
	                break;
	            case 5:
	                $nmhari="Jum'at";
	                break;
	            case 6:
	                $nmhari="Sabtu";
	                break;
	            case 7:
	                $nmhari="Minggu";
	                break;
	             
	        }
	    }
	    if ($mulai_hari=="minggu"){
	        switch ((int)$no_hari)
	        {
	           
	            case 1:
	                $nmhari="Minggu";
	            break;
	            case 2:
	                $nmhari="Senin";
	                break;
	            case 3:
	                $nmhari="Selasa";
	                break;
	            case 4:
	                $nmhari="Rabu";
	                break;
	            case 5:
	                $nmhari="Kamis";
	                break;
	            case 6:
	                $nmhari="Jum'at";
	                break;
	            case 7:
	                $nmhari="Sabtu";
	                break;
	            
	             
	        }
	    }
        
        return $nmhari;
    }
public function  Terbilang($x)
 {
 	
 	$terbilang="";
   	if (trim($x)<>""){
   		$x=(float)$x;
	  	$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	   
		switch($x){
		  	case $x < 12:
		  	  	$terbilang=$abil[$x];
		  	break;
		  	case $x < 20:
		  	  	$terbilang=$abil[$x-10] . " belas";
		  	break;
		  	case $x < 100:
		  		$hasil_bagi = (int)($x / 10);
		        $hasil_mod = $x % 10;
		        $terbilang= trim(sprintf('%s puluh %s', $abil[$hasil_bagi], $abil[$hasil_mod]));
		  	
		  	  //	$terbilang=$this->Terbilang($x / 10) . "puluh " . $this->Terbilang($x % 10);
		  	break;
		  	case $x < 200:
		  	  	//$terbilang="seratus " . $this->Terbilang($x - 100)." ";
		  	  	$terbilang= sprintf('seratus %s', $this->Terbilang($x - 100));
		  	break;
		  	case $x < 1000:
		  		$hasil_bagi = (int)($x / 100);
		        $hasil_mod = $x % 100;
		        $terbilang= trim(sprintf('%s ratus %s', $abil[$hasil_bagi], $this->Terbilang($hasil_mod)));
		  	  //	$terbilang=$this->Terbilang($x / 100) . "ratus " . $this->Terbilang($x % 100);
		  	break;
		  	case $x < 2000:
		  		$terbilang= trim(sprintf('seribu %s', $this->Terbilang($x - 1000)));
		  	  	//$terbilang="seribu " . $this->Terbilang($x - 1000)." ";
		  	break;
		  	case $x < 1000000:
		  		$hasil_bagi = (int)($x / 1000); // karena hasilnya bisa ratusan jadi langsung digunakan rekursif
		        $hasil_mod = $x % 1000;
		        $terbilang= sprintf('%s ribu %s', $this->Terbilang($hasil_bagi), $this->Terbilang($hasil_mod));
		  	  	//$terbilang=$this->Terbilang($x / 1000) . "ribu " . $this->Terbilang($x % 1000);
		  	break;
		  	case $x < 1000000000:
		  		// hasil bagi bisa satuan, belasan, ratusan jadi langsung kita gunakan rekursif
		        $hasil_bagi = (int)($x / 1000000);
		        $hasil_mod = $x % 1000000;
		        $terbilang= trim(sprintf('%s juta %s', $this->Terbilang($hasil_bagi), $this->Terbilang($hasil_mod)));
		  	  	//$terbilang=$this->Terbilang($x / 1000000) . "juta " . $this->Terbilang($x % 1000000);
		  	break;
		  	case $x < 1000000000000:
		  		// bilangan 'milyaran'
		        $hasil_bagi = (int)($x / 1000000000);
		        $hasil_mod = fmod($x, 1000000000);
		        $terbilang= trim(sprintf('%s milyar %s', $this->Terbilang($hasil_bagi), $this->Terbilang($hasil_mod)));
		  	  	//$terbilang=$this->Terbilang($x / 1000000000) . "miliar " . $this->Terbilang($x % 1000000000);
		  	break;
		  	case $x < 1000000000000000:
		        // bilangan 'triliun'                           
				  $hasil_bagi = $x / 1000000000000;                           
				  $hasil_mod = fmod($x, 1000000000000);                           
				  $terbilang= trim(sprintf('%s triliun %s', $this->Terbilang($hasil_bagi), $this->Terbilang($hasil_mod))); 
		  	break;
		  /*	case $x >= 1000000000000000000:
		                                  
				  $terbilang= "Diluar kapasitas..!"; 
		  	break;*/
		}
	    
     }
    return $terbilang;
  
  }	

   public function  datediff($tgl_awal, $tgl_akhir){
		$tgl_awal = (is_string($tgl_awal)? strtotime($tgl_awal):$tgl_awal);
		$tgl_akhir = (is_string($tgl_akhir)? strtotime($tgl_akhir):$tgl_akhir);
		$diff_secs = abs($tgl_akhir-$tgl_awal);
		$base_year = min(date("Y", $tgl_awal), date("Y", $tgl_akhir));
		$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
		$hasil['Tahun']=date('Y', $diff)-$base_year;
		$hasil['BulanTotal']=(date("Y", $diff)-$base_year)* 12 + date("n", $diff)-1;
		$hasil['Bulan']		=date("n", $diff)-1;
		
		$hasil['HariTotal']=floor($diff_secs/(3600*24));
		$hasil['Hari']=date("j", $diff)-1;
		
		$hasil['JamTotal']=floor($diff_secs/3600);
		$hasil['Jam']= date("G", $diff);
		$hasil['MenitTotal']=floor($diff_secs / 60);
		$hasil['Menit']=(int) date("i", $diff);
		$hasil['DetikTotal']=$diff_secs;
		$hasil['Detik']=(int) date("s", $diff) ;
	
	return $hasil;
	}
	
	public function  split_string($var,$char_count_splitted=2,$pemisah="array"){
		
		$jml_char=strlen($var)-$char_count_splitted;
		$hasil=array();
		$i=0;
		while($i<=$jml_char){
			$hasil[]=substr($var,$i,$char_count_splitted);
			$i=$i+$char_count_splitted;
		}
		$hsl="";
		if(trim($pemisah)<>"array"){
			$hsl=implode(".",$hasil);
		}
		
	return trim($hsl)<>""?$hsl:$hasil;
	}
	public function  konversi_tanggal_otomatis($tanggal_and_time){
		/** ===========================
		 * Support input Format : dd/mm/yyyy atau dd-mm-yyyy
		 * Output format : yyyy-mm-dd
		 * 
		 * ============================ */
		$format_baru="";
		if(trim($tanggal_and_time)<>"")
		{
            $tt=explode(" ",$tanggal_and_time);
            $tanggal=$tt[0];
            $waktu=isset($tt[1])?$tt[1]:"";
			if(substr_count(trim($tanggal),"/")==2){
				$tg=explode("/",$tanggal);
				$format_baru=$tg[2]."-".$tg[1]."-".$tg[0];
                $format_baru=trim($waktu)<>""?$format_baru." ".$waktu:$format_baru;
			}
			if(substr_count(trim($tanggal),"-")==2){
				$tg=explode("-",$tanggal);
				$format_baru=$tg[2]."-".$tg[1]."-".$tg[0];
                $format_baru=trim($waktu)<>""?$format_baru." ".$waktu:$format_baru;
			}
		}
		
	return $format_baru;
	}
	public function  nama_dan_gelar($gelar_depan="",$nama,$gelar_belakang=""){
		$nl=$nama;
		if(trim($gelar_depan)<>"")
		{
			$nl=$gelar_depan." ".$nl;
		}
		if(trim($gelar_belakang)<>"")
		{
			$nl=$nl.", ".$gelar_belakang;
		}
		
		return $nl;
	}
	function parseToXML($htmlStr)
	{
		$xmlStr=str_replace('<','&lt;',$htmlStr);
		$xmlStr=str_replace('>','&gt;',$xmlStr);
		$xmlStr=str_replace('"','&quot;',$xmlStr);
		$xmlStr=str_replace("'",'&#39;',$xmlStr);
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		return $xmlStr;
	} 
public function konversi2angka($nilai,$input_tanda_decimal=","){
		$hasil="";
		
		if($nilai<>""){
		
			$str=(string)$nilai;
			$posisi_koma	=strpos($str,",");
			$posisi_titik	=strpos($str,".");
			if($posisi_koma<>false && $posisi_titik<>false){
				if($input_tanda_decimal==','){
					$hasil = str_replace(".","",$str);
					$hasil = str_replace(",",".",$hasil);
				}else{
					$hasil = str_replace(",","",$str);// str.split(',').join('');
				}
			}elseif($posisi_koma>=0 and $posisi_titik==false){
				// bila 5000,05 ubah jadi 5000.05
			
				if($input_tanda_decimal==','){
					$hasil = str_replace(",",".",$str);//str.split(',').join('.');
				}else{
					$hasil = str_replace(",","",$str);
				}
			}elseif($posisi_koma==false and $posisi_titik>=0){
				// bila : 50.000 ubah jadi 50000
				
				if($input_tanda_decimal==','){
					$hasil = 	$hasil = str_replace(".","",$str);
				}else{
					$hasil = $str;
				}
			}else{
				$hasil = $str;
			}
		}else{
			$hasil='0';
		}
		return $hasil;
	}
	public function paginator($total_data,$current_page,$jumlah_tampil){
		
			$total_pages = ceil($total_data/$jumlah_tampil);
			$awal_data   = $current_page==1?1:($jumlah_tampil*($current_page-1))+1;
			$ahir_data   = $current_page==1?$jumlah_tampil:$current_page*$jumlah_tampil;
			$prev_page	= ($current_page-1)<1?1:($current_page-1);
			$next_page	= ($current_page+1)>$total_pages?$total_pages:($current_page+1);
			$rentang="";
			if($total_data<$jumlah_tampil){
				$ahir_data=$total_data;
			}
			if($total_data==0){
				$rentang=0;
			}else{
				$rentang=$awal_data.'-'.$ahir_data;
			}
			$disabled_prev=($current_page==1 or $total_data==0)?" disabled=\"disabled\" ":"";
			$disabled_next=($current_page==$total_pages or $total_data==0)?" disabled=\"disabled\" ":"";
		
			$html= $rentang.'/'.$total_data.'&nbsp;<div class="btn-group"><button type="button" id="btn-prev-nav" onclick="document.getElementById(\'current_page\').value=\''.$prev_page.'\';klikloaddata();" '.$disabled_prev.' class="btn btn-primary btn-xs"><i class="fa fa-chevron-left"></i></button><button type="button" id="btn-next-nav" onclick="document.getElementById(\'current_page\').value=\''.$next_page.'\';klikloaddata();" '.$disabled_next.' class="btn btn-primary btn-xs"><i class="fa fa-chevron-right"></i></button></div>';
	
		$hasil['html']=$html;
		$hasil['current_page']=$current_page;
		return $hasil;
	  
}
	function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
  
	function url_previous(){
		$url_current= curPageURL();
		if(!isset($_SESSION['url_prev'])){
			$_SESSION['url_prev']="";
			$_SESSION['url_current']=$url_current;
		}else{
			//$_SESSION['url_prev']=$_SESSION['url_current'];
			if($_SESSION['url_current']<>$url_current){
				$_SESSION['url_prev']	=$_SESSION['url_current'];
				$_SESSION['url_current']=$url_current;
			}
		}
	}
	 function referensi_session($ref="all"){
    	global $dcistem;
        $db   = $dcistem->getOption("framework/db");
    	
	
			$lists=array();
			//$referensi['payment_method']		=array("trf"=>"Transfer","cas"=>"Cash","crc"=>"Credit Cards");
           
            switch($ref){
                case "all":
               	    if(!isset($_SESSION["referensi"])){
                        /*$list_sa= $db->select("status_id,status_name","anggota_status")->get();
            	        while ($sa = current($list_sa)) {
            	       
            	            $lists["status_keanggotaan"][$sa->status_id]=$sa->status_name;
            	            next($list_sa);
            	        }
                        $list_mp= $db->select("metode_id,metode_nama","keswan_metode_perolehan")->get();
            	        while ($mp = current($list_mp)) {
            	       
            	            $lists["metode_perolehan"][$mp->metode_id]=$mp->metode_nama;
            	            next($list_mp);
            	        }
                      */
                        $list_agama= $db->select("agamaKode,agamaNama","tbragama")->get();
            	        while ($ag = current($list_agama)) {
            	       
            	            $lists["agama"][$ag->agamaKode]=$ag->agamaNama;
            	            next($list_agama);
            	        }
                       
                        $lists["status_verifikasi"]['1']="Unverified";
            			$lists["status_verifikasi"]['0']="Verified";
                        $lists["status_sapi"]['1']="Aktif";
            			$lists["status_sapi"]['0']="Non-Aktif";
                        $lists["sex"]['L']="Laki-Laki";
            			$lists["sex"]['P']="Perempuan";
                        $lists["state_pit"]['prospect']="Prospect";
            			$lists["state_pit"]['active']="Active";
                        $lists["state_pit"]['mined_out']="Mined out";
            	       /* $list_idcard= $db->select("TPJenisKode,TPJenisNama","tbrTandaPengenalJenis ORDER BY TPJenisPrioritas asc")->get();
            	        while ($ic = current($list_idcard)) {
            	       
            	            $lists["idcard"][$ic->TPJenisKode]=$ic->TPJenisNama;
            	            next($list_idcard);
            	        }
            	        $lists["status_bayar"][0]="Belum Bayar";
            			$lists["status_bayar"][1]="Lunas";
            			$lists["status_bayar"][2]="Belum Lunas";
            			
            			$list_job= $db->select("pekerjaanKode,pekerjaanNama","tbrPekerjaan 
            			ORDER BY pekerjaanUrut asc")->get();
            	        while ($job = current($list_job)) {
            	       
            	            $lists["pekerjaan"][$job->pekerjaanKode]=$job->pekerjaanNama;
            	            next($list_job);
            	        }
            	        $list_pendidikan= $db->select("pendidikanKode,pendidikanNama","tbrPendidikan 
            			ORDER BY pendidikanUrut asc")->get();
            	        while ($ddk = current($list_pendidikan)) {
            	       
            	            $lists["pendidikan"][$ddk->pendidikanKode]=$ddk->pendidikanNama;
            	            next($list_pendidikan);
            	        }*/
                    $_SESSION["referensi"]		=$lists;
                 }//end if(!isset($_SESSION["referensi"])){
                 break;
                 case "kasus_penyakit":
                   
                    if(!isset($_SESSION["referensi"]['kasus_penyakit'])){
                        $list_kasus_qry= $db->select("KasusID,KasusPenyakit","keswan_kasus_penyakit")
                        ->where("ifnull(KasusInactive,0)=0")->orderBy("KasusID asc")->lim();
            	        while($kasus= $db->fetchObject($list_kasus_qry))
            	        {
            	            $lists["kasus_penyakit"][$kasus->KasusID]=$kasus->KasusPenyakit;
            	           
            	        }
                    }
                    $_SESSION["referensi"]= array_merge((array) $_SESSION["referensi"], (array) $lists);
               break;
               case "sub_sistem":
                   
                    if(!isset($_SESSION["referensi"]['sub_sistem'])){
                        $list_ss_qry= $db->select("SubID,SubNama","keswan_kasus_subsistem")->orderBy("SubID asc")->lim();
            	        while($sub= $db->fetchObject($list_ss_qry))
            	        {
            	            $lists["sub_sistem"][$sub->SubID]=$sub->SubNama;
            	           
            	        }
                    }
                    $_SESSION["referensi"]= array_merge((array) $_SESSION["referensi"], (array) $lists);
               break;
               case "barang":
                   
                    if(!isset($_SESSION["referensi"]['barang'])){
                        $list_brg_qry= $db->select("id,name","barang")->orderBy("id asc")->lim();
            	        while($brg= $db->fetchObject($list_brg_qry))
            	        {
            	            $lists["barang"][$brg->id]=$brg->name;
            	           
            	        }
                    }
                    $_SESSION["referensi"]= array_merge((array) $_SESSION["referensi"], (array) $lists);
               break;
               case "tpk":
                   
                    if(!isset($_SESSION["referensi"]['tpk'])){
                        $list_mcp_qry= $db->select("id,name","mcp")->orderBy("id asc")->lim();
            	        while($mcp= $db->fetchObject($list_mcp_qry))
            	        {
            	            $lists["tpk"][$mcp->id]=$mcp->name;
            	           
            	        }
                    }
                    $_SESSION["referensi"]= array_merge((array) $_SESSION["referensi"], (array) $lists);
               break;
            }//end switch
            
		return $_SESSION["referensi"];
		
	}
	function settings(){
    	global $dcistem;
        $db   = $dcistem->getOption("framework/db");
    		$lists=array();
			//$referensi['payment_method']		=array("trf"=>"Transfer","cas"=>"Cash","crc"=>"Credit Cards");
	         $list_data= $db->select("settingID,settingKey,settingValue","tbrsettings")->get();
	        while ($la = current($list_data)) {
	       
	            $lists[$la->settingKey]=$la->settingValue;
	            next($list_data);
	        }
	
		return $lists;
		
	}
public function kopsurat($format_cetak="html"){
	global $dcistem;
	$setting=$this->settings();
	$path_aplikasi=dirname($_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['SCRIPT_NAME']));
	
	//echo $path_aplikasi;//$dcistem->getOption("framework/value/theme_path")."images/logo_bw.jpg";
	//echo $_SERVER["HTTP_HOST"];
//	echo "<pre>";print_r($setting);echo "</pre>";exit;
	//echo $path_aplikasi."<br />";
	?>
	<style>
	<?php
		
	$path_file="";//$this->curPageURL();
	if ($format_cetak=="pdf")
	{
	//	$path_file= dirname($path_aplikasi).$dcistem->getOption("framework/value/theme_path")."images/logo_bw.jpg";
		$path_file = $setting['logo_kop_surat'];//$dcistem->getOption("framework/value/theme_path")."images/logo_bw.jpg";
	?>
	.garis {
		clear:both;
		width:560px;
		margin:8px 0px 0px 0px;
	
		padding-bottom: 3px;
	}
	<?php
	
	}
	if ($format_cetak=="html")
	{
		
		$path_file = $setting['logo_kop_surat'];//$dcistem->getOption("framework/value/theme_path")."images/logo_bw.jpg";
	?>
		.garis {
			width:560px;
			margin-top:6px;
			padding-bottom: 3px;
		}
	<?php
	}
	
	?>
	</style>
<?php //echo $path_file;exit;?> 
	<table border="0"  cellpadding="0" cellspacing="0"  class="tabel-utama" width="100%" style="border-bottom:3px double #000;">
	  <tr  style="">
	  <td  style="text-align:center; width:85px;" >
	   <img src="<?php echo $path_file;?>" style="height:70px; " />
	  </td>
	<td style="text-align:center;   padding:3px 0px 0px 10px;" >
	<table border="0" ><tr>	 <td class="garis"  style="text-align:center;">
		<span style="font-size:18px; line-height:20px;">SEKOLAH TINGGI ILMU EKONOMI PARIWISATA<br />
			<strong style="font-size:24px;line-height:26px">STIEPAR YAPARI-AKTRIPA</strong></span><br />
		    <span style="font-size:1em;line-height:15px; display:block; border:0px solid $ccc; ">
			Jl. Prof. Dr. Ir. Sutami No. 81-83 Bandung 40152 <br />
			Telp. (022) 87783179/(022) 2011027 Fax (022) 2004423 www.stiepar.ac.id 
		    </span>
		   </td>
	</tr></table>
	    </td>
	  </tr>
	  </table>
  <?php
  //$content = ob_get_clean();
 // return $content;
  
	}
	function get_token($token_name,$panjang_karakter=15){
		
		$_SESSION[$token_name]=text::kodeAcak($panjang_karakter);
		return $_SESSION[$token_name];
	}
    public function  is_connected($domain="www.google.com",$port=80)
    {
        $connected = @fsockopen($domain, $port); 
        //website, port  (try 80 or 443)
        $is_conn=false;
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    
    }
    function list_zona_waktu(){
        $timezones = array(
            'Pacific/Midway'       => "(GMT-11:00) Midway Island",
            'US/Samoa'             => "(GMT-11:00) Samoa",
            'US/Hawaii'            => "(GMT-10:00) Hawaii",
            'US/Alaska'            => "(GMT-09:00) Alaska",
            'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
            'America/Tijuana'      => "(GMT-08:00) Tijuana",
            'US/Arizona'           => "(GMT-07:00) Arizona",
            'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
            'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
            'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
            'America/Mexico_City'  => "(GMT-06:00) Mexico City",
            'America/Monterrey'    => "(GMT-06:00) Monterrey",
            'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
            'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
            'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
            'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
            'America/Bogota'       => "(GMT-05:00) Bogota",
            'America/Lima'         => "(GMT-05:00) Lima",
            'America/Caracas'      => "(GMT-04:30) Caracas",
            'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
            'America/La_Paz'       => "(GMT-04:00) La Paz",
            'America/Santiago'     => "(GMT-04:00) Santiago",
            'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
            'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
            'Greenland'            => "(GMT-03:00) Greenland",
            'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
            'Atlantic/Azores'      => "(GMT-01:00) Azores",
            'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
            'Africa/Casablanca'    => "(GMT) Casablanca",
            'Europe/Dublin'        => "(GMT) Dublin",
            'Europe/Lisbon'        => "(GMT) Lisbon",
            'Europe/London'        => "(GMT) London",
            'Africa/Monrovia'      => "(GMT) Monrovia",
            'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
            'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
            'Europe/Berlin'        => "(GMT+01:00) Berlin",
            'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
            'Europe/Brussels'      => "(GMT+01:00) Brussels",
            'Europe/Budapest'      => "(GMT+01:00) Budapest",
            'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
            'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
            'Europe/Madrid'        => "(GMT+01:00) Madrid",
            'Europe/Paris'         => "(GMT+01:00) Paris",
            'Europe/Prague'        => "(GMT+01:00) Prague",
            'Europe/Rome'          => "(GMT+01:00) Rome",
            'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
            'Europe/Skopje'        => "(GMT+01:00) Skopje",
            'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
            'Europe/Vienna'        => "(GMT+01:00) Vienna",
            'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
            'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
            'Europe/Athens'        => "(GMT+02:00) Athens",
            'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
            'Africa/Cairo'         => "(GMT+02:00) Cairo",
            'Africa/Harare'        => "(GMT+02:00) Harare",
            'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
            'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
            'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
            'Europe/Kiev'          => "(GMT+02:00) Kyiv",
            'Europe/Minsk'         => "(GMT+02:00) Minsk",
            'Europe/Riga'          => "(GMT+02:00) Riga",
            'Europe/Sofia'         => "(GMT+02:00) Sofia",
            'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
            'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
            'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
            'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
            'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
            'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
            'Europe/Moscow'        => "(GMT+03:00) Moscow",
            'Asia/Tehran'          => "(GMT+03:30) Tehran",
            'Asia/Baku'            => "(GMT+04:00) Baku",
            'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
            'Asia/Muscat'          => "(GMT+04:00) Muscat",
            'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
            'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
            'Asia/Kabul'           => "(GMT+04:30) Kabul",
            'Asia/Karachi'         => "(GMT+05:00) Karachi",
            'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
            'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
            'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
            'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
            'Asia/Almaty'          => "(GMT+06:00) Almaty",
            'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
            'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
            'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
            'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
            'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
            'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
            'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
            'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
            'Australia/Perth'      => "(GMT+08:00) Perth",
            'Asia/Singapore'       => "(GMT+08:00) Singapore",
            'Asia/Taipei'          => "(GMT+08:00) Taipei",
            'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
            'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
            'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
            'Asia/Seoul'           => "(GMT+09:00) Seoul",
            'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
            'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
            'Australia/Darwin'     => "(GMT+09:30) Darwin",
            'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
            'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
            'Australia/Canberra'   => "(GMT+10:00) Canberra",
            'Pacific/Guam'         => "(GMT+10:00) Guam",
            'Australia/Hobart'     => "(GMT+10:00) Hobart",
            'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
            'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
            'Australia/Sydney'     => "(GMT+10:00) Sydney",
            'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
            'Asia/Magadan'         => "(GMT+12:00) Magadan",
            'Pacific/Auckland'     => "(GMT+12:00) Auckland",
            'Pacific/Fiji'         => "(GMT+12:00) Fiji",
        );
        return $timezones;
    }
}
?>