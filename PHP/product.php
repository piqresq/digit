<?php

abstract class Product{

    protected $SKU, $name, $price;
    public static $product_amount = 3;
    function __construct($data)
    {
        $this->SKU = $data[0];
        $this->name = $data[1];
        $this->price = $data[2];
    }
    // static function display()
    // {
    //     echo "

    //     <div class='card dvd' id='card-$this->id'>
    //         <input type='checkbox' id='field-$this->id' class='choose' name='card-$this->id' onclick='onClick(this.id)'
    //             onmouseover='hover(this.id)' onmouseout='leave(this.id)'>
    //         <i class='icon i-dvd' id='icon-$this->id'></i>
    //         <p class='SKU'>$this->SKU</p>
    //         <p class='name'>$this->name</p>
    //         <p class='price'>$this->price</p>
    //         <p class='memory'>$this->memory</p>
    //     </div>

    //     ";
    // }
    abstract function add_to_database($conn);
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
        $stmt->execute([$this->cat_name,$this->field_name,$this->memory,$id]);
    }
    static function create_default($conn)
    {
        $find = $conn->prepare("SELECT name FROM products WHERE name='def_dvd'");
        $find->execute();
        $res = $find->fetchAll();
        if (empty($res)) {
            $q = "INSERT INTO products (`sku`,`name`,`price`) VALUES ('def','def_dvd',0)";
            $stmt = $conn->prepare($q);
            $stmt->execute();
            $id = $conn->lastInsertId();
            $cat = self::$cat_name;
            $field = self::$field_name;
            $q = "INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES ('$cat','$field','0','$id')";
            $stmt = $conn->prepare($q);
            $stmt->execute();
        }
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
        $find = $conn->prepare("SELECT name FROM products WHERE name='def_book'");
        $find->execute();
        $res = $find->fetchAll();
        if (empty($res)) {
            $q = "INSERT INTO products (`sku`,`name`,`price`) VALUES ('def','def_book',0)";
            $stmt = $conn->prepare($q);
            $stmt->execute();
            $id = $conn->lastInsertId();
            $cat=self::$cat_name; $field = self::$field_name;
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
        $stmt->execute([$this->cat_name, $this->field_name, $this->weight, $id]);
    }
}

class furniture extends Product
{
    private $height, $width, $length;
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
        $find = $conn->prepare("SELECT name FROM products WHERE name='def_furniture'");
        $find->execute();
        $res = $find->fetchAll();
        if (empty($res)) {
            $q = "INSERT INTO products (`sku`,`name`,`price`) VALUES ('def','def_furniture',0)";
            $stmt = $conn->prepare($q);
            $stmt->execute();
            $id = $conn->lastInsertId();
            $values = array(
                array(self::$cat_name, self::$field_name_1, 0, $id),
                array(self::$cat_name, self::$field_name_2, 0, $id),
                array(self::$cat_name, self::$field_name_3, 0, $id)
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
        $values = array(array($this->cat_name, $this->field_name_1, $this->height, $id),
                        array($this->cat_name, $this->field_name_2, $this->width, $id), 
                        array($this->cat_name, $this->field_name_3, $this->length, $id));
        $q = "INSERT INTO categories (`type`,`category_field`,`value`,`product_id`) VALUES (?,?,?,?)";
        $stmt = $conn->prepare($q);
        foreach($values as $row){
            $stmt->execute($row);
        }
    }
}

?>