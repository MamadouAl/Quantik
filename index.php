<?php

use Quantik2024\PDOQuantik;
use Quantik2024\QuantikGame;

require_once 'PHP/QuantikUIGenerator.php';
require_once 'PHP/PDOQuantik.php';
require_once './ressourcesQuantik/env/db.php';


session_start();
//session_unset();
if (!isset($_SESSION['player'])) {
    header('HTTP/1.1 303 See Other');
    header('Location: login.php');
    exit();
}
//header('refresh: 2');


//initialisation du plateau
//if(empty($_SESSION)) {
//    echo "Debut de la partie<br/>";
//    $_SESSION['plateau'] = new PlateauQuantik();
//    $_SESSION['QuantikGame'] = new QuantikGame();
//    $_SESSION['QuantikGame']->plateau = $_SESSION['plateau'];
//    $_SESSION['actionQ'] = new ActionQuantik($_SESSION['QuantikGame']->plateau);
//    $_SESSION['QuantikGame']->pieceBlack = ArrayPieceQuantik::initPiecesNoires();
//    $_SESSION['QuantikGame']->pieceWhite = ArrayPieceQuantik::initPiecesBlanches();
//    $_SESSION['QuantikGame']->gameStatus = "choixPiece";
//    $_SESSION['etat'] = $_SESSION['QuantikGame']->gameStatus;
//    $_SESSION['QuantikGame']->currentPlayer = 0;
//}

PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);

switch ($_SESSION['etatApp']) {
    case 'consultePartieEnCours':
        $gameID = $_SESSION['gameID'];
        $game = PDOQuantik::getGameQuantikById($gameID);
        // var_dump($game);
        $currentPlayer = $game->currentPlayer;

        $couleurPlayer = $game->couleurPlayer[$currentPlayer];
        $playerName = $game->getPlayers()[$couleurPlayer]->getName();
       
        // echo $currentPlayer;

        switch ($_SESSION['etat'] ) {
            case "choixPiece":
                // var_dump($game->gameStatus);
                if($game->gameStatus != "finished"){
                    echo "c'est a ".$game->getPlayers()[$couleurPlayer]->getName()." de jouer ";
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
               // echo QuantikUIGenerator::getPageSelectionPiece($game, $currentPlayer);

                break;
            case "posePiece":
                echo QuantikUIGenerator::getPagePosePiece($game, $currentPlayer, $_SESSION['piece']);
        
                $game->currentPlayer = ($currentPlayer + 1) % 2;
                break;
            }
        break;
    case 'Victoire':
        if($_SESSION['etat'] == 'consultePartieVictoire'){
            echo "<h1>Victoire</h1>";
            $gameID = $_SESSION['gameID'];
            $game = PDOQuantik::getGameQuantikById($gameID);
            $game->currentPlayer = ($game->currentPlayer + 1) % 2;
            $currentPlayer = $game->currentPlayer;
            $couleurPlayer = $game->couleurPlayer[$currentPlayer];
            $playerName = $game->getPlayers()[$couleurPlayer]->getName();
            echo "<h3>Le joueur <b>" . $playerName . "</b> a gagné</h3>";
            echo QuantikUIGenerator::getPageVictoire($game, $currentPlayer);
        }
        break;
    case 'home':
        header('Location: /ressourcesQuantik/quantik.php');
        break;
    default:
           echo AbstractUIGenerator::getPageErreur("Une erreur est survenue lors de la partie","");
    break;
}


/*
//Affiche c'est à qui de jouer, noir si 0, blanc si 1
echo '<h3>C\'est au joueur ' . ($_SESSION['QuantikGame']->currentPlayer == 0 ? '<b>Noir</b>' : '<b>Blanc</b>') . ' de jouer </h3>';
switch ($_SESSION['etat']) {
    case "choixPiece":
        echo QuantikUIGenerator::getPageSelectionPiece($_SESSION['QuantikGame'], $_SESSION['QuantikGame']->currentPlayer);

        break;
    case "posePiece":
        echo QuantikUIGenerator::getPagePosePiece($_SESSION['QuantikGame'], $_SESSION['QuantikGame']->currentPlayer, $_SESSION['piece']);

        $_SESSION['QuantikGame']->currentPlayer = ($_SESSION['QuantikGame']->currentPlayer + 1) % 2;
        break;
    case "finPartie":
              echo QuantikUIGenerator::getPageVictoire($_SESSION['QuantikGame'], ($_SESSION['QuantikGame']->currentPlayer+1)%2, $_SESSION['piece']);
        break;
    default:
        echo AbstractUIGenerator::getPageErreur("Une erreur est survenue lors de la partie","");
        break;
}*/