<?php
    use DB\dbh;
    use MyCandies\Entity\Category;
    use MyCandies\Controllers\ManageCategories;

    require_once 'lib'.DIRECTORY_SEPARATOR.'paths.php';


    try {
        $categoryManager = new ManageCategories();
        $categories = $categoryManager->getCategories();
    } catch(Exception $e) {
        // load 404 page, TODO
        die();
    }
    
    $htmlPage = file_get_contents(VIEW_PATH."inserisciProdotto_dashboard.html");
    $categoriesOptions = "";
    foreach ($categories as $category) {
        if($category['name'] != null)
        $categoriesOptions .=
            '<option value="'
            . $category['name']
            . '">'
            . $category['name']
            . '</ option>'
            ;
    }
    $htmlPage = str_replace("<categoryOptions />", $categoriesOptions, $htmlPage);
    echo $htmlPage;

?>