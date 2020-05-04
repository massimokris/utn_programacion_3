<?php

class Sales {
    public $type;
    public $flavor;
    public $price;
    public $client;

    public function sell($type, $flavor, $client){
        $pizza = Pizza::getPizza($type, $flavor);
        if(!$pizza || $pizza->stock < 1) return false;
        $this->client = $client->email;
        $this->type = $pizza->type;
        $this->flavor = $flavor;
        $this->price = $pizza->price;
        $this->fecha = date('Y/m/d');
        $pizza->stock -= 1;
        $file = new File('sales.json');
        if($file->writeJson($this)){
            return $this;
        }
    }

    public static function getSales($user){
        $sales = File::readJson('sales.json');

        if($user->type == 'encargado'){
            $data = new stdClass();
            $data->sales = 0;
            $data->total = 0;

            foreach ($sales as $key => $value) {
                $data->sales++;
                $data->total += $value->price;
            }
            return json_encode($data);
        }else {
            $data = array();
            foreach ($sales as $key => $value) {
                if($value->client == $user->email){
                    array_push($data, $value);
                }
            }
            return json_encode($data);
        }
    }
}