<?php
namespace Quantik2024;
require_once '../PHP/PDOQuantik.php';
require_once '../PHP/Player.php';
require_once './env/db.php';
use Exception;

session_start();

if (!isset($_SESSION['player'])) {
    header('HTTP/1.1 303 See Other');
    header('Location: login.php');
    exit();
}

$player = $_SESSION['player'];
PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
$games = PDOQuantik::getAllGameQuantik();

$page = '<!DOCTYPE html>
<html class="no-js" lang="fr" dir="ltr" style="background-color: #cac5c5;">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="Author" content="Dominique Fournier" />
    <link rel="stylesheet" href="quantik.css" />
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Salle de jeux Quantik</title>
</head>
<body>
    <section class="section">
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="quantik has-text-centered">
                        <h1 class="title">Bienvenue -: <i>' . $player->getName() . '</i></h1>
                        <h1 class="subtitle is-3">Options disponibles :</h1>
                        <ul class="has-text-centered">';
                            $page .= '<li>
                                <form action="../traiteFormQuantik.php" method="post">
                                    <input type="hidden" name="action" value="constructed">
                                    <button class="button is-primary" type="submit">Initier une nouvelle partie</button>
                                </form>
                            </li>';
                            $page .= '<li>
                                <h1 class="has-text-centered is-size-4 has-text-weight-bold">Parties en cours</h1>
                                <form action="../traiteFormQuantik.php" method="post">';
                                foreach ($games as $game) {
                                    if ($game['gameStatus'] == 'initialized' || $game['gameStatus'] == 'waitingForPlayer') {
                                        
                                        $playerOne = PDOQuantik::selectPlayerByID($game['playerOne']);
                                        $playerTwo = PDOQuantik::selectPlayerByID($game['playerTwo']);
                                        if($playerOne['name']==$player->getName() || $playerTwo['name']==$player->getName()){
                                            $page .= '<button class="button" type="submit" name="gameID" value="'.$game['gameId'] .'">Partie ['.$game['gameId'] . '] de '.$playerOne['name'].' en cours avec '.$playerTwo['name'].'</button><br>';
                                        }

                                       
                                    }
                                }
                                $page .= '<input type="hidden" name="action" value="waitingForPlayer"></form></li>';

                            $page .= '<li>
                                <h3 class="has-text-centered is-size-4 has-text-weight-bold">Parties en attente d\'un joueur</h3>
                                <form action="../traiteFormQuantik.php" method="post">';
                                foreach ($games as $game) {
                                    if ($game['gameStatus'] == 'constructed') {
                                        $playerOne = PDOQuantik::selectPlayerByID($game['playerOne']);
                                        $disabled = ($_SESSION['player']->getId() === $game['playerOne']) ? 'disabled' : '';

                                        $page .= '<button class="button" type="submit" name="gameID" value="'.$game['gameId'] .'" '.$disabled.'>Partie ['.$game['gameId'] . '] - '.$playerOne['name'].' en attente d\'un autre joueur</button><br>';
                                    }
                                }
                                $page .= '<input type="hidden" name="action" value="initialized"></form></li>';

                            $page .= '<li>
                                <h3 class="has-text-centered is-size-4 has-text-weight-bold">Parties terminées</h3>
                                <form action="../traiteFormQuantik.php" method="post">';
                                foreach ($games as $game) {
                                    if ($game['gameStatus'] == 'finished') {
                                        $playerOne = PDOQuantik::selectPlayerByID($game['playerOne']);
                                        $playerTwo = PDOQuantik::selectPlayerByID($game['playerTwo']);
                                        if($playerOne['name']==$player->getName() || $playerTwo['name']==$player->getName()){
                                            $page .= '<button class="button" type="submit" name="gameID" value="'.$game['gameId'] .'">Partie ['.$game['gameId'] . '] de '.$playerOne['name'].' terminée avec '.$playerTwo['name'].'</button><br>';
                                        }
                                    }
                                }
                                $page .= '<input type="hidden" name="action" value="finished"></form></li>';

                            $page .= '<li>
                                <form action="../traiteFormQuantik.php" method="post">
                                    <input type="hidden" name="action" value="deconnexion">
                                    <button class="button is-danger" type="submit">Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</body>
</html>';

echo $page;
?>
