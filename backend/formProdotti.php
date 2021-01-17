<?php
    // TODO eliminare definizione costanti

    require_once '..'.DIRECTORY_SEPARATOR.'paths_index.php';
    require_once MYCANDIES_PATH.DS.'Entities'.DS.'Category.php';
    require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ManageCategories.php';

    use MyCandies\Controllers\ManageCategories;

    try {
        $categoryManager = new ManageCategories();
        $categories = $categoryManager->getCategories();
    } catch(Exception $e) {
        // load 404 page, TODO
        echo $e->getMessage();
        die();
    }
    
    $htmlPage = file_get_contents(VIEW_PATH.DS."inserisciProdotto_dashboard.html");
    $categoriesOptions = "";

    foreach ($categories as $category) {
        if($category->getName() != null)
        $categoriesOptions .=
            '<option value="'
            . $category->getName()
            . '">'
            . $category->getName()
            . '</ option>'
            ;
    }
    $htmlPage = str_replace("<categoryOptions />", $categoriesOptions, $htmlPage);
    echo $htmlPage;
