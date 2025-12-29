<?php
session_start();
include 'header.php';

if ($_POST) {
    
    $login = $_POST['login'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $sexe = $_POST['sexe'] ?? '';
    $email = $_POST['email'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $code_postal = $_POST['code_postal'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $telephone = $_POST['telephone'] ?? '';

    // tableau pour stocker les erreurs
    $erreurs = [];

    // vérification login et mot de passe obligatoires
    if (empty($login)) {
        $erreurs[] = "Le login est obligatoire.";
    }
    if (empty($mdp)) {
        $erreurs[] = "Le mot de passe est obligatoire.";
    }

    // vérification nom 
    if (!empty($nom) && !preg_match("/^[a-zA-ZÀ-ÿ\s\-]+$/u", $nom)) {
        $erreurs[] = "Le nom ne doit contenir que des lettres.";
    }

    // vérification prénom
    if (!empty($prenom) && !preg_match("/^[a-zA-ZÀ-ÿ\s\-]+$/u", $prenom)) {
        $erreurs[] = "Le prénom ne doit contenir que des lettres.";
    }

    // vérification email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email n'est pas valide.";
    }

    // vérification ville
    if (!empty($ville) && !preg_match("/^[a-zA-ZÀ-ÿ\s\-]+$/u", $ville)) {
        $erreurs[] = "La ville ne doit contenir que des lettres.";
    }

    // vérification téléphone
    if (!empty($telephone) && !preg_match("/^0[1-9][0-9]{8}$/", $telephone)) {
        $erreurs[] = "Le téléphone doit être au format français (10 chiffres commençant par 0).";
    }

    // si pas d'erreurs, on insère
    if (empty($erreurs)) {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, motdepasse, nom, prenom, sexe, email, datenaissance, adresse, codepostal, ville, telephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$login, $mdp, $nom, $prenom, $sexe, $email, $date_naissance, $adresse, $code_postal, $ville, $telephone]);

            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch();

            $_SESSION['user'] = $user;

            header("Location: pagePrincipale.php");
            exit;

        } catch (Exception $e) {
            $erreurs[] = "Ce login existe déjà.";
        }
    }
    
    // si des erreurs existent
    if (!empty($erreurs)) {
        $message = implode("<br>", $erreurs);
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription – MyCocktails</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>

<main class="form-container">
    <div class="form-card">

        <h2 class="title">Créer un compte</h2>

        <?php 
        if (isset($message)) {
            echo "<div class='error'>" . $message . "</div>";
        }
        ?>

        <form method="post" class="form">

            <h3 class="section-title">Identifiants</h3>
            <input class="input" type="text" name="login" placeholder="Login (obligatoire)" required>
            <input class="input" type="password" name="mdp" placeholder="Mot de passe (obligatoire)" required>

            <h3 class="section-title">Informations personnelles</h3>
            <input class="input" type="text" name="nom" placeholder="Nom">
            <input class="input" type="text" name="prenom" placeholder="Prénom">

            <label class="label">Sexe :</label>
            <select class="input" name="sexe">
                <option value="">--</option>
                <option value="H">Homme</option>
                <option value="F">Femme</option>
            </select>

            <input class="input" type="email" name="email" placeholder="Email">
            <input class="input" type="date" name="date_naissance">

            <input class="input" type="text" name="adresse" placeholder="Adresse">
            <input class="input" type="text" name="code_postal" placeholder="Code postal">
            <input class="input" type="text" name="ville" placeholder="Ville">
            <input class="input" type="text" name="telephone" placeholder="Téléphone">

            <button type="submit" class="btn">Créer mon compte</button>
        </form>

    </div>
</main>

</body>
</html>