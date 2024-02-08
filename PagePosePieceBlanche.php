<?php
session_start();
$_SESSION['pagePoseBlanche'] = $_SERVER['REQUEST_URI'];
require_once 'PHP/QuantikUIGenerator.php';

$pg = new PlateauQuantik();
$action = new ActionQuantik($pg);

$qg = new QuantikGame();
$qg->plateau = $pg;
$qg->pieceBlack = ArrayPieceQuantik::initPiecesNoires();
$qg->pieceWhite = ArrayPieceQuantik::initPiecesBlanches();

$action->posePiece(0, 0, $qg->pieceBlack->getPieceQuantiks(0));
echo QuantikUIGenerator::getPagePosePiece($qg, 1, $_SESSION['pieceBlanche']);

