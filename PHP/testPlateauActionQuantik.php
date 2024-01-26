<?php

require 'PlateauQuantik.php';
require_once 'ActionQuantik.php';

$plateau = new PlateauQuantik();
/*
// Initialiser le plateau avec des pièces noires
$plateau->setPiece(0, 0, PieceQuantik::initBlackCylindre());
$plateau->setPiece(0, 1, PieceQuantik::initBlackCube());
$plateau->setPiece(0, 2, PieceQuantik::initBlackCone());
$plateau->setPiece(0, 3, PieceQuantik::initBlackSphere());


// Initialiser le plateau avec des pièces blanches
$plateau->setPiece(1, 0, PieceQuantik::initWhiteCone());
$plateau->setPiece(1, 1, PieceQuantik::initWhiteSphere());
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
$piece = PieceQuantik::initVoid();
$plateau->setPiece(3, 3, $piece);
*/

// Initialiser le plateau avec des pièces noires
$plateau->setPiece(0, 0, PieceQuantik::initBlackCylindre());
$plateau->setPiece(0, 1, PieceQuantik::initVoid());
$plateau->setPiece(0, 2, PieceQuantik::initVoid());
$plateau->setPiece(0, 3, PieceQuantik::initVoid());


// Initialiser le plateau avec des pièces blanches
$plateau->setPiece(1, 0, PieceQuantik::initVoid());
$plateau->setPiece(1, 1, PieceQuantik::initVoid());
$plateau->setPiece(1, 2, PieceQuantik::initVoid());
$plateau->setPiece(1, 3, PieceQuantik::initVoid());

// Initialiser le plateau avec des pièces noires
$plateau->setPiece(2, 0, PieceQuantik::initBlackCube());
$plateau->setPiece(2, 1, PieceQuantik::initVoid());
$plateau->setPiece(2, 2, PieceQuantik::initVoid());
$plateau->setPiece(2, 3, PieceQuantik::initVoid());



// Initialiser le plateau avec des pièces blanches
$plateau->setPiece(3, 0, PieceQuantik::initBlackCone());
$plateau->setPiece(3, 1, PieceQuantik::initVoid());
$plateau->setPiece(3, 2, PieceQuantik::initVoid());
$plateau->setPiece(3, 3, PieceQuantik::initVoid());


// Afficher le plateau
$var = "<h2>Test de la Classe 'PlateauQuantik'</h2><hr/>";
for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
    $var .= "Row $i: " . $plateau->getRow($i) . "\n";
    $var .= "<br/>\n";
}

$var .= "<br/>\n";

// Tester la méthode getCorner()
for ($dir = 0; $dir < 4; $dir++) {
    $var .= "Corner $dir: " . $plateau->getCorner($dir) . "\n";
    $var .= "<br/>\n";
}
$var .= "<br/>\n";


//tester la méthode getCol()
for ($i = 0; $i < PlateauQuantik::NBCOLS; $i++) {
    $var .= "Col $i: " . $plateau->getCol($i) . "\n";
    $var .= "<br/>\n";
}

//tester la methode getCornerFromCoord()
$var .= "<br/>\n";
$var .= "getCornerFromCoord(2, 2): " . $plateau->getCornerFromCoord(2, 2) . "\n";
$var .= "<br/>\n";



$actionQuantik = new ActionQuantik($plateau);
$var .= "<h2>Test de la Classe 'ActionQuantik'</h2><hr/>";
$var .= "\nisRowWin(0): " . ($actionQuantik->isRowWin(0) ? 'true' : 'false') . "<br/>\n";
$var .= "isCornerWin(0): " . ($actionQuantik->isCornerWin(0) ? 'true' : 'false') . "<br/>\n";
$var .= "isColWin(0): " . ($actionQuantik->isColWin(0) ? 'true' : 'false') . "<br/>\n";
$p = PieceQuantik::initWhiteCylindre();
$var .="isValidePose(1, 0, $p): " . ($actionQuantik->isValidePose(1, 0, $p) ? 'true' : 'false') . "<br/>\n";



$actionQuantik->posePiece(2, 2, PieceQuantik::initBlackCylindre());
$var .= "<p>Plateau après pose de la pièce noire 'Cube' en (2, 2):</p>\n";
for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
    $var .= "Row $i: " . $plateau->getRow($i) . "<br/>\n";
}
$var .= "<br/>\n";
echo $var;
