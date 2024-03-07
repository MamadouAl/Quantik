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
    <html class="no-js" lang="fr" dir="ltr">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="Author" content="Dominique Fournier" />
        <link rel="stylesheet" href="quantik.css" />
        <title>Salle de jeux Quantik</title>
      </head>
      <body>';

    $page .= '
<div class="quantik">
          <h1>Bienvenue -: <i>' . $player->getName() . '</i></h1>

          <h2>Options disponibles :</h2>
          <ul>
            <li>
              <form action="../traiteFormQuantik.php" method="post">
              
                <input type="hidden" name="action" value="constructed">
                <input type="submit" value="Initier une nouvelle partie">
              </form>
            </li>';

            $page .= '
            <li> <h3>Parties en cours</h3>
                <form action="../traiteFormQuantik.php" method="post">';
                    foreach ($games as $game) {
                        if ($game['gameStatus'] == 'initialized') {
                            $playerOne = PDOQuantik::selectPlayerByID($game['playerOne']);
                            $playertwo = PDOQuantik::selectPlayerByID($game['playerTwo']);

                            $page .= '<button type="submit" name="gameID" value="'.$game['gameId'] .'">Partie ['.$game['gameId'] . '] de '.$playerOne['name'].' en cours avec '.$playertwo['name'].'</button></br>';
                        }
                    }
            $page .='<input type="hidden" name="action" value="waitingForPlayer">

                </form>
        </li>';

    $page .= '
            <li> <h3>Parties en attente d\'un joueur </h3>
                <form action="../traiteFormQuantik.php" method="post">';
foreach ($games as $game) {
    if ($game['gameStatus'] == 'constructed') {
        $player = PDOQuantik::selectPlayerByID($game['playerOne']);
        if($_SESSION['player']->getId() === $game['playerOne']){
            $page .= '<button type="submit" name="gameID" value="'.$game['gameId'] .'" disabled>Partie ['.$game['gameId'] . '] - '.$player['name'].' en attente d\'un autre joueur</button></br>';
        }else {
            $page .= '<button type="submit" name="gameID" value="'.$game['gameId'] .'">Partie ['.$game['gameId'] . '] - '.$player['name'].' en attente d\'un autre joueur</button></br>';
        }
    }
}


$page .= '
                    <input type="hidden" name="action" value="initialized">

                </form>
            </li>';

    $page .= '
            <li><h3>Parties terminées</h3>
                <form action="../traiteFormQuantik.php" method="post">
                    <input type="hidden" name="action" value="finished">
                    <input type="submit" value="Parties terminées">
                </form>
            </li></br>';

    $page .= '
            <li>
                <form action="../traiteFormQuantik.php" method="post">
                    <input type="hidden" name="action" value="deconnexion">
                    <input type="submit" value="Déconnexion">
                </form>
</li>
          </ul>
          <!-- Afficher la section des parties en cours -->
        </div>
      </body>
    </html>';

    echo $page;