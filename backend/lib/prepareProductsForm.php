<?php

function insertCategoriesIntoForm($categories, $DOM): String {
    $categoriesOptions = "";
    foreach ($categories as $category) {
        if ($category->getName() != null)
            $categoriesOptions .=
                "<option value=\"{$category->getName()}\">{$category->getName()}</option> \n";
    }
    return str_replace("<categoryOptions />", $categoriesOptions, $DOM);
}

function insertActivePrinciplesIntoForm($activePrinciples, $DOM): String {
    $activePrinciplesOptions = '';
    foreach ($activePrinciples as $activePrinciple) {
        if ($activePrinciple->getName() != null)
            $activePrinciplesOptions .=
                "<option value=\"{$activePrinciple->getName()}\">{$activePrinciple->getName()}</option> \n";
    }
    return str_replace("<activePrinciples />", $activePrinciplesOptions, $DOM);
}
