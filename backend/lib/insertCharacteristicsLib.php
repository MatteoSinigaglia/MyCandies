<?php

require_once LIB_PATH . DS . 'functions.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ActivePrinciplesManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';

use MyCandies\Controllers\ActivePrinciplesManager;

function initInsertCharacteristics(): array {
    return [
        'DOM' => file_get_contents(VIEW_PATH . DS . "inserimentoCaratteristiche_dashboard.html"),
        'pattern' => "/<\/form>(.*?)<\/div>/s",
    ];
}

function insertActivePrinciple(String $htmlPage, String $pattern, bool $insertErrTag=false): String {
    try {
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
    } catch(Exception $e) {
        http_response_code(404);
        include(MODEL_PATH . DS . 'error404.php');
        die();
    }
    $form = '
        </form>
    </div>
    <div>
        <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
            <fieldset>
                <legend class="insertEffects">Inserimento nuovo principio attivo</legend>
                <error_overall />
                <label for="activePrincipleName">Nome:</label>
                <span><input type="text" id="activePrincipleName" name="activePrincipleName" /></span>
                <error_name />
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
    return ($insertErrTag ? $htmlPage : noFormErrors($htmlPage));
}

function insertSideEffect(String $htmlPage, String $pattern, bool $insertErrTag=false): String {
    $form = '
    </form>
    </div>
        <div>
            <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
                <fieldset>
                    <legend class="insertEffects">Inserimento nuovo effetto collaterale</legend>
                    <error_overall />
                    <label for="sideEffectName">Nome:</label>
                    <span><input type="text" id="sideEffectName" name="sideEffectName" /></span>
                    <error_name />
                    <input type="submit" id="submitSideEffect" value="Aggiungi" name="submitSideEffect" class="buttons" />
                </fieldset>
            </form>
        </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
    $htmlPage = str_replace('value="Inserisci effetto collaterale" class="buttons"', 'value="Inserisci effetto collaterale" class="selectedButton buttons"', $htmlPage);
    return ($insertErrTag ? $htmlPage : noFormErrors($htmlPage));
}

function insertEffect(String $htmlPage, String $pattern, bool $insertErrTag=false): String {
    $form = '
    </form>
    </div>
        <div>
            <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
                <fieldset>
                    <legend class="insertEffects">Inserimento nuovo effetto</legend>
                    <error_overall />
                    <label for="effectName">Nome:</label>
                    <span><input type="text" id="effectName" name="effectName" /></span>
                    <error_name />
                    <input type="submit" id="submitEffect" value="Aggiungi" name="submitEffect" class="buttons" />
                </fieldset>
            </form>
        </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
    $htmlPage = str_replace('value="Inserisci effetto" class="buttons"', 'value="Inserisci effetto" class="selectedButton buttons"', $htmlPage);
    return ($insertErrTag ? $htmlPage : noFormErrors($htmlPage));
}

function insertCategory(String $htmlPage, String $pattern, bool $insertErrTag=false): String {
    $form = '
    </form>
    </div>
        <div>
        <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
            <fieldset>
                <legend class="insertEffects">Inserimento nuova categoria</legend>
                <error_overall />
                <label for="categoryName">Nome:</label>
                <span><input type="text" id="categoryName" name="categoryName" /></span>
                <errName />
                <input type="submit" id="submitCategory" value="Aggiungi" name="submitCategory" class="buttons" />
            </fieldset>
        </form>
        </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
    $htmlPage = str_replace('value="Inserisci categoria" class="buttons"', 'value="Inserisci categoria" class="selectedButton buttons"', $htmlPage);
    return ($insertErrTag ? $htmlPage : noFormErrors($htmlPage));
}
