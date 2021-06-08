<?php

include_once "validator.php";
include_once "output.php";

abstract class Product
{

    protected $SKU, $name, $price;
    protected static $product_amount = 3;
    public static $auto_create_products = false;

    function __construct($data)
    {
        $this->SKU = $data[0];
        $this->name = $data[1];
        $this->price = $data[2];
    }

    static function create_defaults($db)
    {
        dvd::create_default($db);
        book::create_default($db);
        furniture::create_default($db);
    }

    static function get_data()
    {
        $uPost = $_POST;
        unset($uPost['save']);
        unset($uPost['type']);
        return $uPost;
    }
    static function get_id_of_first_element($db)
    {
        return $db->fetchQuery("SELECT MIN(id) AS id FROM `products`")[0]["id"] + self::$product_amount;
    }

    public static function delete_from_database($db)
    {
        $cb_id = [];

        if (isset($_POST['delete']) && count($_POST) > 1) {
            foreach ($_POST as $id => $element) {
                if ($element === 'on') {
                    array_push($cb_id, '\'' . explode('-', $id)[1] . '\'');
                }
            }
            $id_string = implode(', ', $cb_id);
            $db->executeQuery("DELETE FROM `categories` WHERE product_id IN ($id_string)");
            $db->executeQuery("DELETE FROM `products` WHERE id IN ($id_string)");
            echo "<script>document.location='index.php';</script>";
        } else if (!Output::$products_exist) {
            echo "<div class='empty'>No items to display :(</div>";
        }
    }
    public static function create_product($db)
    {
        $validator = new Validator();
        $validator->validate();
        if (Validator::$error === null && isset($_POST['save'])) {
            $category = isset($_COOKIE['current_category']) ? $_COOKIE['current_category'] : 'dvd';
            $product = new $category(array_values(self::get_data()));
            $product->add_to_database($db);
            $_POST = array();
            foreach ($_COOKIE as $key => $cookie)
                setcookie($key, '', time() - 3600);
            Validator::$error = "Product added!";
            echo "<script>document.location = 'index.php'</script>";
        }
    }

    abstract function add_to_database($db);

    abstract static function display($data);

    abstract static function create_default($db);
}

class dvd extends Product
{
    private $memory;
    private static $field_name = 'memory';
    private static $cat_name = 'dvd';
    function __construct($data)
    {
        $this->memory = $data[3];
        parent::__construct($data);
    }
    function add_to_database($db)
    {
        $db->executeQuery("INSERT INTO products (`sku`,`name`,`price`) VALUES (?, ?, ?)", array($this->SKU, $this->name, $this->price));
        $id = $db->pdo->lastInsertId();
        $db->executeQuery("INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES (?,?,?,?)", array(self::$cat_name, self::$field_name, $this->memory, $id));
    }
    static function create_default($db)
    {

        $cat = self::$cat_name;
        $field = self::$field_name;
        $res = $db->fetchQuery("SELECT name FROM products WHERE name='def_$cat'");
        if (empty($res) || self::$auto_create_products == true) {
            $db->executeQuery("INSERT INTO products (`sku`,`name`,`price`) VALUES ('def','def_$cat',0)");
            $id = $db->pdo->lastInsertId();
            $db->executeQuery("INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES ('$cat','$field','0','$id')");
        }
    }
    static function display($data)
    {
        $cat = self::$cat_name;
        $field = self::$field_name;
        $id = $data['id'];
        $sku = $data['sku'];
        $name = $data['name'];
        $price = $data['price'];
        $price = !strpos($price, '.') ? $price . '.00' : (strlen(explode('.', $price)[1]) == 1 ? $price . '0' : $price);
        $memory = $data['value'];
        echo "
        <div class='card $cat' id='card-$id'>
            <input type='checkbox' id='field-$id' class='choose' name='card-$id' onclick='onClick(this.id)'
                onmouseover='hover(this.id)' onmouseout='leave(this.id)'>
            <i class='icon i-$cat' id='icon-$id'></i>
            <p class='SKU'>$sku</p>
            <p class='name'>$name</p>
            <p class='price'>$price</p>
            <p class='$field'>$memory MB</p>
        </div>
        ";
    }
}

