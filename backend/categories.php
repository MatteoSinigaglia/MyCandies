<?php
    class Category {
        private $connection;

        public function __construct($connection) {
            $this->connection = $connection;
        }

        private function getCategories() { 
            $selectQuery = "select * from Categories order by name asc";
            $queryResult = mysqli_query($this->connection, $selectQuery);    
            if(mysqli_num_rows($queryResult)==0)
                return null;
            else {
                $categories = array();
                while($row = mysqli_fetch_array($queryResult)) {
                    $category = array(
                        "id"          => $row['id'],
                        "name"        => $row['name'], 
                        "description" => $row['description']
                    );
                    array_push($categories, $category);
                }
            }
            return $categories;
        }

        public function printCategoryOptions($htmlPage, $placeholder) {
            $categories = $this->getCategories();
            $categoriesOptions = "";
            if($categories != null) {
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
            } else {
                $categoriesOptions = 
                    '<p>'
                    . 'Non esistono ancora categorie'
                    . '</p>'
                    ;
            }
            $htmlPage = str_replace($placeholder, $categoriesOptions, $htmlPage);
            return $htmlPage;
        }

        public function getId($name) {
            $categories = $this->getCategories();
            $id = null;
            $i = 0;
            while(count($categories)>$i && $categories[$i]['name'] != $name) {
                if($categories[$i]['name'] == $name)
                    $id = $category['id'];
                $i=$i+1;
            }
            return $id;
        }
    }
?>