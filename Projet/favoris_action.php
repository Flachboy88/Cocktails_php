<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';

$redirectPage = 'index.php';

if (isset($_GET['id'])) {
    $redirectPage = 'boissonSpecifique.php?boissonSpecifique=' . urlencode($_GET['id']);
}

if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
    $redirectTarget = $_GET['redirect'];
    if ($redirectTarget === 'favoris.php' || $redirectTarget === 'pagePrincipale.php' || $redirectTarget === 'index.php') {
        $redirectPage = $redirectTarget;
    }
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $recetteId = (int)$_GET['id'];
    $action = $_GET['action'];

    if (isset($_SESSION['user'])) {
        $userId = $_SESSION['user']['id'];

        if ($action === 'add') {
            try {
                $stmt = $pdo->prepare("INSERT INTO recettes_favorites (utilisateur_id, recette_id) VALUES (?, ?)");
                $stmt->execute([$userId, $recetteId]);
            } catch (Exception $e) {
            }
        } elseif ($action === 'remove') {
            $stmt = $pdo->prepare("DELETE FROM recettes_favorites WHERE utilisateur_id = ? AND recette_id = ?");
            $stmt->execute([$userId, $recetteId]);
        }

    } else {
        if (!isset($_SESSION['guest_favorites'])) {
            $_SESSION['guest_favorites'] = [];
        }

        if ($action === 'add' && !in_array($recetteId, $_SESSION['guest_favorites'])) {
            $_SESSION['guest_favorites'][] = $recetteId;
        } elseif ($action === 'remove') {
            $indexToRemove = array_search($recetteId, $_SESSION['guest_favorites']);
            if ($indexToRemove !== false) {
                unset($_SESSION['guest_favorites'][$indexToRemove]);
                $_SESSION['guest_favorites'] = array_values($_SESSION['guest_favorites']);
            }
        }
    }
}

header("Location: " . $redirectPage);
exit;