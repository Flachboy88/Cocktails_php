<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';

// si l'utilisateur n'est pas connecté, on redirige vers la page de connexion
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$user = $_SESSION['user'];
$login = $user['login'];

// récup info perso
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login=?");
$stmt->execute([$login]);
$userInfo = $stmt->fetch();

// si n'existe pas BDD on déco
if (!$userInfo) {
    session_destroy();
    header("Location: connexion.php");
    exit();
}

//traitement du formulaire
if ($_POST) {

    $nom = $_POST['nom'] ?? ""; // récupération du nom, si non présent, on met un espace
    $prenom = $_POST['prenom'] ?? ""; // récupération du prénom, si non présent, on met un espace
    $sexe = $_POST['sexe'] ?? ""; // récupération du sexe, si non présent, on met un espace
    $email = $_POST['email'] ?? ""; // récupération de l'email, si non présent, on met un espace
    $date_naissance = $_POST['date_naissance'] ?? ""; // récupération de la date de naissance, si non présent, on met un espace
    $adresse = $_POST['adresse'] ?? ""; // récupération de l'adresse, si non présent, on met un espace
    $code_postal = $_POST['code_postal'] ?? ""; // récupération du code postal, si non présent, on met un espace
    $ville = $_POST['ville'] ?? ""; // récupération de la ville, si non présent, on met un espace
    $telephone = $_POST['telephone'] ?? ""; // récupération du téléphone, si non présent, on met un espace

    // tableau pour stocker les erreurs
    $erreurs = [];

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

    // si pas d'erreurs, on met à jour
    if (empty($erreurs)) {
        // on fait la requête de mise à jour
        $stmt = $pdo->prepare("
            UPDATE utilisateurs 
            SET nom=?, prenom=?, sexe=?, email=?, datenaissance=?, adresse=?, codepostal=?, ville=?, telephone=?
            WHERE login=?
        ");

        // on exécute la requête avec les valeurs modifiées
        $stmt->execute([
            $nom,
            $prenom,
            $sexe,
            $email,
            $date_naissance,
            $adresse,
            $code_postal,
            $ville,
            $telephone,
            $login
        ]);

        // recharge les infos
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login=?");
        $stmt->execute([$login]);
        $userInfo = $stmt->fetch();
        $_SESSION['user'] = $userInfo;

        $message = "Informations mises à jour avec succès !";
    } else {
        // si des erreurs existent, on les affiche
        $message = implode("<br>", $erreurs);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes informations personnelles</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>

<main class="form-container">
    <div class="form-card">

        <h2 class="title">Mes informations personnelles</h2>
        <?php 
        if (isset($message)) {
            // si le message contient "succès", affichage en vert, sinon en rouge
            $style = (strpos($message, 'succès') !== false) 
                ? "background:#e5ffe5;color:#2d7a2d;" 
                : "background:#ffe5e5;color:#d32f2f;";
            echo "<div class='error' style='$style'>$message</div>";
        }
        ?>

        <!-- formulaire de mise à jour des informations personnelles -->
        <form method="post" class="form">

            <h3 class="section-title">Identité</h3>

            <input class="input" type="text" name="nom" 
                value="<?php echo htmlspecialchars($userInfo['nom']); ?>" placeholder="Nom">

            <input class="input" type="text" name="prenom" 
                value="<?php echo htmlspecialchars($userInfo['prenom']); ?>" placeholder="Prénom">

            <label class="label">Sexe :</label>
            <select class="input" name="sexe">
                <option value="">--</option>
                <option value="H" <?php echo ($userInfo['sexe'] == "H") ? "selected" : ""; ?>>Homme</option>
                <option value="F" <?php echo ($userInfo['sexe'] == "F") ? "selected" : ""; ?>>Femme</option>
            </select>

            <h3 class="section-title">Contact</h3>

            <input class="input" type="email" name="email" 
                value="<?php echo htmlspecialchars($userInfo['email']); ?>" placeholder="Email">

            <input class="input" type="text" name="telephone" 
                value="<?php echo htmlspecialchars($userInfo['telephone']); ?>" placeholder="Téléphone">

            <h3 class="section-title">Adresse</h3>

            <input class="input" type="text" name="adresse" 
                value="<?php echo htmlspecialchars($userInfo['adresse']); ?>" placeholder="Adresse">

            <input class="input" type="text" name="code_postal" 
                value="<?php echo htmlspecialchars($userInfo['codepostal']); ?>" placeholder="Code postal">

            <input class="input" type="text" name="ville" 
                value="<?php echo htmlspecialchars($userInfo['ville']); ?>" placeholder="Ville">

            <h3 class="section-title">Autre</h3>

            <label class="label">Date de naissance :</label>
            <input class="input" type="date" name="date_naissance" 
                value="<?php echo htmlspecialchars($userInfo['datenaissance']); ?>">

            <button type="submit" class="btn">Enregistrer les modifications</button>
            <a href="pagePrincipale.php" class="btn-return"> Retour aux recettes</a>

        </form>

    </div>
</main>

</body>
</html>