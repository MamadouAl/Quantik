<?php
require_once 'PlateauQuantik.php';

class ActionQuantik
{
    private $plateau;

    public function __construct(PlateauQuantik $plateau)
    {
        $this->plateau = $plateau;
    }

    public function getPlateau() : PlateauQuantik
    {
        return $this->plateau;
    }

    public function isRowWin(int $numRow): bool
    {
        $pieces = $this->plateau->getRow($numRow);

        // Vérifier si les 4 pièces de la ligne ont des formes différentes
        return count(array_unique(array_map(fn($piece) => $piece->getForme(), $pieces))) === 4;
    }

    public function isColWin(int $numCol): bool
    {
        $pieces = $this->plateau->getCol($numCol);

        // Vérifier si les 4 pièces de la colonne ont des formes différentes
        return count(array_unique(array_map(fn($piece) => $piece->getForme(), $pieces))) === 4;
    }

    public function isCornerWin(int $dir): bool
    {
        $pieces = $this->plateau->getCorner($dir);

        // Vérifier si les 4 pièces du coin ont des formes différentes
        return count(array_unique(array_map(fn($piece) => $piece->getForme(), $pieces))) === 4;
    }
/*
    public function isValidePose(int $rowNum, int $colNum, PieceQuantik $piece): bool
    {
        // Vérifier la ligne
        for ($col = 0; $col < PlateauQuantik::NBROWS; $col++) {
            if ($this->plateau->getPiece($rowNum, $col)->getForme() === $piece->getForme()) {
                return false; // Une pièce de la même forme est déjà présente dans la ligne
            }
        }

        // Vérifier la colonne
        for ($row = 0; $row < PlateauQuantik::NBCOLS; $row++) {
            if ($this->plateau->getPiece($row, $colNum)->getForme() === $piece->getForme()) {
                return false;
            }
        }

        // Si aucune pièce de la même forme n'est trouvée dans la ligne ou la colonne, la pose est valide
        return true;
    }*/

    public function isValide(int $rowNum, int $colNum, PieceQuantik $piece): bool
    {
        // verifier la case est dispo
        if (PlateauQuantik::getPiece($rowNum, $colNum) == PieceQuantik::VOID) {
            return false;
        }
        //si la ligne et colonne peut etre joue
        if(!$this->isRowWin($rowNum) || !$this->isColWin($colNum)) {
            return false;
        }
        return true;
    }
}