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
if (!isset($_SESSION['boissonSpecifique'])){
    $_SESSION['boissonSpecifique'] = 0;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main>
    <p>
    <?php 
        if ($_SESSION['Aliment'] == 'Aliment'){
            foreach ($Recettes as $r => $nomboisson):?>
                <li>
                    <a class="boisson" href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($r) ?>">
                        <?= htmlspecialchars($nomboisson['titre']) ?>
                    </a>
                </li>
            <?php endforeach;
        }
    ?>
    </p>
</main>

</body>
</html>