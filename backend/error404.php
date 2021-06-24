<?php

$htmlPage = file_get_contents(VIEW_PATH . DS . "error404.html");

$htmlPage = str_replace('<dashboard />', ($auth->isAdmin()
    ?
    '<li><a href="../backend/inserisciProdotto.php">Gestione</a></li>'
    :
    ''), $htmlPage);

echo $htmlPage;