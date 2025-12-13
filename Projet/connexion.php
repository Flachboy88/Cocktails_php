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

    if ($user) {
        if ($user['motdepasse'] === $mdp) {
            $_SESSION['user'] = $user;
            
            if (isset($_SESSION['guest_favorites']) && !empty($_SESSION['guest_favorites'])) {
                $userId = $user['id'];
                
                foreach ($_SESSION['guest_favorites'] as $recetteId) {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO recettes_favorites (utilisateur_id, recette_id) VALUES (?, ?)");
                        $stmt->execute([$userId, $recetteId]);
                    } catch (Exception $e) {
                        
                    }
                }
                
                // supp les favoris visiteurs 
                unset($_SESSION['guest_favorites']);
            }
            
            header("Location: pagePrincipale.php");
            exit;
        } else {
            $err = "Login ou mot de passe incorrect.";
        }
    } else {
        $err = "Login ou mot de passe incorrect.";
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