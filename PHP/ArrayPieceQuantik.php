<?php

require 'PieceQuantik.php';
class ArrayPieceQuantik implements ArrayAccess, Countable
{
    // #pieceQuantik : PieceQuantik[]
    protected $pieceQuantik;

    public function __construct()
    {
        $this->pieceQuantik = array(pieceQuantik::initVoid());
    }

    public function getPieceQuantik(int $position)
    {
        return $this->pieceQuantik[$position];
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
    public static function initPiecesNoires(): ArrayPieceQuantik
    {
        $arrayPieceQuantik = new ArrayPieceQuantik();
        for ($i = 0; $i < 8; $i++) {
            //en utilisant initBlackCube()
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCylindre());
        }
        return $arrayPieceQuantik;
    }

    public static function initPiecesBlanches(): ArrayPieceQuantik
    {
        $arrayPieceQuantik = new ArrayPieceQuantik();
        for ($i = 0; $i < 8; $i++) {
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCylindre());
        }
        return $arrayPieceQuantik;
    }

    public function offsetGet($offset): PieceQuantik
    {
        return $this->pieceQuantik[$offset];
    }

    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->pieceQuantik);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->pieceQuantik[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->pieceQuantik[] = $value;
        } else {
            $this->pieceQuantik[$offset] = $value;
        }
    }
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->pieceQuantik[$offset]);
    }

    public function __toString(): string
    {
        $str = "";
        for ($i = 0; $i < count($this->pieceQuantik); $i++) {
            $str .= $this->pieceQuantik[$i];
        }
        return $str;
    }
}