<?php
namespace MyCandies\Controllers;

require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'dbh.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Image.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'ProductImage.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Product.php';
require_once MYCANDIES_PATH.DS.'Tables'.DS.'Table.php';

use DB\Exceptions\DBException;
use Exception;
use DB\dbh;
use MyCandies\Exceptions\EntityException;
use MyCandies\Tables\Table;
use MyCandies\Entities\Product;
use MyCandies\Entities\Image;
use MyCandies\Entities\ProductImage;

class InsertProduct {

    private $T_images;
    private $T_products;
    private $T_categories;
    private $T_productsImages;
    private $dbh;

    public function __construct(){
        $this->dbh = new dbh();
        $this->T_products = new Table($this->dbh, 'Products', 'id', Product::class);
        $this->T_images = new Table($this->dbh, 'Images', 'id', Image::class);
        $this->T_categories = new Table($this->dbh, 'Categories', 'id', Category::class);
        $this->T_productsImages = new Table($this->dbh, 'ProductsImages', 'id', ProductImage::class);
    }

    /**
     * @param $product
     * @param $image
     * @return bool
     * @throws DBException
     * @throws \MyCandies\Exceptions\EntityException
     */
    public function insertProduct($product, $image) : bool {
        try {
            $this->dbh->connect();
            $data = array();
            $this->dbh->transactionStart();
            $data['product_id'] = $this->T_products->insert($product->getValues());
            $data['image_id'] = $this->T_image->insert($image->getValues());
            $productImage = new ProductImage(ProductImage::PRODUCT_IMAGES, $data);
            $this->T_productsImages->insert($productImage->getValues());
            $this->dbh->transactionCommit();
		} catch (EntityException | DBException | Exception $e) {
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