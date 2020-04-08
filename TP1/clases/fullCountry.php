<?php

require_once './interfaces/IGetLanguage.php';

class FullCountry extends Country implements IGetLanguage {

    private $population;
    private $subregion;
    private $language;

    function __construct($object){
        parent ::__construct($object);
        $this->population = $object->population;
        $this->subregion = $object->subregion;
        $this->language = $this->getLanguageName($object);
    }

    function getLanguageName($object){
        $language = $object->languages[0]->name;
        return $language;
    }

    function getData() {
        $str = parent::getData();
        $str .= "Subregion : $this->subregion <br> 
        Population : $this->population <br> 
        Language : $this->language <br>";

        return $str;
    }
}

?>