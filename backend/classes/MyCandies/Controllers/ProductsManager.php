<?php

namespace MyCandies\Controllers;

require_once MODEL_PATH . DS . 'classes' . DS . 'DB' . DS . 'dbh.php';
require_once MODEL_PATH . DS . 'classes' . DS . 'DB' . DS . 'Exceptions' . DS . 'DBException.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Image.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'ProductImage.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Product.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'ActivePrinciple.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Effect.php';
require_once MYCANDIES_PATH . DS . 'Tables' . DS . 'Table.php';

use DB\dbh;
use DB\Exceptions\DBException;
use Exception;
use MyCandies\Entities\ActivePrinciple;
use MyCandies\Entities\Category;
use MyCandies\Entities\Effect;
use MyCandies\Entities\Image;
use MyCandies\Entities\Product;
use MyCandies\Entities\ProductImage;
use MyCandies\Exceptions\EntityException;
use MyCandies\Tables\Table;

class ProductsManager
{

    private $T_images;
    private $T_products;
    private $T_categories;
    private $T_productsImages;
    private $T_activePrinciples;
    private $T_effects;
    private $dbh;

    public function __construct()
    {
        $this->dbh = new dbh();
        $this->T_products = new Table($this->dbh, 'Products', 'id', Product::class);
        $this->T_images = new Table($this->dbh, 'Images', 'id', Image::class);
        $this->T_categories = new Table($this->dbh, 'Categories', 'id', Category::class);
        $this->T_productsImages = new Table($this->dbh, 'ProductsImages', 'id', ProductImage::class);
        $this->T_activePrinciples = new Table($this->dbh, 'ActivePrinciples', 'id', ActivePrinciple::class);
        $this->T_effects = new Table($this->dbh, 'Effects', 'id', Effect::class);
    }

    /**
     * @param $product
     * @param $image
     * @param $activePrinciple
     * @param $effects
     * @return bool
     * @throws DBException
     * @throws EntityException
     */
    public function insertProduct($product, $image, $activePrinciple, $effects): bool
    {
        try {
            $this->dbh->connect();
            $data = array();
            $this->dbh->transactionStart();
            $data['product_id'] = $this->T_products->insert($product->getValues());
            $data['img_id'] = $this->T_images->insert($image->getValues());
            $productImage = new ProductImage(ProductImage::PRODUCT_IMAGES, $data);
            $this->T_productsImages->insert($productImage->getValues());
            $this->T_activePrinciples->insert($activePrinciple->getValues());
            $this->T_effects->insert($effects->getValues());
            $this->dbh->transactionCommit();
            $this->uploadImage(); // carica l'immagine nel server
        } catch (EntityException | DBException | Exception $e) {
            $this->dbh->transactionRollback();
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return true;
    }

    private function uploadImage()
    {
        $uploaddir = ROOT . DS . 'img' . DS . 'products' . DS;
        $uploadfile = $uploaddir . $_FILES['productImage']['name'];
        if (!move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadfile)) {
            throw new Exception('Immagine non caricata nel server');
        }
    }

    public function removeProduct()
    {
    }

    public function getProductsHtml()
    {
        $productList = $this->getProducts();
        $listOfProducts = "";
        if ($productList != null) {
            foreach ($productList as $product) {
                $listOfProducts .=
                    '<div class="singleProduct">'
                    . '<p>'
                    . $product['Nome']
                    . '</p>'
                    . '</div>';
            }
        } else {
            $listOfProducts =
                '<li class=\"failure\">'
                . 'Non sono ancora stati inseriti prodotti'
                . '</li>';
        }
        $htmlPage = file_get_contents("../frontend/listaProdotti.html");
        echo str_replace("<productList />", $listOfProducts, $htmlPage);
    }
}

?>