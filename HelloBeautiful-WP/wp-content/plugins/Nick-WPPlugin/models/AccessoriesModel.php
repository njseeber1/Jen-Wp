<?php

/**
 * Accessories short summary.
 *
 * Accessories description.
 *
 * @version 1.0
 * @author Nick
 */
class Accessories
{
    var $idAccessories;
    var $Name;
    var $price;
    var $quantity;
    var $description;
    var $vendor;
    
    function __construct($data) {
        foreach($data as $key => $val) {
            if(property_exists(__CLASS__,$key)) {
                $this->$key = $val;
            }
        }
    }
    
}
