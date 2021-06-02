<?php

abstract class Product{

    protected $SKU, $name, $price;
    protected static $product_amount = 3;
    public static $auto_create_products = false;

    function __construct($data)
    {
        $this->SKU = $data[0];
        $this->name = $data[1];
        $this->price = $data[2];
    }

    static function get_id_of_first_element($conn){
        $q= "SELECT MIN(id) AS id FROM `products`";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        return $stmt->fetchAll()[0]["id"] + self::$product_amount;
    }

    public static function delete_from_database($conn, $idx){
        $q = "DELETE FROM `categories` WHERE product_id IN ($idx)";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        $q = "DELETE FROM `products` WHERE id IN ($idx)";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        
    }

    abstract function add_to_database($conn);

    abstract static function display($data);

    abstract static function create_default($conn);
}

class dvd extends Product{
    private $memory;
    private static $field_name ='memory';
    private static $cat_name = 'dvd';
    function __construct($data)
    {
        $this->memory=$data[3];
        parent::__construct($data);
    }
    function add_to_database($conn)
    {
        $q="INSERT INTO products (`sku`,`name`,`price`) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($q);
        $stmt->execute([$this->SKU,$this->name,$this->price]);
        $id = $conn->lastInsertId();
        $q="INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES (?,?,?,?)";
        $stmt= $conn->prepare($q);
        $stmt->execute([self::$cat_name,self::$field_name,$this->memory,$id]);
    }
    static function create_default($conn)
    {
        $cat = self::$cat_name;
        $field = self::$field_name;
        $find = $conn->prepare("SELECT name FROM products WHERE name='def_$cat'");
        $find->execute();
        $res = $find->fetchAll();
        if (empty($res)||self::$auto_create_products==true) {
            $q = "INSERT INTO products (`sku`,`name`,`price`) VALUES ('def','def_$cat',0)";
            $stmt = $conn->prepare($q);
            $stmt->execute();
            $id = $conn->lastInsertId();
            $q = "INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES ('$cat','$field','0','$id')";
            $stmt = $conn->prepare($q);
            $stmt->execute();
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
    static function create_default($conn)
    {
        $cat = self::$cat_name;
        $field = self::$field_name;
        $find = $conn->prepare("SELECT name FROM products WHERE name='def_$cat'");
        $find->execute();
        $res = $find->fetchAll();
        if (empty($res) || self::$auto_create_products == true) {
            $q = "INSERT INTO products (`sku`,`name`,`price`) VALUES ('def','def_$cat',0)";
            $stmt = $conn->prepare($q);
            $stmt->execute();
            $id = $conn->lastInsertId();
            $q = "INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES ('$cat','$field','0','$id')";
            $stmt = $conn->prepare($q);
            $stmt->execute();
        }
    }
    function add_to_database($conn)
    {
        $q = "INSERT INTO products (`sku`,`name`,`price`) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($q);
        $stmt->execute([$this->SKU, $this->name, $this->price]);
        $id = $conn->lastInsertId();
        $q = "INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES (?,?,?,?)";
        $stmt = $conn->prepare($q);
        $stmt->execute([self::$cat_name, self::$field_name, $this->weight, $id]);
    }
    static function display($data)
    {
        $cat = self::$cat_name;
        $field = self::$field_name;
        $id = $data['id'];
        $sku = $data['sku'];
        $name = $data['name'];
        $price = $data['price'];
        $price = !strpos($price, '.')?$price.'.00':(strlen(explode('.',$price)[1])==1?$price.'0':$price);
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
    static function create_default($conn)
    {
        $cat = self::$cat_name;
        $find = $conn->prepare("SELECT name FROM products WHERE name='def_$cat'");
        $find->execute();
        $res = $find->fetchAll();
        if (empty($res) || self::$auto_create_products == true) {
            $q = "INSERT INTO products (`sku`,`name`,`price`) VALUES ('def','def_$cat',0)";
            $stmt = $conn->prepare($q);
            $stmt->execute();
            $id = $conn->lastInsertId();
            $values = array(
                array($cat, self::$field_name_1, 0, $id),
                array($cat, self::$field_name_2, 0, $id),
                array($cat, self::$field_name_3, 0, $id)
            );
            $q = "INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES (?,?,?,?)";
            $stmt = $conn->prepare($q);
            foreach($values as $row)
                $stmt->execute($row);
        }
    }
    function add_to_database($conn)
    {
        $q = "INSERT INTO products (`sku`,`name`,`price`) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($q);
        $stmt->execute([$this->SKU, $this->name, $this->price]);
        $id = $conn->lastInsertId();
        $values = array(array(self::$cat_name, self::$field_name_1, $this->height, $id),
                        array(self::$cat_name, self::$field_name_2, $this->width, $id), 
                        array(self::$cat_name, self::$field_name_3, $this->length, $id));
        $q = "INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES (?,?,?,?)";
        $stmt = $conn->prepare($q);
        foreach($values as $row){
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
        list($height,$width,$length)=explode(',',$data['value']);
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

?>