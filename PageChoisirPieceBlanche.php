<?php

require_once 'PHP/QuantikUIGenerator.php';

$pg = new PlateauQuantik();
$action = new ActionQuantik($pg);

$qg = new QuantikGame();
$qg->plateau = $pg;
$qg->pieceBlack = ArrayPieceQuantik::initPiecesNoires();
$qg->pieceWhite = ArrayPieceQuantik::initPiecesBlanches();
 echo QuantikUIGenerator::getPageSelectionPiece($qg, 1);