class book extends Product
{
    private $weight;
    private static $field_name = 'weight';
    private static $cat_name = 'book';
    function __construct($data)
    {
        $this->weight = $data[3];
        parent::__construct($data);
    }
    static function create_default($db)
    {
        $cat = self::$cat_name;
        $field = self::$field_name;
        $res = $db->fetchQuery("SELECT name FROM products WHERE name='def_$cat'");
        if (empty($res) || self::$auto_create_products == true) {
            $db->executeQuery("INSERT INTO products (`sku`,`name`,`price`) VALUES ('def','def_$cat',0)");
            $id = $db->pdo->lastInsertId();
            $db->executeQuery("INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES ('$cat','$field','0','$id')");
        }
    }
    function add_to_database($db)
    {
        $db->executeQuery("INSERT INTO products (`sku`,`name`,`price`) VALUES (?, ?, ?)", [$this->SKU, $this->name, $this->price]);
        $id = $db->pdo->lastInsertId();
        $db->executeQuery("INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES (?,?,?,?)", [self::$cat_name, self::$field_name, $this->weight, $id]);
    }
    static function display($data)
    {
        $cat = self::$cat_name;
        $field = self::$field_name;
        $id = $data['id'];
        $sku = $data['sku'];
        $name = $data['name'];
        $price = $data['price'];
        $price = !strpos($price, '.') ? $price . '.00' : (strlen(explode('.', $price)[1]) == 1 ? $price . '0' : $price);
        $weight = $data['value'];
        echo "
        <div class='card $cat' id='card-$id'>
            <input type='checkbox' id='field-$id' class='choose' name='card-$id' onclick='onClick(this.id)'
                onmouseover='hover(this.id)' onmouseout='leave(this.id)'>
            <i class='icon i-$cat' id='icon-$id'></i>
            <p class='SKU'>$sku</p>
            <p class='name'>$name</p>
            <p class='price'>$price</p>
            <p class='$field'>$weight KG</p>
        </div>
        ";
    }
}

class furniture extends Product
{
    private $height, $width, $length;
    private static $field_key = 'dimensions';
    private static $field_name_1 = 'height';
    private static $field_name_2 = 'width';
    private static $field_name_3 = 'length';
    private static $cat_name = 'furniture';

    function __construct($data)
    {

        $this->height = $data[3];
        $this->width = $data[4];
        $this->length = $data[5];
        parent::__construct($data);
    }
    static function create_default($db)
    {
        $cat = self::$cat_name;
        $res = $db->fetchQuery("SELECT name FROM products WHERE name='def_$cat'");
        if (empty($res) || self::$auto_create_products == true) {
            $db->executeQuery("INSERT INTO products (`sku`,`name`,`price`) VALUES ('def','def_$cat',0)");
            $id = $db->pdo->lastInsertId();
            $values = array(
                array($cat, self::$field_name_1, 0, $id),
                array($cat, self::$field_name_2, 0, $id),
                array($cat, self::$field_name_3, 0, $id)
            );
            $q = "INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES (?,?,?,?)";
            $stmt = $db->pdo->prepare($q);
            foreach ($values as $row)
                $stmt->execute($row);
        }
    }
    function add_to_database($db)
    {
        $db->executeQuery("INSERT INTO products (`sku`,`name`,`price`) VALUES (?, ?, ?)", [$this->SKU, $this->name, $this->price]);
        $id = $db->pdo->lastInsertId();
        $values = array(
            array(self::$cat_name, self::$field_name_1, $this->height, $id),
            array(self::$cat_name, self::$field_name_2, $this->width, $id),
            array(self::$cat_name, self::$field_name_3, $this->length, $id)
        );
        $q = "INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES (?,?,?,?)";
        $stmt = $db->pdo->prepare($q);
        foreach ($values as $row) {
            $stmt->execute($row);
        }
    }
    static function display($data)
    {
        $cat = self::$cat_name;
        $field = self::$field_key;
        $id = $data['id'];
        $sku = $data['sku'];
        $name = $data['name'];
        $price = $data['price'];
        $price = !strpos($price, '.') ? $price . '.00' : (strlen(explode('.', $price)[1]) == 1 ? $price . '0' : $price);
        list($height, $width, $length) = explode(',', $data['value']);
        echo "
        <div class='card $cat' id='card-$id'>
            <input type='checkbox' id='field-$id' class='choose' name='card-$id' onclick='onClick(this.id)'
                onmouseover='hover(this.id)' onmouseout='leave(this.id)'>
            <i class='icon i-$cat' id='icon-$id'></i>
            <p class='SKU'>$sku</p>
            <p class='name'>$name</p>
            <p class='price'>$price</p>
            <p class='$field'>$height x $width x $length CM</p>
        </div>
        ";
    }
}
