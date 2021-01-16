<?php

function get($name, $def='') {
	return (isset($_REQUEST[$name]) ? $_REQUEST[$name] : $def);
}

function array_slice_assoc(array $array, array $keys) : array {
	return array_intersect_key($array, array_flip($keys));
}