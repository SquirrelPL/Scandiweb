<?php
namespace App\Product_Add_Page\Products;
require_once  "/var/www/task.squirrel.net.pl/"."vendor/autoload.php";

class Furniture extends Product{
    public $array;
    public function __construct($array) {
        $this->array = $array;
    }

    public function customInputsValidation(){
        $standartValidation = $this->standardValidation(); //--//standardValidation() is from Product abstract class
                                                              // made for validating fields that not change
        if(!is_bool($standartValidation)) return $standartValidation;

        $errorCode2 = ["elements"=>[  ]];
        $measuringUnitsValidationArray = array();

        $measuringUnitsValidationArray[0] =
        $this->measuringUnitsValidation($this->array['height'], "height");//--//measuringUnitsValidation() 
                                                                             //is a premade function in Product
        $measuringUnitsValidationArray[1] =
        $this->measuringUnitsValidation($this->array['width'], "width");//--//measuringUnitsValidation() 
                                                                           //is a premade function in Product

        $measuringUnitsValidationArray[2] =
        $this->measuringUnitsValidation($this->array['length'], "length");//--//measuringUnitsValidation() 
                                                                             //is a premade function in Product
        foreach($measuringUnitsValidationArray as &$measuringUnitsValidation){
            foreach($measuringUnitsValidation['elements'] as &$x){
                array_push($errorCode["elements"], $x);
            }
        }

        if(sizeof($errorCode2['elements']) > 0){
            return $errorCode2;
        }

        $keys = array("Height", "Width", "Length"); //sets columns for sql query
        $values = array($this->array['height'], //sets values for those columns
        $this->array['width'],
        $this->array['length']);

        $strValue =
        "Dimension: ".$this->array['height']."x".$this->array['width']."x".$this->array['length'];

        return $this->insertToDB( //-> Product.php
           $strValue,
           $keys,
           $values
       );
    }
}
$furniture = new Furniture($this->array);
return $furniture->customInputsValidation();
?>