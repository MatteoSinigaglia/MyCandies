<?php

namespace MyCandies\Controllers;

require_once MODEL_PATH . DS . 'classes' . DS . 'DB' . DS . 'dbh.php';
require_once MODEL_PATH . DS . 'classes' . DS . 'DB' . DS . 'Exceptions' . DS . 'DBException.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Image.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'ProductImage.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Category.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Product.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'ActivePrinciple.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'ProductsActivePrinciple.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Effect.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'SideEffect.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'ActivePrincipleEffect.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'ActivePrincipleSideEffect.php';
require_once MYCANDIES_PATH . DS . 'Tables' . DS . 'Table.php';

use DB\dbh;
use DB\Exceptions\DBException;
use Exception;
use MyCandies\Entities\ActivePrinciple;
use MyCandies\Entities;
use MyCandies\Entities\Category;
use MyCandies\Entities\Image;
use MyCandies\Entities\Product;
use MyCandies\Entities\ProductImage;
use MyCandies\Entities\ProductsActivePrinciple;
use MyCandies\Entities\SideEffect;
use MyCandies\Entities\Effect;
use MyCandies\Exceptions\EntityException;
use MyCandies\Entities\ActivePrincipleEffect;
use MyCandies\Entities\ActivePrincipleSideEffect;
use MyCandies\Tables\Table;

class ProductsManager
{

    private $T_images;
    private $T_products;
    private $T_categories;
    private $T_productsImages;
    private $T_activePrinciples;
    private $T_productsActivePrinciples;
    private $T_effects;
    private $T_sideEffects;
    private $T_activePrinciplesSideEffects;
    private $T_activePrinciplesEffects;
    private $dbh;

    public function __construct()
    {
        $this->dbh = new dbh();
        $constructorargs = [Entities\DB];
        $this->T_products = new Table($this->dbh, 'Products', 'id', Product::class, $constructorargs);
        $this->T_images = new Table($this->dbh, 'Images', 'id', Image::class, $constructorargs);
        $this->T_categories = new Table($this->dbh, 'Categories', 'id', Category::class, $constructorargs);
        $this->T_productsImages = new Table($this->dbh, 'ProductsImages', 'id', ProductImage::class, $constructorargs);
        $this->T_activePrinciples = new Table($this->dbh, 'ActivePrinciples', 'id', ActivePrinciple::class, $constructorargs);
        $this->T_productsActivePrinciples = new Table($this->dbh, 'ProductsActivePrinciples', 'id', ProductsActivePrinciple::class, $constructorargs);
        $this->T_effects = new Table($this->dbh, 'Effects', 'id', Effect::class, $constructorargs);
        $this->T_sideEffects = new Table($this->dbh, 'SideEffects', 'id', SideEffect::class, $constructorargs);
        $this->T_activePrinciplesEffects = new Table($this->dbh, 'ActivePrinciplesEffects', 'id', ActivePrincipleEffect::class, $constructorargs);
        $this->T_activePrinciplesSideEffects = new Table($this->dbh, 'ActivePrinciplesSideEffects', 'id', ActivePrincipleSideEffect::class, $constructorargs);
    }

    public function insertProduct($product, $image, $activePrincipleId, $percentage): bool
    {
        try {
            $this->dbh->connect();
            $data = array();
            $this->dbh->transactionStart();
            $data['product_id'] = $this->T_products->insert($product);
            $data['img_id'] = $this->T_images->insert($image);
            $productImage = new ProductImage(Entities\PRODUCTS_MANAGER, $data);
            $this->T_productsImages->insert($productImage);
            $data_products_active_principles['product_id'] = $data['product_id'];
            $data_products_active_principles['active_principle_id'] = $activePrincipleId;
            $data_products_active_principles['percentage'] = $percentage;
            $productsActivePrinciples = new ProductsActivePrinciple(Entities\PRODUCTS_MANAGER, $data_products_active_principles);
            $this->T_productsActivePrinciples->insert($productsActivePrinciples);
            $this->uploadImage(); // carica l'immagine nel server
            $this->dbh->transactionCommit();

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
        $uploadfile = ROOT. DS.'img' . DS . 'products' . DS . $_FILES['productImage']['name'];
        if (!move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadfile)) {
            throw new Exception('Immagine non caricata nel server');
        }
    }

