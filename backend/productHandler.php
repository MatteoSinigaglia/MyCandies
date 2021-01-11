<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "product.php";
class ProductHandler {

    private $connection;
    
    public function __construct($connection, $product) {
        $this->connection = $connection;
        $this->product = $product;
    }

    public function insertProduct($product) {
        $insertQuery = "
            insert into Products(
                category_id, 
                name, 
                description,
                price, 
                availability,
                linked_category
            )
            values(
                {$product->getCategory_id()},
                \"{$product->getName()}\",
                \"{$product->getDescription()}\",
                {$product->getPrice()},
                {$product->getAvailability()},
                {$product->getLinked_category()}
            )"
            ;
        return mysqli_query($this->connection, $insertQuery);
    }

    public function getProductId($name) {
        $selectQuery = "
            select * from Products where name='{$name}'order by id asc";
        $queryResult = mysqli_query($this->connection, $selectQuery);    
        if(mysqli_num_rows($query)==0)
            return null;
        $result = mysqli_fetch_array($queryResult);
        return $result['id'];
    }

    private function getProducts() {
        $selectQuery = "
            select * from Products order by id asc"
            ;
        $queryResult = mysqli_query($this->connection, $selectQuery);    
        if(mysqli_num_rows($query)==0)
            return null;
        else {
            $productList = array();
            while($row = mysqli_fetch_array($queryResult)) {
                $product = array(
                    "category_id"       => $row['category_id'], 
                    "name"              => $row['name'],  
                    "description"       => $row['description'],
                    "price"             => $row['price'], 
                    "availability"      => $row['availability'],
                    "linked_category"   => $row['linked_category']
                );
                array_push($productList, $product);
            }
        }
        return $productList;
    }

    /**
     * elimino un prodotto cercandolo per nome anche se non è la chiave
     */
    public function removeProduct() {}
    public function getProductsHtml() {
        $productList = $this->getProducts();
        $listOfProducts = "";
        if($productList != null) {
            foreach ($productList as $product) {
                $listOfProducts .=
                    '<div class="singleProduct">'
                    . '<p>'
                    . $product['Nome']
                    . '</p>'
                    . '</div>'
                    ;
            }
        } else {
            $listOfProducts = 
                '<li class=\"failure\">'
                . 'Non sono ancora stati inseriti prodotti'
                . '</li>'
                ;
        }
        $htmlPage = file_get_contents("../frontend/listaProdotti.html");
        echo str_replace("<productList />", $listOfProducts, $htmlPage);
    }
}
?>