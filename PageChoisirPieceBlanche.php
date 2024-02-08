<?php
session_start();
$_SESSION['pageChoisirBlanche'] = $_SERVER['REQUEST_URI'];
require_once 'PHP/QuantikUIGenerator.php';

$pg = new PlateauQuantik();
$action = new ActionQuantik($pg);

$qg = new QuantikGame();
$qg->plateau = $pg;
$qg->pieceBlack = ArrayPieceQuantik::initPiecesNoires();
$qg->pieceWhite = ArrayPieceQuantik::initPiecesBlanches();
 echo QuantikUIGenerator::getPageSelectionPiece($qg, 1);
 
// if(isset($_SESSION['posePieceBlanche'])){
//     $piece = $qg->pieceBlack->getPieceQuantiks($_SESSION['pieceBlanche']);
//     $action->posePiece(0, 0, $piece);

// }