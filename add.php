<?php

include_once "database.php";

$db = new Database();
$pdo=$db->getConnection();
$productInfo = $db->fetchColumns();

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
            <form name="submit">
                <button name="save" class="btn btn-left">SAVE</button>
            </form>
            <button onclick="document.location='index.php'" class="btn">CANCEL</button>
        </div>
    </header>
    <main class="product-add">
        <form name="submit">
            <div class="product-container sku-container">
                <label for="sku">SKU:</label>
                <input type="text" id="sku" class="sku" name="sku">
            </div>
            <div class="product-container name-container">
                <label for="name">Name:</label>
                <input type="text" id="name" class="name" name="name">
            </div>
            <div class="product-container price-container">
                <label for="price">Price:</label>
                <input type="text" id="price" class="price" name="price">
            </div>
            <div class="product-container type-container">
                <select name="type" id="type">
                    <optgroup label="Select type">
                    <option value="Disk">Disk</option>
                    <option value="Book">Book</option>
                    <option value="Furniture">Furniture</option>
                    </optgroup>
                </select>
            </div>
            <div class="product-container type-info-container">
                <div class="info-container">
                    <label class="lbl" for="info1">Height:</label>
                    <input type="text" id="info1" class="info" name="info1">
                </div>
                <div class="info-container">
                    <label class="lbl" for="info2">Width:</label>
                    <input type="text" id="info2" class="info" name="info2">
                </div>
                <div class="info-container">
                    <label class="lbl" for="info3">Height:</label>
                    <input type="text" id="info3" class="info" name="info3">
                </div>
                <p class="caution"><img src="icons/info.svg" alt="info"> Please provide dimensions of the furniture</p>
            </div>
        </form>
    </main>
</body>

</html>