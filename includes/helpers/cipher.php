<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Barcode Helper
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class cipher {
    private static $mode = 'MCRYPT_BLOWFISH';
    private static $key = 'q!2wsd#45^532dfgTgf56njUhfrthu&^&ygsrwsRRsf';
    
    public static function encrypt($buffer){
        $iv                 = mcrypt_create_iv(mcrypt_get_iv_size(constant(self::$mode), MCRYPT_MODE_ECB), MCRYPT_RAND); 
        $passcrypt  = mcrypt_encrypt(constant(self::$mode), self::$key, $buffer, MCRYPT_MODE_ECB, $iv); 
        $encode         = base64_encode($passcrypt); 
        return $encode; 
    }
    
    public static function decrypt($buffer){
        $decoded        = base64_decode($buffer); 
        $iv                 = mcrypt_create_iv(mcrypt_get_iv_size(constant(self::$mode), MCRYPT_MODE_ECB), MCRYPT_RAND); 
        $decrypted  = mcrypt_decrypt(constant(self::$mode), self::$key, $decoded, MCRYPT_MODE_ECB, $iv);
        return $decrypted;
    }
}
?>