    public function removeProduct()
    {
    }

    public function getProducts()
    {
        try{
            $this->dbh->connect();
            $products = $this->T_products->find();
            $images = $this->T_images->find();
            $productsImages = $this->T_productsImages->find();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
         $rows=[];
         foreach($products as $product) {
             $rows[$product->getId()] = [
                 'id'       => $product->getId(),
                 'name'     => $product->getName(),
                 'price'    => $product->getPrice()
             ];
         }
        foreach($productsImages as $productImage) {
            $rows[$productImage->getProduct_id()]['img_id'] = $productImage->getImg_id();
        }

        foreach($rows as $key => &$row) {
            foreach($images as $image) {
                if($image->getId() == (int)$row['img_id'])
                    $row['img_path'] = $image->getImg_path();
            }
        }

         return $rows;
    }

    public function getSingleProduct($id) : array {
        try{
            $this->dbh->connect();
            // trova il prodotto
            $products = $this->T_products->find([
                'column' => 'id',
                'value'  => $id
            ]);
            $categories = $this->T_categories->find([
                'column' => 'id',
                'value'  => $products[0]->getCategory_id()
            ]);
            // mi aspetto che nel database ogni prodotto abbia UNA SOLA immagine anche se sarebbe possibile averne di più
            $productsImages = $this->T_productsImages->find([
                'column' => 'product_id',
                'value'  => $id
            ]);
            $images = $this->T_images->find([
                'column' => 'id',
                'value'  => $productsImages[0]->getImg_id()
            ]);
            // ogni prodotto ha un solo principio attivo collegato anche se sarebbe possibile averne più di uno
            $productsActivePrinciple = $this->T_productsActivePrinciples->find([
                'column' => 'product_id',
                'value'  => $id
            ]);
            $activePrinciple = $this->T_activePrinciples->find([
                'column' => 'id',
                'value'  => $productsActivePrinciple[0]->getActive_principle_id()
            ]);
            $activePrinciplesEffects = $this->T_activePrinciplesEffects->find([
                'column' => 'active_principle_id',
                'value'  => $activePrinciple[0]->getId()
            ]);
            $activePrinciplesSideEffects = $this->T_activePrinciplesSideEffects->find([
                'column' => 'active_principle_id',
                'value'  => $activePrinciple[0]->getId()
            ]);
            // effects e sideEffects sono le uniche query che potrebbero restituire 1 o più elementi
            if(isset($activePrinciplesEffects[0]))
                $effects = $this->T_effects->find([
                    'column' => 'id',
                    'value'  =>  $activePrinciplesEffects[0]->getEffect_id()
                ]);
            if(isset($activePrinciplesSideEffects[0]))
                $sideEffects = $this->T_sideEffects->find([
                    'column' => 'id',
                    'value'  =>  $activePrinciplesSideEffects[0]->getSide_effect_id()
                ]);
        } catch (Exception | DBException $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }

        // preparazione dell'array associativo da restituire, contenente tutti i dati del prodotto
        if(isset($activePrinciplesEffects)) {
            $effectsList = '';
            foreach ($effects as $effect) {
                $effectsList .= ', ' . $effect->getName();
            }
        }
        if(isset($activePrinciplesSideEffects)) {
            $sideEffectsList = '';
            foreach ($sideEffects as $sideEffect) {
                $sideEffectsList .= ', ' . $sideEffect->getName();
            }
        }
        return [
            'name'                      => $products[0]->getName(),
            'description'               => $products[0]->getDescription(),
            'price'                     => $products[0]->getPrice(),
            'image'                     => $images[0]->getImg_path(),
            'category'                  => $categories[0]->getName(),
            'activeprinciple'           => $activePrinciple[0]->getName(),
            'activeprinciplepercentage' => $productsActivePrinciple[0]->getPercentage(),
            'effects'                   => $effectsList,
            'sideeffects'               => $sideEffectsList
        ];
    }
}
