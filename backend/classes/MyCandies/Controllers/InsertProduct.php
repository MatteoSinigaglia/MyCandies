<?php
namespace MyCandies\Controllers;

use DB\Exceptions\TransactionException;
use Exception;
use InvalidArgumentException;
use DB\dbh;
use MyCandies\Exceptions\EntityException;
use MyCandies\Tables\Table;
use MyCandies\Entity\Product;
use MyCandies\Entity\Image;
use MyCandies\Entity\Category;
use MyCandies\Entity\ProductImage;

class InsertProduct {

    private $T_images;
    private $T_products;
    private $T_categories;
    private $T_productsImages;
    private $productImage;
    private $product;
    private $image;
    private $dbh;
    
    private const PATH_TO_ENTITY = '.'.DS.'..'.DS.'Entity'.DS;

    public function __construct(array $product, array $image) {
        try{
            $this->dbh = new dbh();
            $this->product = new Product($product);
            $this->image = new Image($image);
            $this->T_products = new Table($this->dbh, 'Products', 'id', self::PATH_TO_ENTITY.'Product');
            $this->T_images = new Table($this->dbh, 'Images', 'id', self::PATH_TO_ENTITY.'Image');
            $this->T_categories = new Table($this->dbh, 'Categories', 'id', self::PATH_TO_ENTITY.'Category');
            $this->T_productsImages = new Table($this->dbh, 'ProductsImages', 'id', self::PATH_TO_ENTITY.'ProductImage');
        } catch (EntityException $e) {
            throw $e;
        }
    }

    public function insertProduct() : bool {
        try {
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $data = array();
            $data['product_id'] = $this->T_products->insert($product);
            $data['image_id'] = $this->T_image->insert($image);
            $this->productImage = new ProductImage($data);
            $T_productsImages->insert($this->productImage);
            $this->dbh->transactionCommit();
            
		} catch (Exception $e) {
            $this->dbh->transactionRollback();
            throw $e;
            
		} finally {
            $this->dbh->disconnect();
            
		}
		return true;
    }

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