<?php
namespace App\Product_Add_Page\Products;

require_once  "/var/www/task.squirrel.net.pl/"."vendor/autoload.php";
class DVD extends Product{
    public $array;
    public function __construct($array){
        $this->array = $array;
    }

    public function customInputsValidation(){
        $standartValidation = $this->standardValidation(); //--//standardValidation() is from Product abstract class
                                                              // made for validating fields that not change
        if(!is_bool($standartValidation)) return $standartValidation;

        //custom validation system goes here//
        $measuringUnitsValidation = $this->measuringUnitsValidation($this->array['size'], "size");//--//measuringUnitsValidation()
        if(!is_bool($measuringUnitsValidation)) return $measuringUnitsValidation;                    //is a premade function in Product

        $keys = array("Size"); //sets columns for sql query
        $values = array($this->array['size']); //sets values for those columns
        $strValue = "Size: ".$this->array['size']." MB";

        return $this->insertToDB( //-> Product.php
            $strValue,
            $keys,
            $values
        );
    }
}
$dvd = new DVD($this->array);
return $dvd->customInputsValidation();
?>