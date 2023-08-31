<?php
namespace App\Product_Add_Page\Products;

require  "/var/www/task.squirrel.net.pl/"."vendor/autoload.php";
use App\Product_Add_Page\Database;

abstract class Product extends Database{

    private $insertSqlQuery;
    private $sku;
    private $name;
    private $price;

    public function standardValidation(){
        $errorCode = ["elements"=>[  ]];

        //Validation for sku//
        if(strlen($this->array['sku']) < 20 && strlen($this->array['sku']) > 0 ){
            if(!$this->isSkuUnique($this->array['sku'])) array_push($errorCode["elements"],
            array(
                "element_id" => "sku",
                "error" => "There is same SKU in database!"
            ));
        }else{
            array_push($errorCode["elements"], array(
                "element_id" => "sku",
                "error" => "SKU can't have more than 20 letters and less than 1"  #NOTE: 20 is a random number i chose couse i needed to set max length for unique value in SQL i could be changed for example to 255 
            ));
        }
        $this->__setSku($this->array['sku']);

        //Validation for name//
        if(strlen($this->array['name']) > 0){
        }else{
            array_push($errorCode["elements"], array(
                "element_id" => "name",
               "error" => "name can't be empty!"
            ));
        }
        $this->__setName($this->array['name']);

        //Validation For price//
        $measuringUnitsValidation = $this->measuringUnitsValidation($this->array['price'], "price");
        if(!is_bool($measuringUnitsValidation)) $errorCode = array_merge($measuringUnitsValidation, $errorCode);
        foreach($measuringUnitsValidation['elements'] as &$x){
            array_push($errorCode["elements"], $x);
        }
        $this->__setPrice($this->array['price']);

        if(sizeof($errorCode['elements']) > 0){ 
            return $errorCode;
        }
        return true;
    }

    public function measuringUnitsValidation($measuringUnit, $elementID){
        $errorCode2 = ["elements"=>[  ]];
        if(strlen($measuringUnit) > 0 && strlen($measuringUnit) < 7){
            if(!is_numeric($measuringUnit)){
                array_push($errorCode2["elements"], array(
                    "element_id" => "$elementID",
                    "error" => "This can't have letters!"
                ));
            }
            else{
                if(floatval($measuringUnit) <= 0){
                    array_push($errorCode2["elements"], array(
                        "element_id" => "$elementID",
                        "error" => "This can't be less or equal 0"
                    ));
                }
            }
        }else{
            if(strlen($measuringUnit) < 0 ){
                array_push($errorCode2["elements"], array(
                    "element_id" => "$elementID",
                    "error" => "This can't be empty!"
                ));
            }
            elseif (strlen($measuringUnit) >= 7) {
                array_push($errorCode2["elements"], array(
                    "element_id" => "$elementID",
                    "error" => "element can't be bigger than 7 characters"
                ));
            }
        }

        if(sizeof($errorCode2['elements']) > 0){
            return $errorCode2;
        }
        return true;
    }

    abstract public function customInputsValidation();
    
    public function insertToDB($strValue, $keys, $values){
        //* insert into product table *
        $sql = "INSERT INTO products(sku,name,type,price,strValue)
        VALUES(
            '".$this->sku."',
            '".$this->name."',
            '".strtoupper($this->array['productType'])."',
            ".$this->price.",
            '".$strValue."'
        );";
        
        //* insert into child tables *
        $sql .= "INSERT INTO ".strtoupper($this->array['productType'])."(".implode(",", $keys).",product_ID)
        VALUES(".implode(",", $values).",
        (SELECT LAST_INSERT_ID(Id) from products order by LAST_INSERT_ID(Id) DESC limit 1));";

        return $this->__setInsertQuery($sql);
    }

    public function __setInsertQuery($sql){
        // in this step $sql could be checked to make sure is correct at least i would have done that thats why there are getters and setters to my knowledge.
        // i did not have done that because i don't really know how much time i have left 😅.
        $this->insertSqlQuery = $sql;
        return $this->insertNewProduct($this->insertSqlQuery);
    }

    public function __setSku($newSku){
        $this->sku = $newSku;
    }

    public function __setName($newName){
        $this->name = $newName;
    }

    public function __setPrice($newPrice){
        $this->price = $newPrice;
    }
}
?>