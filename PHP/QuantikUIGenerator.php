<?php

class QuantikUIGenerator extends AbstractUIGenerator
{
    protected getButtonClass(PieceQuantik $piece): string {
        // Implémenter la logique ici
    }
    protected static function getDivPiecesDisponibles(ArrayPieceQuantik $pieces, int $player): string {
        // Implémenter la logique ici
    }

    protected static function getFormSelectionPiece(ArrayPieceQuantik $pieces): string {
        // Implémenter la logique ici
    }

    protected static function getDivPlateauQuantik(PlateauQuantik $plateau): string {
        // Implémenter la logique ici
    }

    protected static function getFormPlateauQuantik(PlateauQuantik $plateau, PieceQuantik $piece): string {
        // Implémenter la logique ici
    }

    protected static function getButtonClass(PieceQuantik $piece): string {
        // Implémenter la logique ici
    }

    protected static function getFormBoutonAnnulerChoixPiece(): string {
        // Implémenter la logique ici
    }
    
    protected static function  getDivMeessageVictoire(int $couleur) {
        // Implémenter la logique ici
    }
    protected static function getLienRecommencer(): string {
        // Implémenter la logique ici
    }
    public static function getPageSelectionPiece(QuantikGame $quantik, int $couleurActive): string {
        // Implémenter la logique ici
    }
    public static function getPagePosePiece(QuantikGame $quantik, int $couleurActive,int $posSelection): string {
    // Implémenter la logique ici
    }
    public static function getPageVictoire(QuantikGame $quantik, int $couleurActive, nt $posSelection): string {
        // Implémenter la logique ici
    }
    
}