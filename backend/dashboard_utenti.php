<?php

use MyCandies\Controllers\Administration;
use MyCandies\Controllers\Authentication;
use MyCandies\Entities\User;

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';
$auth = new Authentication();

if (!isset($_SERVER['HTTP_REFERER']) || !$auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

$DOM = file_get_contents('../frontend/utenti_dashboard.html');

require_once __DIR__.'/classes/MyCandies/Controllers/Administration.php';
$users = (new Administration())->getUsers();

$usersRow = [
	'email'         =>  '<td headers="mail" title="mail" scope="row"><user_email /></td>',
	'first_name'    =>  '<td headers="name" title="Nome" scope="row"><user_first_name /></td>',
    'last_name'     =>  '<td headers="surname" title="Cognome" scope="row"><user_last_name /></td>',
//    'password'      =>  '<td headers="password" title="Password" scope="row"></td>',
    'birthdate'     =>  '<td headers="dateOfBirth" title="Data di nascita" scope="row"><user_birthdate /></td>',
	'sex'           =>  '<td headers="sex" title="Sesso" scope="row"><user_sex /></td>',
	'actions'       =>  '<td headers="actions" title="Azioni" scope="row">
<a href="./remove_user.php?email=_user_email" name="remove_user"><button class="buttons">Rimuovi</button></a>
<a href="./make_user_admin.php?email=_user_email" name="make_user_admin-"><button class="buttons">Rendi admin</button></a>
</td>'
	];
$usersData = '';

require_once __DIR__.'/lib/functions.php';

foreach ($users as $user) {
	$userData = $user->getValues();
	$userRow = '';
	foreach (array_keys($usersRow) as $property) {
		$userRow .= str_replace('<user_'.$property.' />', $userData[$property], $usersRow[$property]);
	}
	$userRow = str_replace('_user_email', urlencode($userData['email']), $userRow);
	$usersData .= '<tr>'.$userRow.'</tr>';
}

$DOM = str_replace('<users />', $usersData, $DOM);

echo $DOM;