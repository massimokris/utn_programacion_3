<?php

require_once './interfaces/IGetData.php';

class Country implements IGetData {
    
   private $name;
   private $capital;
   private $region;


   function __construct($object){
        $this->name = $object->name;
        $this->capital = $object->capital;
        $this->region = $object->region;
    }

    function getData(){
        $str = "Country Data <br><br>";
        $str .= "Name : $this->name <br> 
        Capital : $this->capital <br> 
        Region : $this->region <br>";

        return $str;
    }
}

?>