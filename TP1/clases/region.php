<?php

require_once './interfaces/IGetData.php';
require_once './clases/fullCountry.php';

class Region implements IGetData {

    private $name;
    private $countries;
    private $population;

    function __construct($array) {
        $this->name = $array[0]->region;
        $this->countries = $this::setCountries($array);
        $this->population = $this::setPopulation($array);
    }

    function getData() {
        $str = "Region Data <br><br>";
        $str .= "Name: $this->name <br> 
        Population: $this->population <br>
        Countries: <br><br>";

        for ($i=0; $i < count($this->countries); $i++) { 
            $str .= $this->countries[$i]->getData();
            $str .= "<br>";
        }

        return $str;
    }

    function setCountries($array) {
        $a = [];

        for ($i=0; $i < count($array); $i++) { 
            $country = new fullCountry($array[$i]);
            array_push($a, $country);
        }

        return $a;
    }

    function setPopulation($array) {
        $population = 0;

        for ($i=0; $i < count($array); $i++) { 
            $population += $array[$i]->population;
        }

        return $population;
    }
}

?>