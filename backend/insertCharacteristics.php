<?php

// TODO rimuovere inclusione paths_index
require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ActivePrinciplesManager.php';

use MyCandies\Controllers\ActivePrinciplesManager;

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
                <legend>Inserimento nuovo principio attivo</legend>
                <success />
                <label for="activePrincipleName">Nome:</label>
                <span><input type="text" id="activePrincipleName" name="activePrincipleName" /></span>
                <errName />
                <fieldset>
                    <legend>Effetti del principio attivo</legend>
                    '.$effectCheckboxes.'
                </fieldset>
                <fieldset>
                    <legend>Effetti collaterali del principio attivo</legend>
                    '.$sideEffectCheckboxes.'
                </fieldset>
                <input type="submit" id="submitActivePrinciple" value="Aggiungi" name="submitActivePrinciple" class="buttons" />
            </fieldset>
        </form>
    </div>';

    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
} else if(isset($_GET['insertEffect'])) {
    $form = '
   </form>
   </div>
        <div>
            <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
                <fieldset>
                    <legend>Inserimento nuovo effetto</legend>
                    <success />
                    <label for="effectName">Nome:</label>
                    <span><input type="text" id="effectName" name="effectName" /></span>
                    <errName />
                    <input type="submit" id="submitEffect" value="Aggiungi" name="submitEffect" class="buttons" />
                </fieldset>
            </form>
        </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
} else if(isset($_GET['insertSideEffect'])) {
    $form = '
   </form>
    </div>
        <div>
            <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
                <fieldset>
                    <legend>Inserimento nuovo effetto collaterale</legend>
                    <success />
                    <label for="sideEffectName">Nome:</label>
                    <span><input type="text" id="sideEffectName" name="sideEffectName" /></span>
                    <errName />
                    <input type="submit" id="submitSideEffect" value="Aggiungi" name="submitSideEffect" class="buttons" />
                </fieldset>
            </form>
        </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
} else if(isset($_GET['insertCategory'])) {
    $form = '
    </form>
    </div>
        <div>
        <form action="../backend/retrieveCharacteristic.php" method="post" class="inputForm">
            <fieldset>
                <legend>Inserimento nuova categoria</legend>
                <success />
                <label for="categoryName">Nome:</label>
                <span><input type="text" id="categoryName" name="categoryName" /></span>
                <errName />
                <input type="submit" id="submitCategory" value="Aggiungi" name="submitCategory" class="buttons" />
            </fieldset>
        </form>
        </div>';
    $htmlPage = preg_replace($pattern, $form, $htmlPage, 1);
}

echo $htmlPage;