<?php
require_once 'PHP/QuantikUIGenerator.php';
session_start();

//si on a cliqué sur une pièce
if (isset($_POST['piece'])) {
    $_SESSION['pieceNoire'] = $_POST['piece'];
    $_SESSION['etat'] = 'posePiece';
    header('Location: index.php');
}
if (isset($_POST['posePiece'])) {
    $pos = explode(',', $_POST['posePiece']); //on récupère les coordonnées
    $piece = $_SESSION['QuantikGame']->pieceBlack->getPieceQuantiks($_SESSION['pieceNoire']);
    $_SESSION['actionQ']->posePiece($pos[0], $pos[1], $piece);
    //mettre une pièce vide à la place de la pièce choisie
    $_SESSION['QuantikGame']->pieceBlack->setPieceQuantiks($_SESSION['pieceNoire'], PieceQuantik::initVoid());

    $_SESSION['etat'] = 'choixPiece';
    $_SESSION['QuantikGame']->currentPlayer = ($_SESSION['QuantikGame']->currentPlayer + 1) % 2;
    header('Location: index.php');
}
