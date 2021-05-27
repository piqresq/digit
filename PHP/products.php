<?php

abstract class Product{
    protected $id, $SKU, $name, $price, $type;
    function __construct($id, $SKU, $name, $price)
    {
        $this->$id = $id;
        $this->$SKU = $SKU;
        $this->$name = $name;
        $this->$price = $price;
    }
    abstract function display();
    abstract function addToDB($db);
}

class DVD extends Product{
    private $memory;
    function __construct($id, $SKU, $name, $price, $memory)
    {
        $this->$memory=$memory;
        parent::__construct($id, $SKU, $name, $price);
    }
    function display(){
        echo"

        <div class='card dvd' id='card-$this->id'>
            <input type='checkbox' id='field-$this->id' class='choose' name='card-$this->id' onclick='onClick(this.id)'
                onmouseover='hover(this.id)' onmouseout='leave(this.id)'>
            <i class='icon i-dvd' id='icon-$this->id'></i>
            <p class='SKU'>$this->SKU</p>
            <p class='name'>$this->name</p>
            <p class='price'>$this->price</p>
            <p class='memory'>$this->memory</p>
        </div>

        ";
    }
    function addToDB($db){
        
    }
}

class Book extends Product
{
    private $weight;
    function __construct($id, $SKU, $name, $price, $weight)
    {
        $this->$weight = $weight;
        parent::__construct($id, $SKU, $name, $price);
    }
    function display()
    {
        echo "

        <div class='card book' id='card-$this->id'>
            <input type='checkbox' id='field-$this->id' class='choose' name='card-$this->id' onclick='onClick(this.id)'
                onmouseover='hover(this.id)' onmouseout='leave(this.id)'>
            <i class='icon i-book' id='icon-$this->id'></i>
            <p class='SKU'>$this->SKU</p>
            < class='name'>$this->name</p>
            <p class='price'>$this->price</p>
            <p class='weight'>$this->weight.MB</p>
        </div>

        ";
    }
}

class Furniture extends Product
{
    private $height, $width, $length;
    function __construct($id, $SKU, $name, $price, $height, $width, $length)
    {
        $this->$height = $height;
        $this->$width  = $width;
        $this->$length = $length;
        parent::__construct($id, $SKU, $name, $price);
    }
    function display()
    {
        echo "

        <div class='card furniture' id='card-$this->id'>
            <input type='checkbox' id='field-$this->id' class='choose' name='card-$this->id' onclick='onClick(this.id)'
                onmouseover='hover(this.id)' onmouseout='leave(this.id)'>
            <i class='icon i-furniture' id='icon-$this->id'></i>
            <p class='SKU'>$this->SKU</p>
            < class='name'>$this->name</p>
            <p class='price'>$this->price</p>
            <p class='memory'>$this->height x $this->width x $this->length</p>
        </div>

        ";
    }
}

?>