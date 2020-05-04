<?php

class Pizza
{
    public $type;
    public $price;
    public $stock;
    public $flavor;
    public $picture;

    public function __construct($type, $price, $stock, $flavor, $picture)
    {
        $this->type = $type;
        $this->price = $price;
        $this->stock = $stock;
        $this->flavor = $flavor;
        $this->picture = File::saveImage('hola', $picture);
    }

    public static function validFlavor($flavor, $type, $path)
    {
        $pizzas = File::readJson($path);

        if ($pizzas == []) return true;

        foreach ($pizzas as $u => $value) {
            if ($value->flavor == $flavor && $value->type == $type) {
                return false;
            }
        }
        return true;
    }

    public static function getPizzas($email, $path)
    {
        $pizzas = File::readJson($path);

        if ($pizzas == []) return false;

        if (User::isAdmin($email, 'users.json')) {
            return json_encode($pizzas);
        }else {
            $pizzasWithoutStock = array();
            foreach ($pizzas as $key => $value) {
                unset($value->stock);
                array_push($pizzasWithoutStock, $value);
            }
            return json_encode($pizzasWithoutStock);
        }
    }

    public static function getPizza($type, $flavor){
        $pizzas = File::readJson(('pizzas.json'));
        if($pizzas == []) return false;
        foreach ($pizzas as $key => $value) {
            if($value->type == $type){
                if($value->flavor == $flavor){
                    return $value;
                }
            } 
        }
        return false;
    }
}
