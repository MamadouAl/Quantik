<?php
require_once 'QuantikGame.php';
require_once 'QuantikUIGenerator.php';
require_once 'PlateauQuantik.php';
require_once 'ArrayPieceQuantik.php';
require_once 'PieceQuantik.php';


$pg = new PlateauQuantik();
$qg = new QuantikGame();
$qg->plateau = $pg;
$qg->pieceBlack = ArrayPieceQuantik::initPiecesNoires();
$qg->pieceWhite = ArrayPieceQuantik::initPiecesBlanches();

// echo QuantikUIGenerator::getPageSelectionPiece($qg, 0);
echo QuantikUIGenerator::getPageVictoire($qg, 0, 0);
