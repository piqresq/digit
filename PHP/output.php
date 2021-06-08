<?php

include_once "product.php";
include_once "validator.php";

class Output
{

    private $db;
    public static $products_exist = true;
    private $cur_cat;

    function __construct()
    {
        $this->cur_cat = isset($_COOKIE['current_category']) ? $_COOKIE['current_category'] : 'dvd';
    }

    public function set_db($database)
    {
        $this->db = $database;
    }
    public function display_items()
    {
        $obj_amount = Product::get_id_of_first_element($this->db);
        $q = "SELECT  products.id, products.sku,products.name,products.price,categories.type, GROUP_CONCAT(categories.value ORDER BY `categories`.id) AS value
            FROM `products` INNER JOIN `categories` ON categories.product_id=products.id WHERE products.id >= $obj_amount GROUP BY products.id";
        $products =  $this->db->fetchQuery($q);
        foreach ($products as $product) {
            $class_name = $product['type'];
            $class_name::display($product);
        }
        self::$products_exist = empty($products) ? 0 : 1;
    }
    public function output_fields()
    {
        $productFields = $this->db->fetchQuery("DESCRIBE products", PDO::FETCH_COLUMN);
        foreach ($productFields as $name) {
            if (!str_contains($name, "id")) {
                $t = $name == "sku" ? strtoupper($name) : ucfirst($name);
                $cur_val = isset($_POST[$name]) ? $_POST[$name] : (isset($_COOKIE[$name]) ? $_COOKIE[$name] : '');
                echo "
                    <div class='product-container sku-container'>
                    <label for='$name'>$t:</label>
                    <input type='text' id='$name' class='$name' name='$name' value='$cur_val'>
                    </div>
                    ";
            }
        }
    }
    public function output_categories()
    {
        $categories = $this->db->fetchQuery("SELECT DISTINCT type FROM categories ");
        echo "
            <div class='product-container type-container'>
                    <select name='type' id='type' onchange='getCurrentCategory()'>
                    <optgroup label='Select type'>";
        foreach ($categories as $cat) {

            $val = $cat["type"];
            $sel = $this->cur_cat == $cat["type"] ? 'selected' : '';
            $t = $val == "dvd" ? strtoupper($val) : ucfirst($val);
            echo "<option value='$val' $sel>$t</option>";
        }
        echo "
                </optgroup>
                </select>
            </div>
            ";
    }
    public function output_category_fields()
    {
        $fields = $this->db->fetchQuery("SELECT DISTINCT category_field FROM categories WHERE type = ?", null, array($this->cur_cat));
        echo "<div class='product-container type-info-container'>";
        foreach ($fields as $id => $field) {
            $exID = $id + 1;
            $name = $field['category_field'];
            $cur_val = isset($_POST[$name]) ? $_POST[$name] : '';
            $outname = ucfirst($name);
            echo "
                <div class='info-container'>
                <label class='lbl' for=$name>$outname:</label>
                <input type='text' id = '$name' class='info' name='$name' value = '$cur_val'>
                </div>
                ";
        }
    }
    public function output_message()
    {
        $err = Validator::$error;
        $message = is_null($err) ? $this->cur_cat : '';
        echo "
            <p class='caution $message' id='caution'><img src='icons/info.svg' alt=''>$err</p>
            </div>
            ";
    }
}
