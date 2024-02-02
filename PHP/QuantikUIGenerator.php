<?php
require_once 'AbstractUIGenerator.php';
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
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++)
            $pieces = $plateau->getRow($i);

        $divPlateau = '<div class="plateau-quantik">';
        $divPlateau .= '<p><table>';
        foreach ($pieces as $row) {
            $divPlateau .= '<tr>';
            foreach ($row as $piece) {
                $divPlateau .= "<td>"
                    ."<button type='submit' disabled >".$piece."</button>"
                    ."</td>";
            }
            $divPlateau .= '</tr>';
        }
        $divPlateau .= '</table></p>';
        $divPlateau .= '</div>';
        return $divPlateau;
    }

    protected static function getDivPiecesDisponibles(ArrayPieceQuantik $pieces, int $player): string {
        $divPieces = '<div class="pieces-disponibles">';

        foreach ($pieces as $piece) {
            // Vérifiez si la pièce est disponible pour le joueur spécifié
            if ($piece->isAvailableForPlayer($player)) {
                $divPieces .= '<button type="submit" name="active" enabled >
                (' . $piece->getRepresentation() . ')</button>';
            }
        }
        $divPieces .= '</div>';
        return $divPieces;
    }


    protected static function getFormSelectionPiece(ArrayPieceQuantik $pieces): string {
        $form ='';
        for ($i = 0; $i < count($pieces); $i++) {
            $form .= '<bouton type="submit" name="piece" value="' . $i . '" disabled>'
                . $pieces->getPieceQuantiks($i) . '</bouton>';
        }
        return $form;
    }

    protected static function getFormPlateauQuantik(PlateauQuantik $plateau, PieceQuantik $piece): string {
        //TODO dans cette methode  il doit me renoyer le plateau en activant les case jouable seulement. pour savoir si une case est jouable on doit utiliser la methode isValidePose. isvalidPose prend un arrayPieceQuantik et un PieceQuantik en parametre et retourne un boolean  
       
        $actionQuantik = new ActionQuantik($plateau);
       
        $form = '<form action="traiteFormQuantik.php" method="post">';
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
            for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
                if ($actionQuantik->isValidePose($i, $j, $piece)) {
                    $form .= '<button type="submit" name="pos" value="' . $i . ',' . $j . '" enabled>'
                        . $plateau->getPiece($i, $j) . '</button>';
                } else {
                    $form .= '<button type="submit" name="pos" value="' . $i . ',' . $j . '" disabled>'
                        . $plateau->getPiece($i, $j) . '</button>';
                }
            }
        }
        $form .= '</form>';
        return $form;
      
    } 
    
    
    
    

    protected static function getFormBoutonAnnulerChoixPiece(): string {
        $res = '"<div>  Changer la pièce </br>"';
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
        if($couleurActive == 0){  // si c'est au tour des noirs
            $page .= self::getDivPiecesDisponibles($quantik->pieceBlack, 0); //
            $page .= self::getFormSelectionPiece($quantik-> pieceBlack);
        }else if($couleurActive == 1){ // si c'est au tour des blancs
            $page .= self::getDivPiecesDisponibles($quantik->pieceWhite, 1);
            $page .= self::getFormSelectionPiece($quantik-> pieceWhite);
        }
        $page .= self::getDivPlateauQuantik($quantik->plateau);
        $page .= self::getFinHTML();
        return $page;
    }

//A revoir
    public static function getPagePosePiece(QuantikGame $quantik, int $couleurActive,int $posSelection): string {
        $page = self::getDebutHTML('Quantik - Pose de pièce');
        $page .= '<form action="traiteFormQuantik.php" method="post">';
        if($couleurActive == 0) {  // si c'est au tour des noirs
            $page .= self::getDivPiecesDisponibles($quantik->pieceBlack, 0); //
            $page .= self::getFormSelectionPiece($quantik->pieceBlack);
            //if (isset())
            //...
        }
        return '';
    }

    /**
     * Genere la page de victoire
     */
    public static function getPageVictoire(QuantikGame $quantik, int $couleurActive, int $posSelection): string {
        $page =self::getDebutHTML('Quantik - Victoire');
        $page .= 'form action="traiteFormQuantik.php" method="get">';

        //on affiche les pièces disponibles
        if($couleurActive == 0){
            $page .= self::getFormSelectionPiece($quantik->pieceBlack);
            $page .= self::getFormSelectionPiece($quantik->pieceWhite);
        }else if($couleurActive == 1) {
            $page .= self::getFormSelectionPiece($quantik->pieceWhite);
            $page .= self::getFormSelectionPiece($quantik->pieceBlack);
        }
        //on affiche le plateau et le message de victoire
        $page .= self::getDivPlateauQuantik($quantik->plateau);
        $page .= self::getDivMeessageVictoire($couleurActive);
        $page .= self::getFinHTML();
        return $page;
    }

}