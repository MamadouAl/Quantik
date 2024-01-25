<?php

require 'PlateauQuantik.php';

$plateau = new PlateauQuantik();

// Initialiser le plateau avec des pièces noires
$plateau->setPiece(0, 0, PieceQuantik::initBlackCylindre());
$plateau->setPiece(0, 1, PieceQuantik::initBlackCube());
$plateau->setPiece(0, 2, PieceQuantik::initBlackCone());
$plateau->setPiece(0, 3, PieceQuantik::initBlackSphere());


// Initialiser le plateau avec des pièces blanches
$plateau->setPiece(1, 0, PieceQuantik::initWhiteCylindre());
$plateau->setPiece(1, 1, PieceQuantik::initWhiteCube());
$plateau->setPiece(1, 2, PieceQuantik::initWhiteCone());
$plateau->setPiece(1, 3, PieceQuantik::initWhiteSphere());

// Initialiser le plateau avec des pièces noires
$plateau->setPiece(2, 0, PieceQuantik::initBlackCylindre());
$plateau->setPiece(2, 1, PieceQuantik::initBlackCube());
$plateau->setPiece(2, 2, PieceQuantik::initBlackCone());
$plateau->setPiece(2, 3, PieceQuantik::initBlackSphere());

// Initialiser le plateau avec des pièces blanches
$plateau->setPiece(3, 0, PieceQuantik::initWhiteCylindre());
$plateau->setPiece(3, 1, PieceQuantik::initWhiteCube());
$plateau->setPiece(3, 2, PieceQuantik::initWhiteCone());
$plateau->setPiece(3, 3, PieceQuantik::initWhiteSphere());

// Afficher le plateau
for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
    echo "Row $i: " . $plateau->getRow($i) . "\n";
    echo "\n";
}
