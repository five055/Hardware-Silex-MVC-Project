
<?php
  // script login2.php
  session_start();
  // connexion BDD
  require 'include/connexion.php';
  header( 'content-type: text/html; charset=utf-8' );
  // Mr propre
  $safe = array_map('strip_tags', $_POST);
  // adress mail existe
  $rqVerif = "SELECT COUNT(*) FROM clients WHERE mailClient = :mailClient";
  // préparartion
  $stmtVerif = $dbh->prepare($rqVerif);
  // parametres
  $paramVerif = array(':mailClient' => $safe['mailClient']);
  //execution
  $stmtVerif ->execute($paramVerif);
  // recuperation
  $exist = $stmtVerif->fetchColumn();


  // si erreur = 0
if ($exist == 1) {
    // recuperation mdp
    $rqClient = "SELECT idClient, passClient FROM clients WHERE mailClient = :mailClient";
    // preparation
    $stmtClient = $dbh->prepare($rqClient);
    // execution
    $stmtClient->execute($paramVerif);
    // info client
    $clients = $stmtClient->fetch();
    // comparaison mdp form et mdp BDD
    if (password_verify($safe['passClient'], $clients['passClient'])) {
        // client trouvé
        $_SESSION['auth'] = 'ok';
        $_SESSION['id'] = $clients['idClient'];
        // sécurité
        session_regenerate_id();


        // message de Bienvenue et retour accueil
        echo '<script>alert("Bienvenue ' . $clients['mailClient']'");
            window.location.href="../index.html.twig";
            </script>';
    } else {
        echo '<script>alert("Votre mot de passe est incorrect");
            window.location.href="login.php";
            </script>';
    }
} else {
    echo '<script>alert("Votre email est inconnu");
          window.location.href="login.php";
          </script>';
}
