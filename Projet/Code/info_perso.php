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

$message = "";

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
        <?php if ($message) echo "<p class='error' style='background:#e5ffe5;color:#2d7a2d;'>$message</p>"; ?> <!-- affichage du message d'erreur si présent -->

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