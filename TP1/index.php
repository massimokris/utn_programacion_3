<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/clases/country.php';
require_once __DIR__ . '/clases/fullCountry.php';
require_once __DIR__ . '/clases/region.php';
use NNV\RestCountries;

$restCountries = new RestCountries;

// echo json_encode($restCountries->byRegion("americas")[0]);

// $data = $restCountries->byName("canada");

// $dati = json_encode($data);

// echo $dati;

// $restCountries = new RestCountries;
$country = new Country($restCountries->byName('canada')[0]);
$fullCountry = new FullCountry($restCountries->byCapitalCity("caracas")[0]);
$region = new Region($restCountries->byRegion('americas'));

echo "--------------- Country ---------------<br><br>";
echo $country->getData();
echo "<br><br>--------------- Full Country ---------------<br><br>";
echo $fullCountry->getData();
echo "<br><br>--------------- Region ---------------<br><br>";
echo $region->getData();