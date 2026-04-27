<?php

global $yhendus;
require('config0.php');

if (isset($_REQUEST["uusleht"])) {
    $kask = $yhendus->prepare("INSERT INTO tooded (hind, kirjeldus, linn, photo, nimi) VALUES (?, ?, ?, ?, ?)");
    $kask->bind_param("dssss", $_REQUEST["hind"], $_REQUEST["kirjeldus"], $_REQUEST["linn"], $_REQUEST["photo"], $_REQUEST["nimi"]);
    $kask->execute();
    header("Location: index.php");
    $yhendus->close();
    exit();
}
if (isset($_REQUEST["kustutusid"])) {
    $kask = $yhendus->prepare("DELETE FROM tooded WHERE tootedID=?");
    $kask->bind_param("i", $_REQUEST["kustutusid"]);
    $kask->execute();
}
if (isset($_REQUEST["muutmisid"])) {
    $kask = $yhendus->prepare("UPDATE tooded SET hind=?, kirjeldus=?, linn=?, photo=?, nimi=? WHERE tootedID=?");
    $kask->bind_param("dssssi", $_REQUEST["hind"], $_REQUEST["kirjeldus"], $_REQUEST["linn"], $_REQUEST["photo"], $_REQUEST["nimi"], $_REQUEST["muutmisid"]);
    $kask->execute();
}
require("nav.php");
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Tooted lehel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Tooted lisamine, kustutamine ja uuendamine</h1>

<?php

if (isset($_REQUEST["id"])) {
    $kask = $yhendus->prepare("SELECT tootedID, hind, kirjeldus, linn, photo, nimi FROM tooded WHERE tootedID=?");
    $kask->bind_param("i", $_REQUEST["id"]);
    $kask->bind_result($tootedID, $hind, $kirjeldus, $linn, $photo, $nimi);
    $kask->execute();

    if ($kask->fetch()) {

        if (isset($_REQUEST["muutmine"])) {
            echo "
                <h2>Toote muutmine</h2>
                <form action='{$_SERVER["PHP_SELF"]}'>
                    <input type='hidden' name='muutmisid' value='$tootedID'/>
                    <table>
                        <tr><td>Nimi:</td><td><input type='text' name='nimi' value='".htmlspecialchars($nimi)."'></td></tr>
                        <tr><td>Hind:</td><td><input type='text' name='hind' value='".htmlspecialchars($hind)."'></td></tr>
                        <tr><td>Kirjeldus:</td><td><input type='text' name='kirjeldus' value='".htmlspecialchars($kirjeldus)."'></td></tr>
                        <tr><td>Linn:</td><td><input type='text' name='linn' value='".htmlspecialchars($linn)."'></td></tr>
                        <tr><td>Foto:</td><td><input type='text' name='photo' value='".htmlspecialchars($photo)."'></td></tr>
                    </table>
                    <input type='submit' value='Muuda'>
                </form>
            ";
        } 
        else {
            echo "<h2>".htmlspecialchars($nimi)."</h2>";
            echo "<p>Kirjeldus: ".htmlspecialchars($kirjeldus)."</p>";
            echo "<p>Hind: ".htmlspecialchars($hind)."</p>";
            echo "<p>Linn: ".htmlspecialchars($linn)."</p>";
            echo "<p><img src='".htmlspecialchars($photo)."' alt='' style='max-width:200px;'></p>";
        }
    }
}


echo "<h2>Kõik tooted</h2>";

$kask = $yhendus->prepare("SELECT tootedID, nimi, hind, kirjeldus, linn, photo FROM tooded");
$kask->bind_result($tid, $tnimi, $thind, $tkirjeldus, $tlinn, $tphoto);
$kask->execute();

echo "<table border='1' cellpadding='5'>
<tr>
    <th>ID</th>
    <th>Nimi</th>
    <th>Hind</th>
    <th>Kirjeldus</th>
    <th>Linn</th>
    <th>Foto</th>
    <th>Muuda</th>
    <th>Kustuta</th>
</tr>";

while ($kask->fetch()) {
    echo "
        <tr>
            <td>$tid</td>
            <td>".htmlspecialchars($tnimi)."</td>
            <td>".htmlspecialchars($thind)."</td>
            <td>".htmlspecialchars($tkirjeldus)."</td>
            <td>".htmlspecialchars($tlinn)."</td>
            <td><img src='".htmlspecialchars($tphoto)."' width='80'></td>
            <td><a href='{$_SERVER["PHP_SELF"]}?id=$tid&muutmine=jah'>Muuda</a></td>
            <td><a href='{$_SERVER["PHP_SELF"]}?kustutusid=$tid'>Kustuta</a></td>
        </tr>
    ";
}
echo "</table>";


?>
</div>
<br><br>
<div id="menyykiht">
<h2>Uue toote lisamine</h2>
<form action="<?=$_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="uusleht" value="jah" />
    <table>
        <tr><td>Nimi:</td><td><input type="text" name="nimi"></td></tr>
        <tr><td>Hind:</td><td><input type="number" name="hind"></td></tr>
        <tr><td>Kirjeldus:</td><td><input type="text" name="kirjeldus"></td></tr>
        <tr><td>Linn:</td><td><input type="text" name="linn"></td></tr>
        <tr><td>Foto:</td><td><input type="text" name="photo"></td></tr>
    </table>
    <input type="submit" value="Sisesta">
</form>

</div>


<br><br>
</body>
</html>
<?php
$yhendus->close();
?>
<?php
require("footer.php");
?>
