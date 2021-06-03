<?php

include_once "PHP/database.php";
include_once "PHP/product.php";
$db = new Database();
$conn = $db->getConnection();

//turn this to true and it will auto create 3 objects of each type
//when visiting the add page. Also creates the objects on reload on that page ;)

Product::$auto_create_products=false;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/digit.css">
    <script src="JS/changeColor.js"></script>
    <title>digIT</title>
</head>

<body>
    <header>
        <h1 class=logo>DIG<span>IT</span></h1>
        <div class="btn-wrap">
            <button onclick="document.location='add.php'" class="btn btn-left">ADD</button>
            <button name=" delete" class="btn" form="cb-form" type="submit">DELETE</button>
        </div>
    </header>
    <main>
        <form class="container" action="" method="POST" id="cb-form">

            <?php

            //get all items from database

            $obj_amount = Product::get_id_of_first_element($conn);
            $q = "SELECT  products.id, products.sku,products.name,products.price,categories.type, GROUP_CONCAT(categories.value) AS value
            FROM `products` INNER JOIN `categories` ON categories.product_id=products.id WHERE products.id >= $obj_amount GROUP BY products.id";
            $products =  $db->executeQuery($q);


            //display all items

            foreach ($products as $product) {
                $class_name = $product['type'];
                $class_name::display($product);
            }

            //delete selected items

            $cb_id = [];

            if (isset($_POST['delete']) && count($_POST) > 1) {
                foreach ($_POST as $id => $element) {
                    if ($element === 'on') {
                        array_push($cb_id, '\'' . explode('-', $id)[1] . '\'');
                    }
                }
                $id_string = implode(', ', $cb_id);
                Product::delete_from_database($conn, $id_string);
                echo "<script>document.location='index.php';</script>";
            } else if (empty($products)) {
                echo "<div class='empty'>No items to display :(</div>";
            }

            ?>

        </form>
    </main>

    <script>

        let originalColor;

        function onClick(id) {
            let
                idnum = id.split('-')[1];
            icon = document.getElementById("icon-" + idnum),
                card = document.getElementById("card-" + idnum);
            if (!icon.classList.contains("checkbox")) {
                icon.classList.remove("empty-checkbox");
                icon.classList.add("checkbox");
            } else {
                icon.classList.remove("checkbox");
                icon.classList.add("empty-checkbox");
            }

        }

        function hover(id) {
            let idnum = id.split('-')[1];
            card = document.getElementById("card-" + idnum),
                icon = document.getElementById("icon-" + idnum),
                color = getComputedStyle(card).getPropertyValue("background-color");
            originalColor = color;
            card.style.backgroundColor = changeColor(-0.2, color);
            if (!icon.classList.contains("checkbox")) {
                icon.classList.remove("icon");
                icon.classList.add("empty-checkbox");
            }
        }

        function leave(id) {
            let
                idnum = id.split('-')[1];
            card = document.getElementById("card-" + idnum),
                icon = document.getElementById("icon-" + idnum),
                color = getComputedStyle(card).getPropertyValue("background-color");
            card.style.backgroundColor = originalColor;
            if (!icon.classList.contains("checkbox")) {
                icon.classList.remove("empty-checkbox");
                icon.classList.add("icon");
            }
        }
    </script>

</body>

</html>