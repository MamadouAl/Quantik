<?php

use Quantik2024\PDOQuantik;
use Quantik2024\QuantikGame;

require_once 'PHP/QuantikUIGenerator.php';
require_once 'PHP/PDOQuantik.php';
require_once './ressourcesQuantik/env/db.php';


session_start();
    // session_unset();


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
        echo "<h1>Partie en cours</h1>";
        $gameID = $_SESSION['gameID'];
        $game = PDOQuantik::getGameQuantikById($gameID);
        // var_dump($game);
        $currentPlayer = $game->currentPlayer;
        $couleurPlayer = $game->couleurPlayer[$currentPlayer];
        echo $couleurPlayer;

        switch ($_SESSION['etat']) {
            case "choixPiece":
                echo QuantikUIGenerator::getPageSelectionPiece($game, $currentPlayer);
        
                break;
            case "posePiece":
                echo QuantikUIGenerator::getPagePosePiece($game, $currentPlayer, $_SESSION['piece']);
        
                // $_SESSION['QuantikGame']->currentPlayer = ($currentPlayer + 1) % 2;
                break;
            }
        break;
    case 'consultePartieVictoire':
        echo "<h1>Partie terminée</h1>";
        break;
    case 'home':
        echo "<h1>Bienvenue sur Home</h1>";
        break;
    default:
    echo "<h1>Erreur</h1>";
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