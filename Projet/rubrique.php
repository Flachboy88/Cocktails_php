<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['Aliment'])){
    $_SESSION['Aliment'] = 'Aliment';
}
if (!isset($_SESSION['ArbreDeRecherche'])){
    $_SESSION['ArbreDeRecherche'][0] = 'Aliment';
}
include 'Donnees.inc.php';

if (isset($_GET['Aliment'])) {

    $_SESSION['Aliment'] = $_GET['Aliment'];

    $nouv = true;
    $i = 0;

    foreach ($_SESSION['ArbreDeRecherche'] as $recherche) {
        if ($nouv == false) {
            unset($_SESSION['ArbreDeRecherche'][$i]);
        }
        if ($recherche == $_GET['Aliment']) {
            $nouv = false;
        }
        $i++;
    }

    if ($nouv == true) {
        $_SESSION['ArbreDeRecherche'][$i] = $_GET['Aliment'];
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Rubrique</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
<h1>Catégories</h1>

<div class="arbre-recherche">
    <?php foreach ($_SESSION['ArbreDeRecherche'] as $recherche) : ?>
        <a href="pagePrincipale.php?Aliment=<?= urlencode($recherche) ?>">
            <?= htmlspecialchars($recherche) ?>
        </a>
        <span> / </span>
    <?php endforeach; ?>
</div>

<br>

<?php
    $alimentActuel = $_SESSION['Aliment'];
?>

<ul>
<?php
    foreach ($Hierarchie as $key => $categorie) :
        if ($key == $alimentActuel) :
            if (isset($categorie['sous-categorie'])) :
                foreach ($categorie['sous-categorie'] as $value) :
?>
                <li>
                    <a href="pagePrincipale.php?Aliment=<?= urlencode($value) ?>">
                        <?= htmlspecialchars($value) ?>
                    </a>
                </li>
<?php
            endforeach;
        endif;

    endif;

endforeach;
?>
</ul>


</body>
</html>