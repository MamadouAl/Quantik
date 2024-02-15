<?php
require_once 'AbstractGame.php';
require_once 'AbstractUIGenerator.php';
require_once 'PlateauQuantik.php';
require_once 'ArrayPieceQuantik.php';
require_once 'QuantikGame.php';
require_once 'ActionQuantik.php';

class QuantikUIGenerator extends AbstractUIGenerator
{
    protected static function getButtonClass(PieceQuantik $piece): string {
        // $buttonClass = 'default-button-class';
        switch ($piece->getForme()) {
            case PieceQuantik::CUBE:
                $buttonClass = 'cube';
                break;
            case PieceQuantik::CONE:
                $buttonClass = 'cone';
                break;
            case PieceQuantik::CYLINDRE:
                $buttonClass = 'cylindre';
                break;
            case PieceQuantik::SPHERE:
                $buttonClass = 'sphere';
                break;
            default:
                $buttonClass = 'default';
                break;
        }
        return $buttonClass;
    }

    /**
     * Permet de générer le code HTML pour représenter le plateau de jeu
     */
    protected static function getDivPlateauQuantik(PlateauQuantik $plateau): string {
        $divPlateau = '<div class="plateau-quantik">';
        $divPlateau .= '<p><table>';
    
        // Parcourir les lignes du plateau
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            $pieces = $plateau->getRow($i);
            $divPlateau .= '<tr>';
            for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
                $piece = $pieces[$j]; 
                if($piece->getForme() != null|| $piece->getCouleur() != null){
                    $couleur = $piece->getCouleur() == 0 ? 'couleurBlanche' : 'couleurNoire';
                }
                else{
                    $couleur='couleurvide';
                }
                // Ajout de styles personnalisés pour le bouton
                $divPlateau .= '<td style="text-align: center;">';
                $divPlateau .= '<button class="  '.self::getButtonClass($piece).' '.$couleur.'" type="submit" disabled >
                  '. $piece->__toString() .' 
                </button>';
                $divPlateau .= '</td>';
            }
            $divPlateau .= '</tr>';
        }
        $divPlateau .= '</table></p>';
        $divPlateau .= '</div>';
        return $divPlateau;
    }
    
    /**
     * recupère les pièces disponibles
     * @param ArrayPieceQuantik $pieces
     * @param int $pos
     * @return string
     */
    protected static function getDivPiecesDisponibles(ArrayPieceQuantik $pieces, int $pos=-1): string {
        $divPieces = '<div class="pieces-disponibles">';
        $cmp=0;
        for ($i = 0; $i < count($pieces); $i++) {

            if($pieces->getPieceQuantiks($i)->getForme() ==null)
                continue;
            $piece = $pieces->getPieceQuantiks($i);
            if($piece->getForme() != null|| $piece->getCouleur() != null){
                $couleur = $piece->getCouleur() == 0 ? 'couleurBlanche' : 'couleurNoire';
            }
            else{
                $couleur='couleurvide';
            }
            if($i==$pos){
                $divPieces .= '<button type="submit" name="active" disabled class="'.self::getButtonClass($piece).' '.$couleur.'" style="background-color: #848B83;">
                
                '.$piece->__toString() . '</button>';
    
                }else{
                    $divPieces .= '<button type="submit" name="active" disabled class="'.self::getButtonClass($piece).'  '.$couleur.'" > 
                   
                    '.$piece->__toString() . '</button>';
                }

                $cmp++;
                if($cmp==4){
                    $divPieces .= '</br>';
                }


        }
        $divPieces .= '</div>';
        return $divPieces;
    }


    protected static function getFormSelectionPiece(ArrayPieceQuantik $pieces): string {
        $form = '<form action="traiteFormQuantik.php" method="post">';
        $cmp=0;
        for ($i = 0; $i < count($pieces); $i++) {
            if($pieces->getPieceQuantiks($i)->getForme() != null|| $pieces->getPieceQuantiks($i)->getCouleur() != null){
                $couleur = $pieces->getPieceQuantiks($i)->getCouleur() == 0 ? 'couleurBlanche' : 'couleurNoire';
            }
            else{
                $couleur='couleurvide';
            }
            if($pieces->getPieceQuantiks($i)->getForme() ==null)
                continue;
            $form .= '<button type="submit" name="piece" enabled class="'.self::getButtonClass($pieces->getPieceQuantiks($i)).' '.$couleur.'" value="' . $i . '" >
            ' . $pieces->getPieceQuantiks($i) . '</button>';
            $cmp++;
                if($cmp== 4){
                    $form .= '</br>';
                }
        }
        $form .= '</form>';
        return $form;
    }

    protected static function getFormPlateauQuantik(PlateauQuantik $plateau, PieceQuantik $piece): string {
        $actionQuantik = new ActionQuantik($plateau);

        $form = '<form action="traiteFormQuantik.php" method="post">';
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
               $p = $plateau->getPiece($i, $j);
                if($p->getForme() != null|| $p->getCouleur() != null){
                    $couleur = $p->getCouleur() == 0 ? 'couleurBlanche' : 'couleurNoire';
                }
                else{
                    $couleur='couleurvide';
                }
                if ($actionQuantik->isValidePose($i, $j, $piece)) {
                    $form .= '<button type="submit" name="posePiece" value="' . $i . ',' . $j . '" enabled class="'.self::getButtonClass($plateau->getPiece($i, $j)).' '.$couleur.'" > <span  style="background-color:#C7F1B5">'   
                    . $plateau->getPiece($i, $j)->__toString() . 

                    '
                    </span>
                    </button>';
                } else {
                    $form .= '<button type="submit" name="posePiece" value="' . $i . ',' . $j . '" disabled class="'.self::getButtonClass($plateau->getPiece($i, $j)).' '.$couleur.' " style="background-color:#FEEEEE">'
                        . $plateau->getPiece($i, $j) . '</button>';
                }
                
                
            }
            $form.="<br/>";
        }
        $form .= '</form>';
        return $form;
      
    } 

    protected static function getFormBoutonAnnulerChoixPiece(): string {
        $res = '<div>  Changer la pièce </br>';
        $res .= '<form action="traiteFormQuantik.php" method="post">';
        $res .= '<input type="hidden" name="action" value="AnnulerChoixPiece">';
        $res .= '<button type="submit">Annuler</button>';
        $res .= '</form>';
        $res .= '</div>';
        return $res;
    }

    /**
     * Permet d'afficher le message de victoire
     * @param int $couleur
     * @return string
     */
    protected static function  getDivMeessageVictoire(int $couleur): string {
        $res ="<div>";
        if($couleur == 0){
            $res .= "<p> Les Noirs ont remporté la partie </p>";
        }else if ($couleur == 1){
            $res .=  "<p> Les Blancs ont remporté la partie </p>";
        }
        $res .= self::getLienRecommencer()."</div>";
        return $res;
    }

    /**
     * Permet d'afficher le lien pour recommencer la partie
     * @return string
     */
    protected static function getLienRecommencer(): string {
        return '<a href="traiteFormQuantik.php?action=recommencer">Recommencer</a>';
    }

    /**
     * Selectionne la page à afficher en fonction de l'état du jeu
     * @param QuantikGame $quantik
     * @param int $couleurActive
     * @return string
     */
    public static function getPageSelectionPiece(QuantikGame $quantik, int $couleurActive): string {

        $page = self::getDebutHTML('Quantik - Sélection de pièce');
        $page .= '<div class="container">';

        $page .='<div class="columns">';
        $page .='<div class="column is-6 box is-bordered">';
        if($couleurActive == 0){  // si c'est au tour des noirs
            $page .= self::getFormSelectionPiece($quantik->pieceBlack);
            $page .='</br>';
            $page .='</br>';
            $page .='</br>';
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite);
        }else if($couleurActive == 1){ // si c'est au tour des blancs
            $page .= self::getDivPiecesDisponibles($quantik->pieceBlack);
            $page .='</br>';
            $page .='</br>';
            $page .='</br>';

            $page .= self::getFormSelectionPiece($quantik->pieceWhite);

        }
        $page .= '</div>';

        $page .= '<div class="column is-6 box is-bordered">';
        $page .= self::getDivPlateauQuantik($quantik->plateau);
        $page .= '</div>';

        $page .= '</div>';
        $page .= '</div>';
        $page .= self::getFinHTML();
        return $page;
    }


    public static function getPagePosePiece(QuantikGame $quantik, int $couleurActive, int $posSelection): string {
        //doit retourner la page permettant au jours de poser la pièce dans le plateau avec l'option d'annuler le choix de la pièce
        $page = self::getDebutHTML('Quantik - Pose de pièce');
        $page .= '<div class="container">';

        $page .='<div class="columns">';
        $page .='<div class="column is-6 has-border">';

        if($couleurActive==0){
            $page .= self::getDivPiecesDisponibles($quantik->pieceBlack, $posSelection);
            $page .= '</br>';
            $page .= '</br>';
            $page .= '</br>';
            $page .=self::getDivPiecesDisponibles($quantik->pieceWhite);
            $page .= '</div>';
            $page .='<div class="column is-6 has-border">';
            $page .= self::getFormPlateauQuantik($quantik->plateau, $quantik->pieceBlack->getPieceQuantiks($posSelection));
            $page .= '</div>';
        }else if($couleurActive==1){
            $page .= self::getDivPiecesDisponibles($quantik->pieceBlack);
            $page .= '</br>';
            $page .= '</br>';
            $page .= '</br>';
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite, $posSelection);
            $page .= '</div>';
            $page .='<div class="column is-6 has-border">'; 
            $page .= self::getFormPlateauQuantik($quantik->plateau, $quantik->pieceWhite->getPieceQuantiks($posSelection));
        $page .= '</div>';
        }
        $page .= '</div>';

        $page .= '<div class="has-text-centered">';
        $page .= self::getFormBoutonAnnulerChoixPiece();
        $page .= '</div>';

        $page .= '</div>';
        $page .= self::getFinHTML();
        return $page;
    }
    
    /**
     * Genere la page de victoire
     */
    public static function getPageVictoire(QuantikGame $quantik, int $couleurActive, int $posSelection): string {
        $page = self::getDebutHTML('Quantik - Victoire');
        $page .= '<div class="container">';

        $page .='<div class="columns">';
        $page .='<div class="column is-6 box is-bordered">';
        if($couleurActive==0){
           
        $page .= self::getDivPiecesDisponibles($quantik->pieceBlack , $posSelection);
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite);}
        else if($couleurActive==1){
            $page .= self::getDivPiecesDisponibles($quantik->pieceBlack);
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite, $posSelection);
        }
        $page .= '</div>';
        $page .='<div class="column is-6 box is-bordered">';
        $page .= self::getDivPlateauQuantik($quantik->plateau);
        $page .= '</div>';
        $page .= '</div>';
        $page .= '<div class="has-text-centered">';
        $page .= self::getDivMeessageVictoire($couleurActive);
        $page .= '</div>';
        $page .= '</div>';
        $page .= self::getFinHTML();
        return $page;
    }
}
