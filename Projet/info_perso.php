<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$user = $_SESSION['user'];
$login = $user['login'];

$message = "";

if ($_POST) {

    $nom = $_POST['nom'] ?? "";
    $prenom = $_POST['prenom'] ?? "";
    $sexe = $_POST['sexe'] ?? "";
    $email = $_POST['email'] ?? "";
    $date_naissance = $_POST['date_naissance'] ?? "";
    $adresse = $_POST['adresse'] ?? "";
    $code_postal = $_POST['code_postal'] ?? "";
    $ville = $_POST['ville'] ?? "";
    $telephone = $_POST['telephone'] ?? "";

    $stmt = $pdo->prepare("
        UPDATE utilisateurs 
        SET nom=?, prenom=?, sexe=?, email=?, datenaissance=?, adresse=?, codepostal=?, ville=?, telephone=?
        WHERE login=?
    ");

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

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login=?");
    $stmt->execute([$login]);
    $_SESSION['user'] = $stmt->fetch();

    $message = "Informations mises à jour avec succès !";
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes informations personnelles</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="form-container">
    <div class="form-card">

        <h2 class="title">Mes informations personnelles</h2>
        <?php if ($message) echo "<p class='error' style='background:#e5ffe5;color:#2d7a2d;'>$message</p>"; ?>
        </a>

        <form method="post" class="form">

            <h3 class="section-title">Identité</h3>

            <input class="input" type="text" name="nom" 
                value="<?php echo $user['nom']; ?>" placeholder="Nom">

            <input class="input" type="text" name="prenom" 
                value="<?php echo $user['prenom']; ?>" placeholder="Prénom">

            <label class="label">Sexe :</label>
            <select class="input" name="sexe">
                <option value="">--</option>
                <option value="H" <?php echo ($user['sexe'] == "H") ? "selected" : ""; ?>>Homme</option>
                <option value="F" <?php echo ($user['sexe'] == "F") ? "selected" : ""; ?>>Femme</option>
            </select>

            <h3 class="section-title">Contact</h3>

            <input class="input" type="email" name="email" 
                value="<?php echo $user['email']; ?>" placeholder="Email">

            <input class="input" type="text" name="telephone" 
                value="<?php echo $user['telephone']; ?>" placeholder="Téléphone">

            <h3 class="section-title">Adresse</h3>

            <input class="input" type="text" name="adresse" 
                value="<?php echo $user['adresse']; ?>" placeholder="Adresse">

            <input class="input" type="text" name="code_postal" 
                value="<?php echo $user['codepostal']; ?>" placeholder="Code postal">

            <input class="input" type="text" name="ville" 
                value="<?php echo $user['ville']; ?>" placeholder="Ville">

            <h3 class="section-title">Autre</h3>

            <label class="label">Date de naissance :</label>
            <input class="input" type="date" name="date_naissance" 
                value="<?php echo $user['datenaissance']; ?>">

            <button type="submit" class="btn">Enregistrer les modifications</button>
            <a href="pagePrincipale.php" class="btn-return"> Retour aux recettes</a>

        </form>

    </div>
</main>

</body>
</html>
