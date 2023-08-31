<?php

require_once  "/var/www/task.squirrel.net.pl/"."vendor/autoload.php";
class Main{
    public $array;
    public function __construct($array) {
        $this->array = $array;
    }

    function check(){
        $products = array(
            "dvd"=>"Products/DVD.php",
            "book"=>"Products/Book.php",
            "furniture"=>"Products/Furniture.php"
        );
        
        // #NOTE this could be done also with >>strtolower("Products/".$products[$this->array['productType']])<< but it also could brig future problems
        $isValid = require_once($products[$this->array['productType']]);

        if(!is_bool($isValid) || !$isValid){
            print_r(json_encode($isValid));
            return;
        }
        print_r("true");
    }
}

if(!empty($_POST["array"])){
    $main = new Main(json_decode($_POST["array"], true));
    $main->check();
}
?>