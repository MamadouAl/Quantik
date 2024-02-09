<?php
require_once 'PHP/QuantikUIGenerator.php';

session_start();
//session_unset();
//initialisation du plateau
if(empty($_SESSION)) {
    echo "Initialisation de la partie";
    $_SESSION['plateau'] = new PlateauQuantik();
    $_SESSION['QuantikGame'] = new QuantikGame();
    $_SESSION['QuantikGame']->plateau = $_SESSION['plateau'];
    $_SESSION['actionQ'] = new ActionQuantik($_SESSION['QuantikGame']->plateau);

    $_SESSION['QuantikGame']->pieceBlack = ArrayPieceQuantik::initPiecesNoires();
    $_SESSION['QuantikGame']->pieceWhite = ArrayPieceQuantik::initPiecesBlanches();
    $_SESSION['QuantikGame']->gameStatus = "choixPiece";
    $_SESSION['etat'] = $_SESSION['QuantikGame']->gameStatus;
    $_SESSION['QuantikGame']->currentPlayer = 0;
}
echo "Etat de la partie : " . $_SESSION['QuantikGame']->currentPlayer . "<br>";
switch ($_SESSION['etat']) {
    case "choixPiece":
        echo QuantikUIGenerator::getPageSelectionPiece($_SESSION['QuantikGame'], $_SESSION['QuantikGame']->currentPlayer);
        break;
    case "posePiece":
        echo QuantikUIGenerator::getPagePosePiece($_SESSION['QuantikGame'], $_SESSION['QuantikGame']->currentPlayer, $_SESSION['pieceNoire']);
        break;
    case "finPartie":
        echo QuantikUIGenerator::getPageVictoire($_SESSION['QuantikGame'], $_SESSION['QuantikGame']->currentPlayer);
        break;
    default:
        echo "Erreur";
        break;
}



