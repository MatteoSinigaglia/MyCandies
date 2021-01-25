<?php


namespace MyCandies\Controllers;

require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'dbh.php';
require_once MYCANDIES_PATH.DS.'Tables'.DS.'Table.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'User.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Cart.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Product.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'ProductInCart.php';

use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities;
use MyCandies\Entities\User;
use MyCandies\Entities\Cart;
use MyCandies\Entities\Product;
use MyCandies\Entities\ProductInCart;
use MyCandies\Tables\Table;


class ShopManager {

	private $user;
	private $cart;
	private $productsInCart;

	private $dbh;

	private $users;
	private $carts;
	private $products;
	private $productsInCarts;

	public function __construct() {

		$this->dbh = new dbh();
	}

	private function initUsers() {
		$this->users = new Table($this->dbh, 'Customers', 'id', User::class, [Entities\DB]);
	}

	private function initCarts() {
		$this->carts = new Table($this->dbh, 'Carts', 'id', Cart::class, [Entities\DB]);
	}

	private function initProducts() {
		$this->products = new Table($this->dbh, 'Products', 'id', Product::class, [Entities\DB]);
	}

	private function initProductsInCarts() {
		$this->productsInCarts = new Table($this->dbh, 'ProductsInCarts', 'id', ProductInCart::class, [Entities\DB]);
	}

	public function addToCart(array $product) {
		if (!isset($_SESSION['cart']))
			$_SESSION['cart']['info'] = new Cart(Entities\SHOP_MANAGER);

		if (isset($_SESSION['cart'][(int)$product['id']])) {
			$_SESSION['cart'][(int)$product['id']] += 1;
		} else {
			$_SESSION['cart'][(int)$product['id']] = 1;
		}
	}

	public function getCart() : ?array {
		return (isset($_SESSION['cart']) ? $_SESSION['cart'] : null);
	}

	public function getProducts() : ?array {
		if (!isset($_SESSION['cart']) || count($_SESSION['cart']) < 2)
//			Cart not set or empty
			return null;

		$productsInCart = $_SESSION['cart'];
		unset($productsInCart['info']);

		if (!isset($this->products))
			$this->initProducts();

		$products = array();
		try {
			$this->dbh->connect();
			foreach ($productsInCart as $id => $quantity) {
				array_push($products, $this->products->findById($id));
			}
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}

		return $products;
	}
}