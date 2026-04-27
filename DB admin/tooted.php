<?php
require("nav.php");
require('config0.php');
global $yhendus;
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Tooted lehel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Tooted</h1>

<div class="tooted_block">
    <div class="tooted_container">
        <ul>
            <?php

            $stmt1 = $yhendus->prepare("SELECT tootedID, nimi, photo FROM tooded");
            $stmt1->execute();
            $stmt1->bind_result($id, $nimi, $photo);

            while($stmt1->fetch()){
                echo "<li>
                        <div class='product-card'>
                            <img src='".htmlspecialchars($photo)."' alt='toode' class='product-img'>
                            <p>".htmlspecialchars($nimi)."</p>
                        </div>
                     </li>";
            }
            $stmt1->close();
            ?>
        </ul>
    </div>
</div>

</body>
</html>

<?php $yhendus->close(); ?>
<?php
echo "<br>";
require("footer.php");
?>
