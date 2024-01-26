<?php
require_once 'AbstractUIGenerator.php';
class QuantikUIGenerator extends AbstractUIGenerator
{
    protected static function getButtonClass(PieceQuantik $piece): string {
        if ($piece->getForme() == PieceQuantik::VOID)
            return 'empty';
        $buttonClass = 'votre_classe_de_bouton';
        return '<button class="' . $buttonClass . '">Votre Bouton</button>';
    }

    /**
     * Permet de générer le code HTML pour représenter le plateau de jeu
     */
    protected static function getDivPlateauQuantik(PlateauQuantik $plateau): string {
        for ($i = 0; $i < PlateauQuantik::NBROWS; $i++)
            $pieces = $plateau->getRow($i);

        $divPlateau = '<div class="plateau-quantik">';
        $divPlateau .= '<p><table>';
        // Parcourez les lignes du plateau et générez le code HTML pour chaque pièce
        foreach ($pieces as $row) {
            // Commencez une nouvelle ligne du tableau
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
        // Commencez le formulaire
        $form = '<form action="traiteFormQuantik.php" method="post">';

        // Ajoutez un champ caché pour l'action du formulaire
        $form .= '<input type="hidden" name="action" value="poser_piece">';

        // Commencez le tableau pour représenter visuellement le plateau
        $form .= '<table class="plateau-table">';

        // Ajoutez la logique pour générer le contenu du plateau en fonction des informations récupérées
        foreach ($plateau->getCasesJouables($piece) as $row => $col) {
            // Commencez une nouvelle ligne du tableau
            $form .= '<tr>';

            foreach ($plateau->getPiece($row, $col) as $cellPiece) {
                // Générez le code HTML pour chaque pièce du plateau dans une cellule
                $buttonValue = $row . ',' . $col; // Format des coordonnées à transmettre
                $form .= '<td class="case-plateau">';
                $form .= '<button type="submit" name="positionPiece" value="' . $buttonValue . '">' . $cellPiece->getRepresentation() . '</button>';
                $form .= '</td>';
            }

            // Fermez la ligne du tableau
            $form .= '</tr>';
        }

        // Fermez le tableau
        $form .= '</table>';

        // Ajoutez le bouton de soumission
        $form .= '<button type="submit">Poser la pièce</button>';

        // Fermez le formulaire
        $form .= '</form>';

        // Retournez le HTML généré
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