<?php
/**
 * @package List Insiden
 * 
 * @author Abdiiwan <abdiiwan1841@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Chart_Resume_Insiden_Controller extends Admin_Template_Controller {
	
	public function index() {
        global $dcistem;
        
        $tpl  = new View("resume_insiden_statistik");
        $db   = $dcistem->getOption("framework/db"); 
        //$login_as = $_SESSION["framework"]
         //echo "<pre>";print_r($_SESSION["framework"]["current_user"]);echo "</pre>";
        //echo "<pre>";print_r($_SESSION["framework"]);echo "</pre>";
        $current_level=$_SESSION["framework"]["user_level"];
        $tpl->current_level      = $current_level;
        $tpl->url_dashboard      = url::current("dashboard");
        $this->tpl->content = $tpl;
        $this->tpl->render();
	    
   }
   
   public function dashboard($kategori) {
        global $dcistem;
        
        
        $db   = $dcistem->getOption("framework/db"); 
        $master         =new Master_Ref_Model();
        $rekap=new Rekap_Insiden_Model();
        $color=array(157=>"orange",
                    158=>"green",//karangtengah
                    159=>"blue",//mande
                    160=>"red");//sukaluyu
        $this->settings =$master->settings();
        
		$tpl  = new View("grafik_yearly_insiden");
		$datayear= $rekap->getRekapInsidenByMonth(date('Y'));
		$bar_chart=array();
		$j=0;
		$list_data=array();
		$list_qry = $db->query("SELECT DATE_FORMAT(STR_TO_DATE(kode, '%m'),'%b') AS bulan FROM ref_kode_bulan"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['bln'][]=$data->bulan;
		}
		$bar_chart['labels']=$list_data[0]['bln'];
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][$j]['label']=$data2['partner_alias']; 
			$bar_chart['datasets'][$j]['backgroundColor']=$colord;
			$bar_chart['datasets'][$j]['borderColor']=$colord;
			$bar_chart['datasets'][$j]['borderWidth']=0.5;
			$bar_chart['datasets'][$j]['data']=$data2['qty']; 
			$bar_chart['datasets'][$j]['fill']=false; 
			
			$j++;
			next($datayear);
		}
		
		$tpl->yearly_data= json_encode($bar_chart);

		//
		$datayear= $rekap->getRekapInsidenByCompany(date('Y'));
		$bar_chart=array();
		$j=0;
		$list_data=array();
		$list_qry = $db->query("SELECT name,alias FROM partner where is_company='1'"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->alias;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->company_data= json_encode($bar_chart);
		
		//
		$datayear= $rekap->getRekapInsidenByJenis(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_insiden FROM ref_jenis_insiden ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama_insiden;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->insiden_data= json_encode($bar_chart);

		//
		$datakontraktor= $rekap->getRekapInsidenByKontraktor(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT id,name FROM partner WHERE is_contractor=1 ORDER BY nik"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->name;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		// var_dump($datakontraktor);

		while($data2=current($datakontraktor)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datakontraktor);
		}
		
		$tpl->kontraktor_data= json_encode($bar_chart);

		// //
		// $datayear= $rekap->getRekapInsidenByDepatemen(date('Y'));
		// $bar_chart=array();
		
		// $list_qry = $db->query("SELECT kode,nama_departemen FROM ref_departemen ORDER BY kode"); 
		// while($data=$db->fetchObject($list_qry)){
		// 	$list_data[0]['nama'][]=$data->nama_departemen;
		// }
		// $bar_chart['labels']=$list_data[0]['nama'];

		// $j=0;
		// $list_data=array();
		// while($data2=current($datayear)){
		// 	$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
		// 	$bar_chart['datasets'][0]['label']='Data '; 
		// 	$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
		// 	$bar_chart['datasets'][0]['borderColor'][]=$colord;
		// 	$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
		// 	$j++;
		// 	next($datayear);
		// }

		//
		$datayear= $rekap->getRekapInsidenByDepatemen(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT id,name FROM organizational_structure ORDER BY id"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->name;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->departemen_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByJamKerja(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_jam FROM ref_kode_jam ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama_jam;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->jam_data= json_encode($bar_chart);

		//
		$datayear= $rekap->getRekapInsidenByShiftKerja(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_shift FROM ref_shift ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama_shift;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->shift_kerja_data= json_encode($bar_chart);

		//
		$datayear= $rekap->getRekapInsidenByHariKerja(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_hari FROM ref_kode_hari ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama_hari;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->hari_kerja_data= json_encode($bar_chart);

		//
		$datayear= $rekap->getRekapInsidenByJabatan(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_jabatan FROM ref_jabatan ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama_jabatan;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->jabatan_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByMasaKerja(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_masa_kerja FROM ref_masa_kerja ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama_masa_kerja;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->masa_kerja_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByUmur(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_umur FROM ref_umur ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama_umur;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->umur_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByArea(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_area FROM ref_area_kerja ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama_area;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->area_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByBulan(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_bulan FROM ref_kode_bulan ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama_bulan;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->bulan_data= json_encode($bar_chart);

		//
		$datayear= $rekap->getRekapInsidenByAlat(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_alat_terlibat as nama FROM ref_alat_terlibat ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->alat_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByLuka(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_bagian_luka as nama FROM ref_bagian_luka ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->luka_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByCaraKerja(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_cara_kerja as nama FROM ref_cara_kerja_tidak_standar ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->cara_kerja_data= json_encode($bar_chart);
		$tpl->luka_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByKondisiKerja(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_kondisi as nama FROM ref_kondisi_tidak_standar ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->kondisi_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByFaktorKerja(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_faktor_pekerjaan as nama FROM ref_faktor_pekerjaan ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->fkerja_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByFaktorPribadi(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_faktor_personal as nama FROM ref_faktor_personal ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->fpribadi_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByTindakan(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_tindakan as nama FROM ref_tindakan_perbaikan ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->tindakan_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenBySanksi(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_sanksi as nama FROM ref_sanksi ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->sanksi_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByHilang(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_lost_workday as nama FROM ref_lost_workday ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->hilang_data= json_encode($bar_chart);
		//
		$datayear= $rekap->getRekapInsidenByBiaya(date('Y'));
		$bar_chart=array();
		
		$list_qry = $db->query("SELECT kode,nama_biaya_perbaikan as nama FROM ref_biaya_perbaikan ORDER BY kode"); 
		while($data=$db->fetchObject($list_qry)){
			$list_data[0]['nama'][]=$data->nama;
		}
		$bar_chart['labels']=$list_data[0]['nama'];

		$j=0;
		$list_data=array();
		while($data2=current($datayear)){
			$colord='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
			$bar_chart['datasets'][0]['label']='Data '; 
			$bar_chart['datasets'][0]['backgroundColor'][]=$colord;;
			$bar_chart['datasets'][0]['borderColor'][]=$colord;
			$bar_chart['datasets'][0]['data'][]= $data2['ttl']>0? round( (($data2['qty']/$data2['ttl'])*100),2):0;
			$j++;
			next($datayear);
		}
		
		$tpl->biaya_data= json_encode($bar_chart);
		//
        $tpl->content = $tpl;
        $tpl->render();
	    
   }

    public function refresh($report="weekly") {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db"); 
        $master=new Master_Ref_Model();
        switch($report){
            case "weekly":
                $list_qry=$db->select("rwp.id,wp.tahun,wp.week,rwp.partner_id,qty,target","report_weekly_production rwp
                inner join week_periode wp on wp.id=rwp.periode_id ")
        		->where("ifnull(locked,0)=0")->orderBy("wp.tahun asc,wp.week asc")->lim();//
                while($data = $db->fetchObject($list_qry))
                {
                    $pre_total=$db->select("IFNULL(sum(qty),0) cum_qty,IFNULL(sum(target),0) cum_target","report_weekly_production rwp
                    inner join week_periode wp on wp.id=rwp.periode_id")
                    ->where("partner_id=".$data->partner_id." and ((wp.week<=".$data->week." and wp.tahun=".$data->tahun.") or (wp.tahun<".$data->tahun.")) ")->get(0);
                   // print_r($pre_total);
                    $cum_target     = $pre_total->cum_target;
                    $cum_qty        = $pre_total->cum_qty;
                    $cum_target_val	=$master->scurevaluetable($cum_target,"number",false);
                    $cum_qty_val	=$master->scurevaluetable($cum_qty,"number",false);
		        	$cols_and_vals   ="cumulative_qty=$cum_qty_val,cumulative_target=$cum_target_val";
                    $sqlin	="UPDATE report_weekly_production SET $cols_and_vals WHERE id=".$data->id."";								
					$db->query($sqlin);                
                                    
                }
            break;
            case "monthly":
                $list_qry=$db->select("id,month,partner_id,qty,target","report_monthly_production ")
        		->where("ifnull(locked,0)=0")->orderBy("cast( left(month,4) as INT) asc,cast( right(month,2) as INT) asc")->lim();//
                while($data = $db->fetchObject($list_qry))
                {
                    list($tahun,$bulan)=explode("-",$data->month);
                    $bulan=(int)$bulan;
                    $pre_total=$db->select("sum(IFNULL(qty,0)) cum_qty,sum(IFNULL(target,0)) cum_target","report_monthly_production")
                    ->where("partner_id=".$data->partner_id." and ((cast( left(month,4) as INT)=".$tahun." and cast( right(month,2) as INT)<=".$bulan.") 
                    or (cast( left(month,4) as INT)<".$tahun." ))")->get(0);
                   // print_r($pre_total);
                    $cum_target     = $pre_total->cum_target;
                    $cum_qty        = $pre_total->cum_qty;
                    $cum_target_val	=$master->scurevaluetable($cum_target,"number",false);
                    $cum_qty_val	=$master->scurevaluetable($cum_qty,"number",false);
		        	$cols_and_vals   ="cumulative_qty=$cum_qty_val,cumulative_target=$cum_target_val";
                    $sqlin	="UPDATE report_monthly_production SET $cols_and_vals WHERE id=".$data->id."";								
					$db->query($sqlin); 
                }               
            break;
        }
    }
}
 

?>