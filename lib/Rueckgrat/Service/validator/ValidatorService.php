<?php

/**
 * Validatior Class
 * 
 * This class can validate different input types.
 * 
 * The links in the document blocks include further information 
 * about the choosen validation solution.
 * 
 * @author  Alexander Feil
 * @author  Nils Abegg
 * @version 0.1
 * @package Service
 */
namespace Rueckgrat\Service\Validator;

class ValidatorServices
{
    /**
     * Validate maximum length
     * 
     * @param string $string
     * @param type $max
     * @return boolean 
     */
    public function isMax($string, $max)
    {
        if (strlen($string) <= $max) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate minimum length
     * 
     * @param  string  $string
     * @param  int     $min
     * @return boolean 
     */
    public function isMin($string, $min)
    {
        if (strlen($string) <= $min) {
           return true;
        }
        
        return false;
    }
    
    /**
     * Validate minimum and maximum length
     * 
     * @param  int     $min
     * @param  int     $max
     * @param  string  $string
     * @return boolean 
     */
    public function isMinMax($string, $min, $max)
    {
        if ($this->isMax($string, $max) AND $this->isMin($string, $min)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate Email
     * 
     * Email RegEx Comparison: http://fightingforalostcause.net/misc/2006/compare-email-regex.php
     * 
     * @param  string $email
     * @return boolean 
     */
    public function isEmail($email)
    {
        $regularExpression = '/^(?:(?:(?:[^@,"\[\]\x5c\x00-\x20\x7f-\xff\.]|\x5c(?=[@,"\[\]\x5c\x00-\x20\x7f-\xff]))(?:[^@,"\[\]\x5c\x00-\x20\x7f-\xff\.]|(?<=\x5c)[@,"\[\]\x5c\x00-\x20\x7f-\xff]|\x5c(?=[@,"\[\]\x5c\x00-\x20\x7f-\xff])|\.(?=[^\.])){1,62}(?:[^@,"\[\]\x5c\x00-\x20\x7f-\xff\.]|(?<=\x5c)[@,"\[\]\x5c\x00-\x20\x7f-\xff])|[^@,"\[\]\x5c\x00-\x20\x7f-\xff\.]{1,2})|"(?:[^"]|(?<=\x5c)"){1,62}")@(?:(?!.{64})(?:[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.?|[a-zA-Z0-9]\.?)+\.(?:xn--[a-zA-Z0-9]+|[a-zA-Z]{2,6})|\[(?:[0-1]?\d?\d|2[0-4]\d|25[0-5])(?:\.(?:[0-1]?\d?\d|2[0-4]\d|25[0-5])){3}\])$/';
        $result = preg_match($regularExpression, $email);
        
        return $result;
    }
}
