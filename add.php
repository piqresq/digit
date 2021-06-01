<?php

include_once "PHP/database.php";
include_once "PHP/validator.php";
include_once "PHP/product.php";

//get connection to database

$db = new Database();
$pdo = $db->getConnection();
$error=null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/digit.css">
    <title>digIT</title>
</head>

<body>
    <header>
        <h1 class=logo>DIG<span>IT</span></h1>
        <div class="btn-wrap">
            <button name='save' class='btn btn-left' type='submit' form='add'>SAVE</button>
            <button onclick="document.location='index.php'" class="btn">CANCEL</button>
        </div>
    </header>
    <main class="product-add">
        <form id="add" action="" method="POST">

            <?php

            //output product input fields

            $productFields = $db->executeQuery("DESCRIBE products", PDO::FETCH_COLUMN);
            foreach ($productFields as $name) {
                if (!str_contains($name, "id")) {
                    $t = $name == "sku" ? strtoupper($name) : ucfirst($name);
                    $cur_val = isset($_POST[$name])?$_POST[$name]:'';
                    echo "
                    <div class='product-container sku-container'>
                    <label for='$name'>$t:</label>
                    <input type='text' id='$name' class='$name' name='$name' value='$cur_val'>
                    </div>
                    ";
                }
            }

            //create default objects if they don't already exist for creating the form

            dvd::create_default($pdo);
            book::create_default($pdo);
            furniture::create_default($pdo);

            //output select with all categories

            $categories = $db->executeQuery("SELECT DISTINCT type FROM categories ");
            $curr_cat = isset($_COOKIE['current_category']) ? $_COOKIE['current_category'] : 1;
            echo "
            <div class='product-container type-container'>
                    <select name='type' id='type' onchange='getCurrentCategory()'>
                    <optgroup label='Select type'>";
            foreach ($categories as $cat) {

                $val = $cat["type"];
                $sel = $curr_cat == $cat["type"] ? 'selected' : '';
                $t = $val == "dvd" ? strtoupper($val) : ucfirst($val);
                echo "<option value='$val' $sel>$t</option>";
            }
            echo "
                </optgroup>
                </select>
            </div>
            ";

            //output all fields for the selected category

            $fields = $db->executeQuery("SELECT DISTINCT category_field FROM categories WHERE type = ?", null, $curr_cat);
            echo "<div class='product-container type-info-container'>";
            foreach ($fields as $id => $field) {
                $exID = $id + 1;
                $name = $field['category_field'];
                $cur_val = isset($_POST[$name])?$_POST[$name]:'';
                $outname = ucfirst($name);
                echo "
                <div class='info-container'>
                <label class='lbl' for=$name>$outname:</label>
                <input type='text' id = '$name' class='info' name='$name' value = '$cur_val'>
                </div>
                ";
            }

            //validate input

            if (isset($_POST['save'])) {
                $uPost =$_POST;
                unset($uPost['save']);
                unset($uPost['type']);
                $validator = new Validator($uPost);
                $error = $validator->validate();

                //add to database

                if($error==null){
                    $_POST=array();
                    $product = new $curr_cat(array_values($uPost));
                    $product->add_to_database($pdo);
                    $error="Product added";
                    header("Location: index.php");
                }
            }
            $message = is_null($error)?$curr_cat:'';

            //output info box

            echo "
            <p class='caution $message'><img src='icons/info.svg' alt=''>$error</p>
            </div>
            ";

            ?>
        </form>
    </main>
    <script>

        function getCurrentCategory() {
            const selected = document.getElementById('type').value;
            document.cookie = `current_category=${selected}`;
            location.reload();
        }

    </script>
</body>

</html>