<?php

class PieceQuantik
{
    /* Constantes de classe qui définissent
        les couleurs et les formes des pièces Quantik
    */
    const WHITE=0;
    const BLACK =1;
    const VOID=0;
    const CUBE=1;
    const CYLINDRE=3;
    const SPHERE=4;
    const CONE=2;

    private $couleur;
    private $forme;

    /**
     *  Constructeur de la classe permettant de créer une pièce Quantik
     * @param $couleur
     * @param $forme
     */
    private function __construct($couleur, $forme)
    {
        $this->couleur = $couleur;
        $this->forme = $forme;
    }

    /**
     * Permet de récupérer la couleur d'une pièce Quantik
     * @return mixed
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     *  Permet de modifier la couleur d'une pièce Quantik
     * @param $couleur
     * @return void
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    }
    public function getForme()
    {
        return $this->forme;
    }
    public function setForme($forme)
    {
        $this->forme = $forme;
    }
    public static function initVoid()
    {
        return new PieceQuantik(self::VOID, null);
    }

    public static function initWhiteCube()
    {
        return new PieceQuantik(self::WHITE, self::CUBE);
    }

    public static function initBlackCube()
    {
        return new PieceQuantik(self::BLACK, self::CUBE);
    }
    public static function initWhiteCone()
    {
        return new PieceQuantik(self::WHITE, self::CONE);
    }

    public static function initBlackCone()
    {
        return new PieceQuantik(self::BLACK, self::CONE);
    }

    public static function initWhiteCylindre()
    {
        return new PieceQuantik(self::WHITE, self::CYLINDRE);
    }

    public static function initBlackCylindre()
    {
        return new PieceQuantik(self::BLACK, self::CYLINDRE);
    }

    public static function initWhiteSphere()
    {
        return new PieceQuantik(self::WHITE, self::SPHERE);
    }

    public static function initBlackSphere()
    {
        return new PieceQuantik(self::BLACK, self::SPHERE);
    }




    public function __toString()
    {
        $couleurText = ($this->couleur === self::WHITE) ? 'WH' : 'BL';
        $formeText = '';

        switch ($this->forme) {
            case self::CUBE:
                $formeText = 'Cu';
                break;
            case self::CONE:
                $formeText = 'Co';
                break;
            case self::CYLINDRE:
                $formeText = 'Cy';
                break;
            case self::SPHERE:
                $formeText = 'Sph';
                break;
            default:
                $formeText = 'Aucune';
        }

        return "($couleurText $formeText) \n";
    }

}
