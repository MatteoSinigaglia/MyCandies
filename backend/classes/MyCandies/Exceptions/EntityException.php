<?php


namespace MyCandies\Exceptions;

/**
 * Class EntityException
 * @package MyCandies\Exceptions
 */

/*
 * Codici di errore:
 *  -1  ->  id non intero
 *	-2  ->  id già assegnato
 *	-3  ->  first_name non corretto (nullo o non rispetta regex)
 *	-4  ->  last_name non corretto (nullo o non rispetta regex)
 *	-5  ->  email non corretta (nullo o non rispetta regex)
 *	-6  ->  password non corretta (nullo o non rispetta regex)
 *	-7  ->  password e confirmPassword non corrispondono
 *	-8  ->  data non corretta (nullo o non rispetta regex)
 *	-9  ->  utente minorenne
 *	-10 ->  cellulare non corretto (nullo o non rispetta regex)
 */

class EntityException extends \Exception {

}