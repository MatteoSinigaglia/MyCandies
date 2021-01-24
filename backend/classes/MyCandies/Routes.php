<?php


namespace MyCandies;

require_once __DIR__ . DS;

class Routes {

	private $dbh;

	private $TActivePrinciples;
	private $TActivePrinciplesSideEffects;
	private $TAddresses;
	private $TCarts;
	private $TCategories;
	private $TCustomers;
	private $TCustomersAddresses;
	private $TCustomersPaymentMethods;
	private $TCustomerVotes;
	private $TDiscounts;
	private $TEffects;
	private $TImages;
	private $TPaymentMethods;
	private $TProducts;
	private $TProductsActivePrinciple;
	private $TProductsImages;
	private $TProductsInCart;
	private $TReviews;
	private $TSideEffects;
	private $TTransactions;
	private $TUsers;

	public function __construct() {

		$this->dbh = new DB\dbh();

		$this->TActivePrinciples = new Table($this->dbh, '', 'id', '\MyCandies\Entity\ActivePrinciple', []);
		$this->TAddresses  = new Table($this->dbh, 'Addresses', 'id', '\MyCandies\Entity\Address');
		$this->TCustomers = new Table($this->dbh, 'Customers', 'id', '\MyCandies\Entity\Customer');
	}
}