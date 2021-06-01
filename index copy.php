<?php

include_once "PHP/database.php";

$db=new Database();
$conn=$db->getConnection();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/digit.css">
    <script src="changeColor.js"></script>
    <title>digIT</title>
</head>

<body>
    <header>
        <h1 class=logo>DIG<span>IT</span></h1>
        <div class="btn-wrap">
            <button onclick="document.location='add.php'" class="btn btn-left">ADD</button>
            <form>
                <button name="delete" class="btn">DELETE</button>
            </form>
        </div>
    </header>
    <main class="container">
        <div class="card dvd" id="card-1">
            <input type="checkbox" id="field-1" class="choose" name="card-1" value=" " onclick="onClick(this.id)"
                onmouseover="hover(this.id)" onmouseout="leave(this.id)">
            <i class="icon i-dvd" id="icon-1"></i>
            <p class="SKU">JCV200123</p>
            <p class="name">Acme disk</p>
            <p class="price">2.00</p>
            <p class="memory">700MB</p>
        </div>
        <div class="card book" id="card-2">
            <input type="checkbox" id="field-2" class="choose" name="card-2" value=" " onclick="onClick(this.id)"
                onmouseover="hover(this.id)" onmouseout="leave(this.id)">
            <i class="icon i-book" id="icon-2"></i>
            <p class="SKU">JCV200123</p>
            <p class="name">Acme disk</p>
            <p class="price">2.00</p>
            <p class="weight">700MB</p>
        </div>
        <div class="card furniture" id="card-3">
            <input type="checkbox" id="field-3" class="choose" name="card-3" value=" " onclick="onClick(this.id)"
                onmouseover="hover(this.id)" onmouseout="leave(this.id)">
            <i class="icon i-furniture" id="icon-3"></i>
            <p class="SKU">JCV200123</p>
            <p class="name">Acme disk</p>
            <p class="price">2.00</p>
            <p class="dimensions">700MB</p>
        </div>
    </main>

    <script>
        let originalColor;

        function onClick(id) {
            let icon = document.getElementById("icon-" + id[id.length - 1]),
            card = document.getElementById("card-" + id[id.length - 1]);
            if (!icon.classList.contains("checkbox")) {
                icon.classList.remove("empty-checkbox");
                icon.classList.add("checkbox");
            } else {
                icon.classList.remove("checkbox");
                icon.classList.add("empty-checkbox");
            }

        }
        function hover(id) {
            let card = document.getElementById("card-" + id[id.length - 1]),
                icon = document.getElementById("icon-" + id[id.length - 1]),
                color = getComputedStyle(card).getPropertyValue("background-color");
            originalColor = color;
            card.style.backgroundColor = changeColor(-0.2, color);
            if (!icon.classList.contains("checkbox")) {
                icon.classList.remove("icon");
                icon.classList.add("empty-checkbox");
            }
        }

        function leave(id) {
            let card = document.getElementById("card-" + id[id.length - 1]),
                icon = document.getElementById("icon-" + id[id.length - 1]),
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