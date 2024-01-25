<?php
require_once 'PlateauQuantik.php';

class ActionQuantik {
    protected PlateauQuantik $plateau;

    /*
     * Constructeur qui initialise le plateau de jeu
     */
    public function __construct(PlateauQuantik $plateau) {
        $this->plateau = $plateau;
    }

    /**
     * Méthode pour obtenir le plateau de jeu
     * @return PlateauQuantik
     */
    public function getPlateau() : PlateauQuantik {
        return $this->plateau;
    }

    /**
     * Méthode pour savoir si une ligne est gagnante
     * @param int $numRow
     * @return bool
     */
    public function isRowWin(int $numRow): bool {
        $pieces = $this->plateau->getRow($numRow);
        return $this->isComboWin($pieces);
    }

    /**
     * Méthode pour savoir si une colonne est gagnante
     * @param int $numCol
     * @return bool
     */
    public function isColWin(int $numCol): bool {
        $pieces = $this->plateau->getCol($numCol);
        return $this->isComboWin($pieces);
    }

    /**
     * Méthode pour savoir si un coin est gagnant
     * @param int $dir
     * @return bool
     */
    public function isCornerWin(int $dir): bool {
        $pieces = $this->plateau->getCorner($dir);
        return $this->isComboWin($pieces);
    }

    /**
     * Méthode pour savoir si une pièce peut être posée à une position donnée
     * @param int $rowNum
     * @param int $colNum
     * @param PieceQuantik $piece
     * @return bool
     */
    public function isValidePose(int $rowNum, int $colNum, PieceQuantik $piece): bool {
        if($this->plateau->getPiece($rowNum, $colNum) != PieceQuantik::initVoid()){
            return false;
        }
        $pieceRow = $this->plateau->getRow($rowNum);
        $pieceCol = $this->plateau->getCol($colNum);
        $pieceCorner = $this->plateau->getCorner(PlateauQuantik::getCornerFromCoord($rowNum, $colNum));

        $resultat = $this->isPieceValide($pieceCorner, $piece) && $this->isPieceValide($pieceCol, $piece) && $this->isPieceValide($pieceRow, $piece) ;
        return $resultat;
    }

    /**
     * Méthode pour poser une pièce à une position donnée
     * @param int $rowNum
     * @param int $colNum
     * @param PieceQuantik $piece
     * @return void
     */
    public function posePiece(int $rowNum, int $colNum, PieceQuantik $piece): void {
        $this->plateau->setPiece($rowNum, $colNum, $piece);
    }


    public function __toString(): string {
        return $this->plateau->__toString();
    }

    /**
     * Méthode privée pour savoir si une pièce est valide
     * @param ArrayPieceQuantik $pieces
     * @param PieceQuantik $p
     * @return bool
     */
    private static function isPieceValide(ArrayPieceQuantik $pieces, PieceQuantik $p): bool {
        $resultat = true;
        foreach ($pieces as $piece){
            if($piece->getForme() == $p->getForme() || $piece->getCouleur() == $p->getCouleur()){
                $resultat = false;
            }
        }
        return $resultat;
    }

    private static function isComboWin(ArrayPieceQuantik $pieces): bool {
        for($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            $tab[$i] = false;
        }
        for($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            $temp = $pieces[$i]->getForme()-1; //on récupère la forme de la pièce et si elle est vide on met -1
            if($temp > -1) { //on vérifie que la pièce n'est pas vide
                $tab[$i] = true;
            }
        }
        for($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            if($tab[$i] == false) {
                return false;
            }
        }
        return true;
    }

}