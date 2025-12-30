<?php

session_start(); // on démarre la session
session_destroy(); // on détruit la session

header("Location: ../../index.php"); // on redirige vers la page d'accueil
exit;