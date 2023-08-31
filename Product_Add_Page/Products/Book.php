<?php
namespace App\Product_Add_Page\Products;
require_once  "/var/www/task.squirrel.net.pl/"."vendor/autoload.php";
class Book extends Product{
    public $array;
    public function __construct($array) {
        $this->array = $array;
    }

    public function customInputsValidation(){
        
        $standartValidation = $this->standardValidation(); //--//standardValidation() is from Product abstract class
                                                              // made for validating fields that not change
        if(!is_bool($standartValidation)) return $standartValidation;

        $measuringUnitsValidation = 
        $this->measuringUnitsValidation($this->array['weight'], "weight");//--//measuringUnitsValidation() 
                                                                             //is a premade function in Product
        if(!is_bool($measuringUnitsValidation))
         return $measuringUnitsValidation;                    

         $keys = array("Weight"); //sets columns for sql query
         $values = array($this->array['weight']); //sets values for those columns
         $strValue = "Weight: ".$this->array['weight']." KG";
 
         return $this->insertToDB( //-> Product.php
            $strValue, 
            $keys,
            $values
        );

    }

}

$book = new Book($this->array);
return $book->customInputsValidation();
?>