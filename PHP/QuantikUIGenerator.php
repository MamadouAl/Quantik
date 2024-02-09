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
        $buttonClass = 'default-button-class';
        switch ($piece->getForme()) {
            case PieceQuantik::CUBE:
                $buttonClass = 'cube-button';
                break;
            case PieceQuantik::CONE:
                $buttonClass = 'cone-button';
                break;
            case PieceQuantik::CYLINDRE:
                $buttonClass = 'cylindre-button';
                break;
            case PieceQuantik::SPHERE:
                $buttonClass = 'sphere-button';
                break;
            default:
                $buttonClass = 'empty-button';
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
                    $divPlateau .= "<td><button type='submit' disabled>{$piece->__toString()}</button></td>";
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
        for ($i = 0; $i < count($pieces); $i++) {
            if($pieces->getPieceQuantiks($i)->getCouleur()== PieceQuantik::VOID)
                continue;
            $piece = $pieces->getPieceQuantiks($i);
            if($i==$pos){
                $divPieces .= '<button type="submit" name="active" disabled class="button is-dark"> '.$piece->__toString() . '</button>';
    
                }else{
                    $divPieces .= '<button type="submit" name="active" disabled > '.$piece->__toString() . '</button>';
                }

            if($i == 3){
                $divPieces .= '</br>';
            }


        }
        $divPieces .= '</div>';
        return $divPieces;
    }


    protected static function getFormSelectionPiece(ArrayPieceQuantik $pieces): string {
        $form = '<form action="traiteFormQuantik.php" method="post">';
        for ($i = 0; $i < count($pieces); $i++) {
            if($pieces->getPieceQuantiks($i)->getCouleur()== PieceQuantik::VOID)
                continue;
            $form .= '<button type="submit" name="piece" enabled value="' . $i . '">'
                . $pieces->getPieceQuantiks($i) . '</button>';
                if($i == 3){
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
                if ($actionQuantik->isValidePose($i, $j, $piece)) {
                    $form .= '<button type="submit" name="posePiece" value="' . $i . ',' . $j . '" enabled>'
                        . $plateau->getPiece($i, $j)->__toString() . '</button>';
                } else {
                    $form .= '<button type="submit" name="posePiece" value="' . $i . ',' . $j . '" disabled>'
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
        $page .='<div class="column is-6">';
        if($couleurActive == 0){  // si c'est au tour des noirs
            $page .= self::getFormSelectionPiece($quantik->pieceBlack);
            $page .='</br>';
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite);
        }else if($couleurActive == 1){ // si c'est au tour des blancs
            $page .= self::getFormSelectionPiece($quantik->pieceBlack);
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite);

        }
        $page .= '</div>';

        $page .= '<div class="column is-6">';
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
        $page .='<div class="column is-6">';

        if($couleurActive==0){
            $page .= self::getDivPiecesDisponibles($quantik->pieceBlack, $posSelection);
            $page .=self::getDivPiecesDisponibles($quantik->pieceWhite);
            $page .= '</div>';
            $page .='<div class="column is-6">';
            $page .= self::getFormPlateauQuantik($quantik->plateau, $quantik->pieceBlack->getPieceQuantiks($posSelection));
            $page .= '</div>';
        }else if($couleurActive==1){
            $page .= self::getDivPiecesDisponibles($quantik->pieceBlack);
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite, $posSelection);
            $page .= '</div>';
            $page .='<div class="column is-6">'; 
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
        $page .='<div class="column is-6">';
        if($couleurActive==0){
           
        $page .= self::getDivPiecesDisponibles($quantik->pieceBlack , $posSelection);
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite);}
        else if($couleurActive==1){
            $page .= self::getDivPiecesDisponibles($quantik->pieceBlack);
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite, $posSelection);
        }
        $page .= '</div>';
        $page .='<div class="column is-6">';
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
