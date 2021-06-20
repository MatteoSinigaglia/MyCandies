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
	'email'         =>  '<td scope="row" title="E-mail"><user_email /></td>',
	'first_name'    =>  '<td scope="row" title="Nome"><user_first_name /></td>',
    'last_name'     =>  '<td scope="row" title="Cognome"><user_last_name /></td>',
    'birthdate'     =>  '<td scope="row" title="Data nascita"><user_birthdate /></td>',
	'gender'        =>  '<td scope="row" title="Sesso"><user_gender /></td>',
	'actions'       =>  '<td scope="row" title="Azioni">
<a href="./remove_user.php?email=_user_email" name="remove_user"><button class="buttons">Rimuovi</button></a>
<make_admin />
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
	$userRow = str_replace('<make_admin />', '<a href="./make_user_admin.php?email=_user_email" name="make_admin"><button class="buttons">Rendi admin</button></a>', $userRow);
	$userRow = str_replace('_user_email', urlencode($userData['email']), $userRow);
	$usersData .= '<tr>'.$userRow.'</tr>';
}

$DOM = str_replace('<users />', $usersData, $DOM);

if (isset($_SESSION['log'])) {

    $class = (isset($_SESSION['logtype']) && $_SESSION['logtype'] === 'success' ? 'formSuccess' : 'formErrors');
    $statusLog = "
		<div>
			<strong class='{$class}'>{$_SESSION['log']}</strong>
		</div>";
    unset($_SESSION['log']);
    unset($_SESSION['logtype']);
} else {
    $statusLog = '';
}

$DOM = str_replace('<status-log />', $statusLog, $DOM);

echo $DOM;