<?php

use MyCandies\Entities\User;
use MyCandies\Entities\Address;

function get($name, $def='') {
	return (isset($_REQUEST[$name]) ? $_REQUEST[$name] : $def);
}

function array_slice_assoc(array $array, array $keys) : array {
	return array_intersect_key($array, array_flip($keys));
}

function getProperties($class) : array {
	$ref = new ReflectionClass($class);
	$properties = array();
	foreach ($ref->getProperties() as $property) {
		array_push($properties, $property->getName());
	}
	return $properties;
}

function noSignInForm($DOM) {
	$DOM = str_replace('<error_login />', '', $DOM);
	return str_replace('_login_value', '', $DOM);;
}

function noSignUpForm($DOM) {
	require_once __DIR__.'/../classes/MyCandies/Entities/User.php';
	require_once __DIR__.'/../classes/MyCandies/Entities/Address.php';

	$items = array_merge(getProperties(User::class), getProperties(Address::class));

	foreach ($items as $item) {
		$DOM = str_replace('_' . $item . '_value', '', $DOM);
		$DOM = str_replace('<error_' . $item . ' />', '', $DOM);
	}

	return str_replace('_confirmPassword_value', '', $DOM);
}

function signupForm($DOM) {
	$DOM = str_replace('<error_login />', '', $DOM);
	$DOM = str_replace('_email_value_login', '', $DOM);


	return $DOM;
}