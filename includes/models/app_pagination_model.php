<?php

/**
 * @package Admin
 * @subpackage Admin Login Modul
 * 
 * @author Hasan <kuring.hasan@gmail.com, hasan@unpad.ac.id>
 *
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class App_Pagination_Model extends Model {
	
	public function __construct() {
		
	}
	
    public function paging($jumlahdata,$reqpage,$jumlah_tampil) {
        
        //echo $jumlah_tampil;exit;
        $jmldata = $jumlahdata; 
        $n =($jmldata % $jumlah_tampil)==0 ? 0:1;
        $jmlpage = (int)($jmldata/$jumlah_tampil)+$n;  
        
        $firstpage = 1; 
        $lastpage=$jmlpage;
        $nextpage = $reqpage+1>$lastpage?$lastpage:$reqpage+1;
        $prevpage = $reqpage-1==0?$firstpage:$reqpage-1;
        
        $table            = new stdClass;
        $table->first     = 1;
        $table->prev      = $reqpage-1;
        $table->next      = $nextpage;
        $table->last      = $lastpage;
        $table->totaldata = $jmldata;
        $table->jmlpage   = $jmlpage;

        return $table;
    }
    public function pagination($jumlahdata,$current_page,$show_entries=10,$form_id,$url_page="") {
        //$url_asli=trim($url_page)==""?"#":$url_page;
        $jmldata = $jumlahdata; 
        $n =($jmldata % $show_entries)==0 ? 0:1;
        $jmlpage = (int)($jmldata/$show_entries)+$n;  
        $firstpage = 1; 
        $lastpage=$jmlpage;
        $nextpage = $current_page+1>$lastpage?$lastpage:$current_page+1;
        $prevpage = $current_page-1==0?$firstpage:$current_page-1;
        
        $return_js=trim($url_page)==""?"return false;":"";
        
        $pagination='';
          $pagination=$pagination.'<ul class="pagination">';
            $pagination=$pagination.'<li>';
            $url_prev_page=trim($url_page)==""?"":$url_page."?page=".$prevpage;
            $pagination=$pagination.'<a href="'.$url_prev_page.'" aria-label="Previous" aria-data="prev" onclick="setpaging('.$prevpage.');'.$return_js.'">';
            $pagination=$pagination.'<span aria-hidden="true">&laquo;</span></a>';
            $pagination=$pagination.'</li>';
            
        	  $page=$current_page;
        	  $end_number	= $jmlpage;
        	  $start_number    = 1;
        	  //echo "end number:".($end_number-5)."<br />";
               
              for($start_number ; $start_number <= $end_number ; $start_number++){
                $url_asli="";
                $url_asli=trim($url_page)==""?"":$url_page."?page=".$start_number;
                  $active = false;
                  if($page == $start_number){
                    $active = "class='active'";
                  }
        		  switch($page)
        		  {
        			  case $page<5:
        			  	if($start_number==6){
        					$pagination=$pagination.'<li '.$active.'><a href="#" disabled="disabled">...</a></li>';
        		  		}elseif($start_number==$end_number or  $start_number<=5){
        					$pagination=$pagination.'<li '.$active.'><a href="'.$url_asli.'" aria-data="'.$start_number.'" onclick="setpaging('.$start_number.');'.$return_js.'">'.$start_number.'</a></li>';
        				}
        			  break;
        			  case $page>=5 and $page<=($end_number-4):
        			  	if($start_number==($page+2) or $start_number==2){
        					$pagination=$pagination.'<li '.$active.'><a href="#" disabled="disabled">...</a></li>';
        		  		}elseif($start_number==1 or $start_number==$end_number or ($start_number>=($page-1) and $start_number<=($page+1))){
        					$pagination=$pagination.'<li '.$active.'><a href="'.$url_asli.'" aria-data="'.$start_number.'" onclick="setpaging('.$start_number.');'.$return_js.'">'.$start_number.'</a></li>';
        				}
        			  break;
        			  case $page>($end_number-4):
        			   // echo "crek";
        			  	if($start_number==($end_number-7)){
        				    $pagination=$pagination.'<li '.$active.'><a href="#" disabled="disabled">...</a></li>';
        		  		}elseif($start_number==1 or   $start_number>=($end_number-4)){
        					$pagination=$pagination.'<li '.$active.'><a href="'.$url_asli.'" aria-data="'.$start_number.'" onclick="setpaging('.$start_number.');'.$return_js.'">'.$start_number.'</a></li>';
        				}
        			  break;
        		  }
        		
              }
              $url_next_page=trim($url_page)=="#"?"":$url_page."?page=".$nextpage;
              $pagination=$pagination.'<li>';
                $pagination=$pagination.'<a href="'.$url_next_page.'" aria-label="Next" aria-data="next"  onclick="setpaging('.$nextpage.');'.$return_js.'">';
                $pagination=$pagination.'<span aria-hidden="true">&raquo;</span></a>';
              $pagination=$pagination.'</li>';
          $pagination=$pagination.'</ul>';

        return $pagination;
    }
    
    
}

?>