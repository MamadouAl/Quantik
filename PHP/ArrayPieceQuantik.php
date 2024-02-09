<?php

require_once 'PieceQuantik.php';
class ArrayPieceQuantik implements ArrayAccess, Countable
{
    protected $pieceQuantiks;

    /**
     * Constructeur de la classe permettant de créer un tableau de pièces Quantik
     */
    public function __construct()
    {
       // $this->pieceQuantik = array(pieceQuantik::initVoid());
        $this->pieceQuantiks = array();
    }

    /**
     * Permet de récupérer une pièce Quantik à une position donnée
     * @param int $position
     * @return mixed
     */
    public function getPieceQuantiks(int $position) : PieceQuantik
    {
        return $this->pieceQuantiks[$position];
    }

    /**
     * Permet de modifier une pièce Quantik à une position donnée dans le tableau
     * @param int $position
     * @param PieceQuantik $pieceQuantik
     * @return void
     */
    public function setPieceQuantiks(int $position, PieceQuantik $pieceQuantik)
    {
        $this->pieceQuantiks[$position] = $pieceQuantik;
    }

    /**
     * Permet d'ajouter une pièce Quantik dans le tableau
     * @param PieceQuantik $pieceQuantik
     * @return void
     */
    public function addPieceQuantik(PieceQuantik $pieceQuantik)
    {
        $this->pieceQuantiks[] = $pieceQuantik;
    }

    /**
     * Permet de supprimer une pièce Quantik à une position donnée dans le tableau
     * @param int $position
     * @return void
     */
    public function removePieceQuantik(int $position)
    {
        unset($this->pieceQuantiks[$position]);
    }

    /**
     * Permet de récupérer le tableau de pièces Quantik de couleuur noire
     * @return ArrayPieceQuantik
     */
    public static function initPiecesNoires(): ArrayPieceQuantik
    {
        $arrayPieceQuantik = new ArrayPieceQuantik();
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackSphere());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackSphere());
        return $arrayPieceQuantik;
    }

    /**
     * Permet de récupérer le tableau de pièces Quantik de couleuur blanche
     * @return ArrayPieceQuantik
     */
    public static function initPiecesBlanches(): ArrayPieceQuantik
    {
        $arrayPieceQuantik = new ArrayPieceQuantik();
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteSphere());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteSphere());

        return $arrayPieceQuantik;
    }

    // Méthode offsetGet pour l'interface ArrayAccess
    public function offsetGet($offset): PieceQuantik
    {
        // Vérifier si la clé existe avant d'accéder à l'élément du tableau
        if (array_key_exists($offset, $this->pieceQuantiks)) {
            return $this->pieceQuantiks[$offset];
        } else {
            // Gérer le cas où la clé n'existe pas, vous pouvez retourner une valeur par défaut ou lancer une exception
            throw new InvalidArgumentException("Cette clé n'existe pas : $offset");
        }
    }




    public function count() : int{
        return sizeof($this->pieceQuantiks);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->pieceQuantiks[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value): void{
        if (is_null($offset)) {
            $this->pieceQuantiks[] = $value;
        } else {
            $this->pieceQuantiks[$offset] = $value;
        }
    }
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset): void
    {
        unset($this->pieceQuantiks[$offset]);
    }

    /**
     * Permet d'afficher le tableau de pièces Quantik
     * @return string
     */
    public function __toString(): string
    {
        $str = "";
        for ($i = 0; $i < count($this->pieceQuantiks); $i++) {
            $str .= $this->pieceQuantiks[$i]." ";
        }
        return $str;
    }
}