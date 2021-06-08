<?php

include_once "PHP/database.php";
$db = new Database();
$db->getConnection();
include_once "PHP/output.php";
$out = new Output();
$out->set_db($db);

include_once "PHP/head.php";

?>

<body>
    <header>
        <h1 class=logo>DIG<span>IT</span></h1>
        <div class="btn-wrap">
            <button name='save' id='save' class='btn btn-left' type='submit' form='add'>SAVE</button>
            <button onclick="document.location='index.php'" class="btn">CANCEL</button>
        </div>
    </header>
    <main class="product-add">
        <form id="add" action="" method="POST">

            <?php

            $out->output_fields();

            $out->output_categories();

            $out->output_category_fields();

            Product::create_product($db);

            $out->output_message();

            ?>

        </form>
    </main>
</body>

</html>