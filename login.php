<?php

namespace Quantik2024;

session_start();

function getPageLogin(): string {
    $form = '<!DOCTYPE html>
    <html class="no-js" lang="fr" dir="ltr" style="background-color: #fee8d9;">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="Author" content="Dominique Fournier" />
        <link rel="stylesheet" href="quantik.css" />
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <title>Accès à la salle de jeux</title>
      </head>
      <body>
        <section class="section">
          <div class="container">
            <div class="columns is-centered">
              <div class="column is-half menuQuantik">
                <h1 class="title">Accès au salon Quantik</h1>
                <h2 class="subtitle">Identification du joueur</h2>
                <form action="' .$_SERVER['PHP_SELF'].'" method="post">
                  <div class="field">
                    <label class="label">Nom</label>
                    <div class="control">
                      <input class="input" type="text" name="playerName" placeholder="Votre nom" required>
                    </div>
                  </div>
                  <div class="field">
                    <div class="control">
                      <input class="button is-primary" type="submit" name="action" value="Connecter">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
      </body>
    </html>';

    return $form;
}

if (isset($_REQUEST['playerName'])) {
    // connexion à la base de données
    require_once 'ressourcesQuantik/env/db.php';
    require_once './PHP/PDOQuantik.php';
    PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
    $player = PDOQuantik::selectPlayerByName($_REQUEST['playerName']);
    if (is_null($player))
        $player = PDOQuantik::createPlayer($_REQUEST['playerName']);
    $_SESSION['player'] = $player;
    header('HTTP/1.1 303 See Other');
    header('Location: quantik.php');
} else {
    echo getPageLogin();
}
?>
