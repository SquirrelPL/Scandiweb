<?php



require("Product_Add_Page/Database.php");
class Main extends Database{

    function importProducts(){  

        $result = $this->db("SELECT sku, name,price,strValue FROM products");
        $rows = array();
        while($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        print_r(json_encode($rows));

    }
}


$main = new Main();
$main->importProducts();

?>