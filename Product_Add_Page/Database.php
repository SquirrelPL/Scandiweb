<?php
namespace App\Product_Add_Page;
use mysqli;
abstract class Database{
    public function db($query){
        // import database config
        if(file_exists("../config/config.php")){$config = require("../config/config.php");}
        else{$config = require("config/config.php");}

        $conn = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database']
        );

        if($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

        $result = $conn->query($query);

        if(mysqli_error($conn)){
            print_r(mysqli_error($conn));
            return false;
        }
        $conn->close();
        return $result;
    }

    public function isSkuUnique($sku){ //--------------------------//function for "Product.php" to check if $sku would be unique
        $query = "SELECT sku FROM products WHERE sku = '$sku'";   // if not it would return false and "Product.php" whould produce errorCode

        if($this->db($query)->num_rows){
            return false;
        }
        return true;
    }

    public function insertNewProduct($query){
        $pieces = explode(";", $query);
        foreach ($pieces as &$value) {
            $result = $this->db($value);
        }

        if($result->num_rows || $result){
            return false;
        }
        return true;
    }
}

/*                ______________________________________Database structure______________________________________

                                   
        +-------------+            
        | products    |            
        +-------------+            
        | id          | PRIMATY KEY
        |             |            
        | sku         | UNIQUE     
        |             |            
        | name        |            
        |             |                            
        |             |            
        | price       |        
        |             |            
        | strValue    |    
        +-------------+             
        


                +-------------+                 +------------+                    +-------------+
                | DVD         |                 | BOOK       |                    | FURNITURE   |
                +-------------+                 +------------+                    +-------------+
                | id          | PRIMATY KEY     | ID         | PRIMATY KEY        | ID          | PRIMATY KEY
                |             |                 |            |                    |             |
                | product_ID  | FOREIGN KEY     | product_ID | FOREIGN KEY        | product_ID  | FOREIGN KEY
                |             |                 |            |                    |             |
                | Size        |                 | Weight     |                    | Height      |
                +-------------+                 +------------+                    |             |
                                                                                  | Width       |
                                                                                  |             |
                                                                                  | Length      |
                                                                                  +-------------+



*/
?>

