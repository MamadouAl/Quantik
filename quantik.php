<?php

namespace Quantik2024;
require_once 'PHP/PDOQuantik.php';
require_once 'PHP/Player.php';
require_once 'ressourcesQuantik/env/db.php';

use Exception;

session_start();

if (!isset($_SESSION['player'])) {
    header('HTTP/1.1 303 See Other');
    header('Location: login.php');
    exit();
}

$player = $_SESSION['player'];
PDOQuantik::initPDO($_ENV['sgbd'], $_ENV['host'], $_ENV['database'], $_ENV['user'], $_ENV['password']);
$games = PDOQuantik::getAllGameQuantik();

$page = '<!DOCTYPE html>
<html class="no-js" lang="fr" dir="ltr" style="background-color: #fee8d9;">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="Author" content="Dominique Fournier" />
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Salle de jeux Quantik</title>
</head>
<body>
    <section class="section">
            <div class="columns is-centered">
                <div class="column is-half menuQuantik">
                    <div class="quantik has-text-centered">
                        <h1 class="title is-1">Bienvenue : <i class="has-text-danger">' . $player->getName() . '</i></h1>
                        <h2 class="subtitle is-3">Options disponibles :</h2>
                        <div class="menu has-text-centered">';
$page .= '                  <div class="init ">
                                <form action="traiteFormQuantik.php" method="post">
                                    <input type="hidden" name="action" value="constructed">
                                   
                                    <button class="button is-success" type="submit" >
                                    <img src="./image/insert_logo.png" alt="initialisation d\'une partie" height="45" width="45">
                                    <span class="title is-3"> Initier une nouvelle partie</span></button>
                                </form>
                            </div>';

$page .= '              <div class="loading">
                            <h2 class="has-text-centered is-size-2 has-text-weight-bold">
                            Parties en cours</h2>
                            <form action="traiteFormQuantik.php" method="post">';
                                foreach ($games as $game) {
                                    if ($game['gamestatus'] == 'initialized' || $game['gamestatus'] == 'waitingForPlayer') {

                                        $playerOne = PDOQuantik::selectPlayerByID($game['playerone']);
                                        $playerTwo = PDOQuantik::selectPlayerByID($game['playertwo']);
                                        if ($playerOne['name'] == $player->getName() || $playerTwo['name'] == $player->getName()) {
                                        $page .= ' <img src="./image/loading.png" alt="parties en cours" height="40" width="40">
                                
                                            <button class="button" type="submit" name="gameID" value="' . $game['gameid'] . '">Partie [' . $game['gameid'] . '] de ' . $playerOne['name'] . ' en cours avec ' . $playerTwo['name'] . '</button><br>';
                                        }
                                    }
                                }
$page .= '                    <input type="hidden" name="action" value="waitingForPlayer">
                            </form>
                         </div>';

$page .= '                <div class="attente">
                                <h3 class="has-text-centered is-size-2 has-text-weight-bold">Parties en attente d\'un joueur</h3>
                                <form action="traiteFormQuantik.php" method="post">';
                                    foreach ($games as $game) {
                                        if ($game['gamestatus'] == 'constructed') {
                                            $playerOne = PDOQuantik::selectPlayerByID($game['playerone']);
                                            $disabled = ($_SESSION['player']->getId() === $game['playerone']) ? 'disabled' : '';

                                            $page .= '<button class="button" type="submit" name="gameID" value="' . $game['gameid'] . '" ' . $disabled . '>Partie [' . $game['gameid'] . '] - ' . $playerOne['name'] . ' en attente d\'un autre joueur</button><br>';
                                        }
                                    }
$page .= '                        <input type="hidden" name="action" value="initialized">
                                </form>
                          </div>';

    $page .=         ' <div class="fin">
                            <h2 class="has-text-centered is-size-2 has-text-weight-bold">Parties terminées</h2>
                            <form action="traiteFormQuantik.php" method="post">';
                                $games = PDOQuantik::getAllGameQuantikByPlayerName($player->getName());
                                foreach ($games as $game) {
                                    if ($game['gamestatus'] == 'finished') {
                                        $playerOne = PDOQuantik::selectPlayerByID($game['playerone']);
                                        $playerTwo = PDOQuantik::selectPlayerByID($game['playertwo']);
                                       // if ($playerOne['name'] == $player->getName() || $playerTwo['name'] == $player->getName()) {
                                            $page .= '
                                            <img src="./image/finish.jpg" alt="parties terminées" height="40" width="40">
                                            <button class="button" type="submit" name="gameID" value="' . $game['gameid'] . '">Partie [' . $game['gameid'] . '] de ' . $playerOne['name'] . ' terminée avec ' . $playerTwo['name'] . '</button><br>';
                                    }
                                }
        $page .= '           <input type="hidden" name="action" value="finished">
                            </form>
                        </div>';


$page .= '                <div class="deconnexion">
                                <form action="traiteFormQuantik.php" method="post">
                                    <input type="hidden" name="action" value="deconnexion">
                                    <button class="button is-danger" type="submit">Déconnexion</button>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</body>
</html>';

echo $page;
?>