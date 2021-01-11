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
            }
            $htmlPage = str_replace($placeholder, $categoriesOptions, $htmlPage);
            return $htmlPage;
        }

        public function getId($name) {
            $categories = $this->getCategories();
            $id = null;
            $i = 0;
            $found = false;
            while(count($categories)>$i && !$found) {
                if($categories[$i]['name'] == $name) {
                    $id = $categories[$i]['id'];
                    $found = true;
                }
                $i=$i+1;
            }
            return $id;
        }
    }
?>