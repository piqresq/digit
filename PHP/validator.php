<?php

class Validator
{
    private $data = [];
    public static $error = null;
    public static $types = [
        'sku' => 'alphanum', 'name' => 'alpha', 'price' => 'decimal',
        'memory' => 'num', 'weight' => 'decimal', 'height' => 'decimal',
        'width' => 'decimal', 'length' => 'decimal'
    ];

    function __construct()
    {
        if (isset($_POST['save'])) {
            $data = Product::get_data();
            foreach ($data as $key => $value) {
                $val = trim(strip_tags(htmlspecialchars($value)));
                $this->data[$key] = $val;
            }
        }
    }

    function validate()
    {

        if (count($this->data) > 0) {
            foreach ($this->data as $key => $field) {
                if (!isset($this->data[$key]) || empty($this->data[$key])) {
                    self::$error = "Please, submit required data";
                }
            }
            if (self::$error === null)
                foreach ($this->data as $key => $field) {
                    if (array_key_exists($key, self::$types)) {
                        $func = 'val_' . self::$types[$key];
                        if (!$this->$func($field)) {
                            self::$error = "Incorrect data inputed for field " . ($key == 'sku' ? strtoupper($key) : $key);
                            break;
                        }
                    }
                }
        }
    }
    private function val_alphanum($field)
    {
        $reg = "/^[a-zA-Z0-9]{3,18}$/";
        return preg_match($reg, $field);
    }
    private function val_alpha($field)
    {
        $reg = "/^[a-zA-Z ]{3,24}$/";
        return preg_match($reg, $field);
    }
    private function val_num($field)
    {
        $reg = "/^([1-9][0-9]*|0){1,12}$/";
        return preg_match($reg, $field);
    }
    private function val_decimal($field)
    {
        $reg = "/^([1-9][0-9]*|0){1,12}(\.[0-9]{0,2})?$/";
        return preg_match($reg, $field);
    }
}
