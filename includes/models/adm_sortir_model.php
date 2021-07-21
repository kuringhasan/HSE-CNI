<?php

/**
 * @package Admin
 * @subpackage Admin Login Modul
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
 * @author Alan Ridwan M <alanrm82@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Adm_Sortir_Model extends Model {
	
	public function __construct() {
		
	}
	
    public function sortirOne2Many($fieldName="",$values="",$operator="") {
          if(is_array($values)) {
            $sortirmk="";
            $i=0;
            while($data=each($values))
            {
                $sortirmk=$sortirmk==""?" ".$fieldName."='".$values[$i]."'":" ".$sortirmk." ".$operator." ".$fieldName."='".$values[$i]."'";
                $i++;
            }
		  }elseif(is_object($values)) {
			$list = get_object_vars($values);
            $sortirmk="";
            $i=0;
            while($data=each($list))
            {
                $sortirmk=$sortirmk==""?" ".$fieldName."='".$data[$i]."'":" ".$sortirmk." ".$operator." ".$fieldName."='".$data[$i]."'";
                $i++;

            }
		 }
          return $sortirmk;  
    }
    
     public function fromFormcari($arrkeriteria="",$operator="") {
          if(is_array($arrkeriteria)) {
            $j=0;
    		$cari="";
    		while ($j<count($arrkeriteria))
    		{  
    			if ($cari=="")
    			{
    			   $cari=$arrkeriteria[$j];
    			   }
    			else
    			{
    				$cari=$cari." ".$operator." ".$arrkeriteria[$j];
    			}
    			$j=$j+1;
    		}
		 }
         
          return $cari;  
          
    }
}

?>