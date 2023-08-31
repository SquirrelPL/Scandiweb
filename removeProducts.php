<?php
require_once realpath("vendor/autoload.php");
use App\Product_Add_Page\Database;
class Main extends Database{
    public $array;
    public function __construct($array) {
        $this->array = $array;
    }

    function remove(){
        $this->array=str_replace(",","' OR sku='", $this->array);
        $result = $this->db("DELETE FROM products WHERE sku='$this->array'");
        print_r($result);
    }
}

if(!empty($_POST["array"])){
    $main = new Main($_POST["array"]);
    $main->remove();
}
?>