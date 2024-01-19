<?php

class ArrayPieceQuantik implements ArrayAccess, Countable{
    // #pieceQuantik : PieceQuantik[]
    protected $pieceQuantik;

    public function __construct()
    {
        $this->pieceQuantik = new PieceQuantik();
    }

    public function getPieceQuantik(int $position)
    {
        return new pieceQuantik[$position];
    }

    public function setPieceQuantik(int $position, PieceQuantik $pieceQuantik)
    {
        $this->pieceQuantik[$position] = $pieceQuantik;
    }

    public function addPieceQuantik(PieceQuantik $pieceQuantik)
    {
        $this->pieceQuantik[] = $pieceQuantik;
    }

    public function removePieceQuantik(int $position)
    {
        unset($this->pieceQuantik[$position]);
    }

    // Génère les tableaux de 8 pièces de chaque couleur nécessaires au jeu
    public static function initPieceQuantikNoires(): ArrayPieceQuantik
    {
        $arrayPieceQuantik = new ArrayPieceQuantik();

        for ($i = 0; $i < 8; $i++) {
            $arrayPieceQuantik->addPieceQuantik(new PieceQuantik("noir"));
        }

        return $arrayPieceQuantik;
    }
}