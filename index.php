<?php

require_once 'PHP/QuantikUIGenerator.php';

$pg = new PlateauQuantik();
$action = new ActionQuantik($pg);

$qg = new QuantikGame();
$qg->plateau = $pg;
$qg->pieceBlack = ArrayPieceQuantik::initPiecesNoires();
$qg->pieceWhite = ArrayPieceQuantik::initPiecesBlanches();
echo QuantikUIGenerator::getPageSelectionPiece($qg, 0);
//pose une pièce
$action->posePiece(0, 0, $qg->pieceBlack->getPieceQuantiks(0));
// echo QuantikUIGenerator::getPageVictoire($qg, 0, 0);
// echo QuantikUIGenerator::getPagePosePiece($qg, 1, 0);

