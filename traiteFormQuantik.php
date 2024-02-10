<?php
require_once 'PHP/QuantikUIGenerator.php';
session_start();

if ($_GET['action'] == 'recommencer') {
    session_unset();
    header('Location: index.php');
}

//si on a cliqué sur une pièce
if (isset($_POST['piece'])) {
    $_SESSION['piece'] = $_POST['piece'];
    $_SESSION['etat'] = 'posePiece';
    header('Location: index.php');
}

// Vérifier si la partie est terminée
function checkVictoire(): bool
{
    $actionQ = $_SESSION['actionQ'];
    // Vérification les lignes
    for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
        if ($actionQ->isRowWin($i)) {
            return true;
        }
    }
    // Vérification les colonnes
    for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
        if ($actionQ->isColWin($j)) {
            return true;
        }
    }
    // Vérifier les coins
    for ($k = 0; $k < 4; $k++) {
        if ($actionQ->isCornerWin($k)) {
            return true;
        }
    }
    return false;
}


if (isset($_POST['posePiece'])) {
    $pos = explode(',', $_POST['posePiece']); //on récupère les coordonnées
    if($_SESSION['QuantikGame']->currentPlayer == 0){
        $piece = $_SESSION['QuantikGame']->pieceWhite->getPieceQuantiks($_SESSION['piece']);
        $_SESSION['actionQ']->posePiece($pos[0], $pos[1], $piece);
    }else{
        $piece = $_SESSION['QuantikGame']->pieceBlack->getPieceQuantiks($_SESSION['piece']);
        $_SESSION['actionQ']->posePiece($pos[0], $pos[1], $piece);
    }

    //mettre une pièce vide à la place de la pièce choisie
    if($_SESSION['QuantikGame']->currentPlayer == 0) {
        $_SESSION['QuantikGame']->pieceWhite->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());
        //$_SESSION['QuantikGame']->piceWhite ->removePieceQuantik($_SESSION['piece']);
    }else {
        $_SESSION['QuantikGame']->pieceBlack->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());
    }

    // Vérifier si la partie est terminée après avoir posé la pièce
    if (checkVictoire()) {
        $_SESSION['etat'] = 'finPartie';
        header('Location: index.php');
        exit; // Terminer le script
    }

    $_SESSION['etat'] = 'choixPiece';
    header('Location: index.php');
}

if (isset($_POST['action']) && $_POST['action'] == 'AnnulerChoixPiece') {
    $_SESSION['etat'] = 'choixPiece';
    $_SESSION['piece'] = null; //on annule le choix de la pièce
    $_SESSION['QuantikGame']->currentPlayer = ($_SESSION['QuantikGame']->currentPlayer + 1) % 2;
    header('Location: index.php');
}
