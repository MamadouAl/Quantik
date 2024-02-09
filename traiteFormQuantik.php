<?php
require_once 'PHP/QuantikUIGenerator.php';
session_start();

//si on a cliqué sur une pièce
if (isset($_POST['piece'])) {
    $_SESSION['piece'] = $_POST['piece'];
    $_SESSION['etat'] = 'posePiece';

    header('Location: index.php');
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
    if($_SESSION['QuantikGame']->currentPlayer == 0)
         $_SESSION['QuantikGame']->pieceWhite->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());

    else
        $_SESSION['QuantikGame']->pieceBlack->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());


    $_SESSION['etat'] = 'choixPiece';
    header('Location: index.php');
}
