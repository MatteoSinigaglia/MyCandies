<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ActivePrinciplesManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';

use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\ActivePrinciplesManager;

$auth = new Authentication();

if (!isset($_SERVER['HTTP_REFERER']) || !$auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

$htmlPage = file_get_contents(VIEW_PATH . DS . "inserimentoCaratteristiche_dashboard.html");
$pattern = "/<\/form>(.*?)<\/div>/s";

if(isset($_GET['insertActivePrinciple'])) {
    $activePrincipleManager = new ActivePrinciplesManager();
    $effects = $activePrincipleManager->loadEffects();
    $effectCheckboxes = '';
    foreach ($effects as $i) {
        $effectCheckboxes .= '<label for="'.$i->getName().'"><input type="checkbox" name="effects[]" id="'.$i->getName().'" value="'.$i->getName().'" />'.$i->getName().'</label>';
    }
    $sideEffects = $activePrincipleManager->loadSideEffects();
    $sideEffectCheckboxes = '';
    foreach ($sideEffects as $i) {
        $sideEffectCheckboxes .= '<label for="'.$i->getName().'"><input type="checkbox" name="sideEffects[]" id="'.$i->getName().'" value="'.$i->getName().'" />'.$i->getName().'</label>';
    }
    $form = '
        </form>
    </div>
    <div>
        <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
            <fieldset>
                <legend class="insertEffects">Inserimento nuovo principio attivo</legend>
                <success />
                <label for="activePrincipleName">Nome:</label>
                <span><input type="text" id="activePrincipleName" name="activePrincipleName" /></span>
                <errName />
                <fieldset class="fieldsetCharacteristics">
                    <legend class="insertEffects">Effetti del principio attivo</legend>
                    '.$effectCheckboxes.'
                </fieldset>
                <fieldset class="fieldsetCharacteristics">
                    <legend class="insertEffects">Effetti collaterali del principio attivo</legend>
                    '.$sideEffectCheckboxes.'
                </fieldset>
                <input type="submit" id="submitActivePrinciple" value="Aggiungi" name="submitActivePrinciple" class="buttons" />
            </fieldset>
        </form>
    </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
    $htmlPage = str_replace('value="Inserisci principio attivo" class="buttons"', 'value="Inserisci principio attivo" class="selectedButton buttons"', $htmlPage);
} else if(isset($_GET['insertEffect'])) {
    $form = '
   </form>
   </div>
        <div>
            <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
                <fieldset>
                    <legend class="insertEffects">Inserimento nuovo effetto</legend>
                    <success />
                    <label for="effectName">Nome:</label>
                    <span><input type="text" id="effectName" name="effectName" /></span>
                    <errName />
                    <input type="submit" id="submitEffect" value="Aggiungi" name="submitEffect" class="buttons" />
                </fieldset>
            </form>
        </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
    $htmlPage = str_replace('value="Inserisci effetto" class="buttons"', 'value="Inserisci effetto" class="selectedButton buttons"', $htmlPage);
} else if(isset($_GET['insertSideEffect'])) {
    $form = '
   </form>
    </div>
        <div>
            <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
                <fieldset>
                    <legend class="insertEffects">Inserimento nuovo effetto collaterale</legend>
                    <success />
                    <label for="sideEffectName">Nome:</label>
                    <span><input type="text" id="sideEffectName" name="sideEffectName" /></span>
                    <errName />
                    <input type="submit" id="submitSideEffect" value="Aggiungi" name="submitSideEffect" class="buttons" />
                </fieldset>
            </form>
        </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
    $htmlPage = str_replace('value="Inserisci effetto collaterale" class="buttons"', 'value="Inserisci effetto collaterale" class="selectedButton buttons"', $htmlPage);
} else if(isset($_GET['insertCategory'])) {
    $form = '
    </form>
    </div>
        <div>
        <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
            <fieldset>
                <legend class="insertEffects">Inserimento nuova categoria</legend>
                <success />
                <label for="categoryName">Nome:</label>
                <span><input type="text" id="categoryName" name="categoryName" /></span>
                <errName />
                <input type="submit" id="submitCategory" value="Aggiungi" name="submitCategory" class="buttons" />
            </fieldset>
        </form>
        </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
    $htmlPage = str_replace('value="Inserisci categoria" class="buttons"', 'value="Inserisci categoria" class="selectedButton buttons"', $htmlPage);
}

echo $htmlPage;
