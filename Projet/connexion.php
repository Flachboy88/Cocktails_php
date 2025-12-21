<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['login']) && $_POST['login'] != '') {
        $login = $_POST['login'];
    } else {
        $login = '';
    }

    if (isset($_POST['mdp']) && $_POST['mdp'] != '') {
        $mdp = $_POST['mdp'];
    } else {
        $mdp = '';
    }

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user['motdepasse'] === $mdp) {
    $_SESSION['user'] = $user;
    
    // charger les favoris depuis la BDD
    $stmt_fav = $pdo->prepare("SELECT recette_id FROM recettes_favorites WHERE utilisateur_id = ?");
    $stmt_fav->execute([$user['id']]);
    $favoris_bdd = $stmt_fav->fetchAll(PDO::FETCH_COLUMN);
    
    // fusionner avec les favoris en session (mode visiteur)
    if (isset($_SESSION['favoris']) && !empty($_SESSION['favoris'])) {
        $_SESSION['favoris'] = array_unique(array_merge($_SESSION['favoris'], $favoris_bdd));
        
        // sauvegarder les favoris du visiteur en BDD
        foreach ($_SESSION['favoris'] as $recette_id) {
            $stmt_insert = $pdo->prepare("INSERT IGNORE INTO recettes_favorites (utilisateur_id, recette_id) VALUES (?, ?)");
            $stmt_insert->execute([$user['id'], $recette_id]);
        }
    } else {
        $_SESSION['favoris'] = $favoris_bdd;
    }
    
    header("Location: pagePrincipale.php");
    exit;
}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion – MyCocktails</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="form-container">
    <div class="form-card">

        <h2 class="title">Connexion</h2>

        <?php if (isset($err)) echo "<p class='error'>$err</p>"; ?>

        <form method="post" class="form">
            <input class="input" type="text" name="login" placeholder="Login" required>
            <input class="input" type="password" name="mdp" placeholder="Mot de passe" required>

            <button type="submit" class="btn">Se connecter</button>
        </form>

        <p class="link-text">Pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
    </div>
</main>

</body>
</html>