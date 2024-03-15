<?php

use Quantik2024\PDOQuantik;
use Quantik2024\QuantikGame;

require_once 'PHP/QuantikUIGenerator.php';
require_once 'PHP/PDOQuantik.php';
require_once './ressourcesQuantik/env/db.php';


session_start();
if (!isset($_SESSION['player'])) {
    header('HTTP/1.1 303 See Other');
    header('Location: login.php');
    exit();
}
if(!isset($_SESSION['etatApp'])){
    $_SESSION['etatApp'] = 'home';
}

PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);

switch ($_SESSION['etatApp']) {
    case 'consultePartieEnCours':
        $gameID = $_SESSION['gameID'];
        $game = PDOQuantik::getGameQuantikById($gameID);
        $currentPlayer = $game->currentPlayer;

        $couleurPlayer = $game->couleurPlayer[$currentPlayer];
        $playerName = $game->getPlayers()[$couleurPlayer]->getName();

        switch ($_SESSION['etat'] ) {
            case "choixPiece":
                if($game->gameStatus != "finished"){
                    if($currentPlayer == 0){
                        echo "<p class='title is-3 is-centered '><b>Partie [".$_SESSION['gameID'] ."]</b>: C'est a <span style='color: #848B83'>".$game->getPlayers()[$couleurPlayer]->getName()."</span> de jouer</p> ";
                    }else{
                        echo "<p class='title is-3 is-centered '><b>Partie [".$_SESSION['gameID'] ."]</b>: C'est a <span style='color: #848B83'>".$game->getPlayers()[$couleurPlayer]->getName()."</span> de jouer</p> ";
                    }
                    if($game->getPlayers()[$couleurPlayer]->getId() === $_SESSION['player']->getId()){
                      
                        echo QuantikUIGenerator::getPageSelectionPiece($game, $currentPlayer);
                    }
                    else{
                        echo QuantikUIGenerator::getPageSelectionPieceGrisee($game, $currentPlayer);
                    }
                }else{
                    echo "<h1>Victoiree</h1>";
                    $currentPlayer = ($game->currentPlayer + 1) % 2;
                    $couleurPlayer = $game->couleurPlayer[$currentPlayer];
                    $playerName = $game->getPlayers()[$couleurPlayer]->getName();
                    $playerName = $game->getPlayers()[$couleurPlayer]->getName();
                    echo "<h3>Le joueur <b>" . $playerName . "</b> a gagné</h3>";
                    echo QuantikUIGenerator::getPageVictoire($game, $currentPlayer);
                }
                break;
            case "posePiece":
                echo QuantikUIGenerator::getPagePosePiece($game, $currentPlayer, $_SESSION['piece']);
        
                $game->currentPlayer = ($currentPlayer + 1) % 2;
                break;
            }
        break;
    case 'Victoire':
        if($_SESSION['etat'] == 'consultePartieVictoire'){
            $gameID = $_SESSION['gameID'];
            $game = PDOQuantik::getGameQuantikById($gameID);
            $game->currentPlayer = ($game->currentPlayer + 1) % 2;
            $currentPlayer = $game->currentPlayer;
            $couleurPlayer = $game->couleurPlayer[$currentPlayer];
            $playerName = $game->getPlayers()[$couleurPlayer]->getName();
            echo "<p class='title is-3 is-centered '><b>Partie [".$_SESSION['gameID'] ."]</b>: <span style='color: #848B83'>".$playerName."</span> a gagné cette partie !</p> ";
            echo QuantikUIGenerator::getPageVictoire($game, $currentPlayer);
        }
        break;
    case 'home':
        header('Location: quantik.php');
        break;
    default:
           echo AbstractUIGenerator::getPageErreur("Une erreur est survenue lors de la partie","");
    break;
}
