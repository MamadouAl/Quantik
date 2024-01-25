<?php

require 'ArrayPieceQuantik.php';

class PlateauQuantik {
    const NW = 0;
    const NE = 1;
    const SW = 2;
    const SE = 3;
    const NBROWS = 4;
    const NBCOLS = 4;

    private $board; // Tableau représentant le plateau de jeu

    /**
     * Constructeur qui initialise le plateau de jeu
     */
    public function __construct() {
        // Initialisez le plateau avec des instances d'ArrayPieceQuantik
        for ($i = 0; $i < self::NBROWS; $i++) {
            for ($j = 0; $j < self::NBCOLS; $j++) {
                $this->board[$i][$j] = PieceQuantik::initVoid();
            }
        }
    }


    /**
     * Méthode pour obtenir une ligne du plateau
     * @param $rowNumber
     * @return ArrayPieceQuantik
     */
    public function getRow($rowNumber): ArrayPieceQuantik {
        $row = new ArrayPieceQuantik();
        for ($i = 0; $i < self::NBCOLS; $i++) {
            $piece = $this->getPiece($rowNumber, $i);
            $row->addPieceQuantik($piece);
        }
        return $row;
    }

    /**
     *  Méthode pour obtenir une colonne du plateau
     * @param $colNumber
     * @return ArrayPieceQuantik
     */
    public function getCol($colNumber): ArrayPieceQuantik {
        $column = new ArrayPieceQuantik();
        for ($i = 0; $i < self::NBROWS; $i++) {
            $column->addPieceQuantik($this->getPiece($i, $colNumber));
        }
        return $column;
    }

    /**
     *  Méthode pour obtenir un coin du plateau
     * @param int $dir
     * @return ArrayPieceQuantik
     */
    public function getCorner(int $dir): ArrayPieceQuantik {
        switch ($dir) {
            case self::NW:
                return $this->getCornerNW();
            case self::NE:
                return $this->getCornerNE();
            case self::SW:
                return $this->getCornerSW();
            case self::SE:
                return $this->getCornerSE();
            default:
                throw new InvalidArgumentException("Coin de plateau invalide");
        }
    }

    /**
     *  Méthode privée pour obtenir le coin NW du plateau
     * @return ArrayPieceQuantik
     */
    private function getCornerNW(): ArrayPieceQuantik
    {
        $corner = new ArrayPieceQuantik();
        for ($i = 0; $i < self::NBROWS / 2; $i++) {
            for ($j = 0; $j < self::NBCOLS / 2; $j++) {
                // Assurez-vous d'appeler addPieceQuantik avec un objet PieceQuantik
                $corner->addPieceQuantik($this->board[$i][$j]);
            }
        }
        return $corner;
    }

    /**
     * Méthode privée pour obtenir le coin NE du plateau
     * @return ArrayPieceQuantik
     */
    private function getCornerNE(): ArrayPieceQuantik {
        $corner = new ArrayPieceQuantik();
        for ($i = 0; $i < self::NBROWS / 2; $i++) {
            for ($j = self::NBCOLS / 2; $j < self::NBCOLS; $j++) {
                $corner->addPieceQuantik($this->board[$i][$j]);
            }
        }
        return $corner;
    }

    /**
     * Méthode privée pour obtenir le coin SW du plateau
     * @return ArrayPieceQuantik
     */
    private function getCornerSW(): ArrayPieceQuantik {
        $corner = new ArrayPieceQuantik();
        for ($i = self::NBROWS / 2; $i < self::NBROWS; $i++) {
            for ($j = 0; $j < self::NBCOLS / 2; $j++) {
                $corner->addPieceQuantik($this->board[$i][$j]);
            }
        }
        return $corner;
    }

    /**
     * Méthode privée pour obtenir le coin SE du plateau
     * @return ArrayPieceQuantik
     */
    private function getCornerSE(): ArrayPieceQuantik {
        $corner = new ArrayPieceQuantik();
        for ($i = self::NBROWS / 2; $i < self::NBROWS; $i++) {
            for ($j = self::NBCOLS / 2; $j < self::NBCOLS; $j++) {
                $corner->addPieceQuantik($this->board[$i][$j]);
            }
        }
        return $corner;
    }

    /**
     * Méthode pour obtenir un coin du plateau à partir d'une coordonnée
     * @param $x
     * @param $y
     * @return int
     */
    public static function getCornerFromCoord($x, $y): int {
        if ($x < self::NBCOLS / 2) {
            return ($y < self::NBROWS / 2) ? self::NW : self::SW;
        } else {
            return ($y < self::NBROWS / 2) ? self::NE : self::SE;
        }
    }

    /**
     * Méthode pour obtenir une pièce à une position spécifique
     * @param $row
     * @param $col
     * @return PieceQuantik
     */
    public function getPiece($row, $col): PieceQuantik
    {
        return $this->board[$row][$col];
    }

    /**
     * Méthode pour modifier une pièce à une position spécifique
     * @param $row
     * @param $col
     * @param PieceQuantik $piece
     * @return void
     */
    public function setPiece($row, $col, PieceQuantik $piece) : void {
        $this->board[$row][$col] = $piece;
    }
}