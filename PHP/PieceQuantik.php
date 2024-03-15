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
    private function __construct($couleur, $forme) {
        $this->couleur = $couleur;
        $this->forme = $forme;
    }

    /**
     * Permet de récupérer la couleur d'une pièce Quantik
     * @return mixed
     */
    public function getCouleur() : int{
        return $this->couleur;
    }

    /**
     *  Permet de modifier la couleur d'une pièce Quantik
     * @param $couleur
     * @return void
     */
    public function setCouleur($couleur) : void{
        $this->couleur = $couleur;
    }
    /**
     * Permet de récupérer la forme d'une pièce Quantik
     * @return mixed
     */
    public function getForme(){
        return $this->forme;
    }
   /**
     * Permet de modifier la forme d'une pièce Quantik
     * @param $forme
     * @return void
     */
    public function setForme($forme): void {
        $this->forme = $forme;
    }

    /**
     * Permet d'initialiser une pièce Quantik vide
     * @return PieceQuantik
     */
    public static function initVoid(): PieceQuantik {
        return new PieceQuantik(self::VOID, null);
    }

    /**
     * Permet d'initialiser une pièce Quantik de couleur blanche et de forme cube
     * @return PieceQuantik
     */
    public static function initWhiteCube(): PieceQuantik {
        return new PieceQuantik(self::WHITE, self::CUBE);
    }

    /**
     * Permet d'initialiser une pièce Quantik de couleur noire et de forme cube
     * @return PieceQuantik
     */
    public static function initBlackCube(): PieceQuantik {
        return new PieceQuantik(self::BLACK, self::CUBE);
    }
    
    public static function initWhiteCone(): PieceQuantik
    {
        return new PieceQuantik(self::WHITE, self::CONE);
    }

    public static function initBlackCone(): PieceQuantik
    {
        return new PieceQuantik(self::BLACK, self::CONE);
    }

    public static function initWhiteCylindre(): PieceQuantik
    {
        return new PieceQuantik(self::WHITE, self::CYLINDRE);
    }

    public static function initBlackCylindre(): PieceQuantik
    {
        return new PieceQuantik(self::BLACK, self::CYLINDRE);
    }

    public static function initWhiteSphere(): PieceQuantik
    {
        return new PieceQuantik(self::WHITE, self::SPHERE);
    }

    public static function initBlackSphere(): PieceQuantik
    {
        return new PieceQuantik(self::BLACK, self::SPHERE);
    }
    /**
     * Permet de représenter une pièce Quantik sous forme de chaine de caractères
     * @return string
     */
    public function __toString()
    {
        $couleurText = '';
        if($this->couleur==self::WHITE){
            $couleurText='Wh';
        }
        else if($this->couleur==self::BLACK){
            $couleurText='Bl';
        }
        else if($this->couleur==self::VOID){
            $couleurText='VD';
        }
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

    /* TODO implantation schéma UML */
    public function getJson(): string {
        if (is_null($this->forme))
            return '{"forme":null,"couleur":'.$this->couleur. '}';
        else
            return '{"forme":'. $this->forme . ',"couleur":'.$this->couleur. '}';
    }

    public static function initPieceQuantik(string|object $json): PieceQuantik {
        if (is_string($json)) {
            $props = json_decode($json, true);
            return new PieceQuantik($props['couleur'], $props['forme']);
        }
        else
            return new PieceQuantik($json->couleur, $json->forme);
    }
}


//$piece = PieceQuantik::initWhiteCube();
//echo $piece->getJson()."\n";
//$test = PieceQuantik::initPieceQuantik($piece->getJson());
//echo $test->__toString()."\n";