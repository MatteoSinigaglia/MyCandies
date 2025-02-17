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
use MyCandies\Entities\ActivePrincipleEffect;
use MyCandies\Entities\ActivePrincipleSideEffect;
use MyCandies\Exceptions\EntityException;
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

    public function insertProduct($productData, $activePrincipleId, $percentage): bool
    {
        try {
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $product = new Product(Entities\PRODUCTS_MANAGER, $productData);
            $image = new Image(Entities\PRODUCTS_MANAGER);
            $data['product_id'] = $this->T_products->insert($product);
            $data['img_id'] = $this->T_images->insert($image);
            $productImage = new ProductImage(Entities\PRODUCTS_MANAGER, $data);
            $this->T_productsImages->insert($productImage);
            $data_products_active_principles['product_id'] = $data['product_id'];
            $data_products_active_principles['active_principle_id'] = $activePrincipleId;
            $data_products_active_principles['percentage'] = $percentage;
            $productsActivePrinciples = new ProductsActivePrinciple(Entities\PRODUCTS_MANAGER, $data_products_active_principles);
            $this->T_productsActivePrinciples->insert($productsActivePrinciples);
            $this->uploadImage();
            $this->dbh->transactionCommit();
        } catch (EntityException | Exception $e) {
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

    public function modifyProduct($data) : bool {
        try{
            $productId = $this->getProductByName($data['name'])->getId();
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $this->T_products->update([
                'id'           => $productId,
                'price'        => $data['price'],
                'availability' => $data['availability']
            ]);
            $this->dbh->transactionCommit();
        } catch(Exception $e) {
            $this->dbh->transactionRollback();
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return true;
    }

    public function removeProduct($name) : bool
    {
        try {
            $product = $this->getProductByName($name);
            $this->dbh->connect();
            $images = $this->T_productsImages->find([
                    'column' => 'product_id',
                    'value'  => $product->getId()
            ]);
            $image_id = $images[0]->getImg_id();
            $this->dbh->transactionStart();
            $this->T_products->delete($product->getId());
            $img_path = $this->T_images->find([
                'column' => 'id',
                'value'  => $image_id
            ])[0]->getImg_path();
            $this->deleteImage($img_path);
            $this->T_images->delete($image_id);
            $this->dbh->transactionCommit();
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }

    private function deleteImage($img_path) {
        if(realpath($img_path) != false && is_writable($img_path)){
            unlink($img_path);
        } else throw new Exception('Non è stato possibile eliminare l\'immagine');
    }

    public function getProducts(bool $isForDashboard = true) : array
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
        $rows = array();
        foreach ($products as $product) {
            if($product->getAvailability() > 0 || $isForDashboard) {
                array_push($rows, $product);
            }
        }
        return $this->prepareProductsForList($rows, $productsImages, $images);
    }

    public function searchProduct($pattern) : array {
        $products = array();
        try{
            $this->dbh->connect();
            $products = $this->T_products->searchPattern('name', '%'.$pattern.'%');
            if(empty($products)) {
                throw new EntityException([], 0, "Non sono stati trovati prodotti");
            }
            $images = $this->T_images->find();
            $productsImages = $this->T_productsImages->find();
        } catch(EntityException | Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $this->prepareProductsForList($products, $productsImages, $images);
    }

    public function getProductByName($name) {
        $products = array();
        try{
            $this->dbh->connect();
            $products = $this->T_products->find([
                'column' => 'name',
                'value'  => $name
            ]);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return (empty($products) ? null : $products[0]);
    }

    public function getSingleProduct($id) : array {
        try{
            $this->dbh->connect();
            $products = $this->T_products->find([
                'column' => 'id',
                'value'  => $id
            ]);
            $categories = $this->T_categories->find([
                'column' => 'id',
                'value'  => $products[0]->getCategory_id()
            ]);
            $productsImages = $this->T_productsImages->find([
                'column' => 'product_id',
                'value'  => $id
            ]);
            $images = $this->T_images->find([
                'column' => 'id',
                'value'  => $productsImages[0]->getImg_id()
            ]);
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
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }

        return [
            'name'                      => $products[0]->getName(),
            'description'               => $products[0]->getDescription(),
            'price'                     => $products[0]->getPrice(),
            'image'                     => $images[0]->getImg_path(),
            'category'                  => $categories[0]->getName(),
            'activeprinciple'           => $activePrinciple[0]->getName(),
            'activeprinciplepercentage' => $productsActivePrinciple[0]->getPercentage(),
            'effects'                   => (isset($effects) ? implode(',', $effects) : ''),
            'sideeffects'               => (isset($sideEffects) ? implode(',', $sideEffects) : '')
        ];
    }

	public function getProductById($id) {

    	try{
			$this->dbh->connect();
			$product = $this->T_products->findById($id);
		} catch (Exception $e) {
			throw $e;
		} finally {
			$this->dbh->disconnect();
		}
		return ($product ?? null);
	}

	public function findProductsByCategory($category_id) : array {
        try{
            $this->dbh->connect();
            $products = $this->T_products->find([
                'column' => 'category_id',
                'value'  => $category_id
            ]);
            $images = $this->T_images->find();
            $productsImages = $this->T_productsImages->find();
        } catch (DBException $e) {
            throw new Exception('Impossibile connettersi al database');
        } finally {
            $this->dbh->disconnect();
        }
        return $this->prepareProductsForList($products, $productsImages, $images);
    }

    private function prepareProductsForList($products, $productsImages, $images) : array {
        $rows=[];
        foreach($products as $product) {
            $rows[$product->getId()] = [
                'id'           => $product->getId(),
                'name'         => $product->getName(),
                'price'        => $product->getPrice(),
                'availability' => $product->getAvailability()
            ];
        }
        foreach($productsImages as $productImage) {
            if(isset($rows[$productImage->getProduct_id()]))
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
}