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
            <button onclick="document.location='add.php'" class="btn btn-left">ADD</button>
            <button name=" delete" class="btn" form="cb-form" type="submit">DELETE</button>
        </div>
    </header>
    <main>
        <form class="container" action="" method="POST" id="cb-form">

            <?php

            $out->display_items();

            Product::delete_from_database($db);

            ?>

        </form>
    </main>
</body>

</html>