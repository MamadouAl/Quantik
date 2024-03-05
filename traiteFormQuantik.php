<?php
namespace Quantik2024;
require_once './ressourcesQuantik/PDOQuantik.php';
require_once './ressourcesQuantik/env/db.php';
require_once 'PHP/QuantikGame.php';
use AbstractUIGenerator;
use PlateauQuantik;
use Quantik2024\PDOQuantik;
use Quantik2024\QuantikGame;



session_start();

switch ($_POST['action']) {
    case 'constructed':
        PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
        //creer le jeu dans la base de données
    $p = $_SESSION['player'];
    $name = $p->getName();
    $game = new QuantikGame([$p]);
    PDOQuantik::createGameQuantik($name, $game->getJson());
    header('Location: ./ressourcesQuantik/quantik.php');
    break;
    case 'recommencer':
        session_unset();
        header('Location: quantik.php');
        break;
    case 'AnnulerChoixPiece':
        $_SESSION['etat'] = 'choixPiece';
        $_SESSION['piece'] = null; //on annule le choix de la pièce
        $_SESSION['QuantikGame']->currentPlayer = ($_SESSION['QuantikGame']->currentPlayer + 1) % 2;

        header('Location: quantik.php');
        break;
    case 'deconnexion':
        session_start();
        session_unset(); // Supprimer toutes les variables de session
        session_destroy(); // Détruire la session
        header('HTTP/1.1 303 See Other');
        header('Location: ./ressourcesQuantik/login.php'); // Rediriger vers la page de connexion
        exit();
    default:
        echo AbstractUIGenerator::getPageErreur("Une erreur est survenue lors de la partie", "");
        break;
}


//si on a cliqué sur une pièce
if (isset($_POST['piece'])) {
    $_SESSION['piece'] = $_POST['piece'];
    $_SESSION['etat'] = 'posePiece';

    header('Location: quantik.php');
}

if (isset($_POST['posePiece'])) {
    $pos = explode(',', $_POST['posePiece']); //on récupère les coordonnées
    if($_SESSION['QuantikGame']->currentPlayer == 0){
        $piece = $_SESSION['QuantikGame']->pieceWhite->getPieceQuantiks($_SESSION['piece']);
        $_SESSION['actionQ']->posePiece($pos[0], $pos[1], $piece);

    }else{
        $piece = $_SESSION['QuantikGame']->pieceBlack->getPieceQuantiks($_SESSION['piece']);
        $_SESSION['actionQ']->posePiece($pos[0], $pos[1], $piece);
    }
    //mettre une pièce vide à la place de la pièce choisie
    if($_SESSION['QuantikGame']->currentPlayer == 0) {
        $_SESSION['QuantikGame']->pieceWhite->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());
        //$_SESSION['QuantikGame']->piceWhite ->removePieceQuantik($_SESSION['piece']);
    }else {
        $_SESSION['QuantikGame']->pieceBlack->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());
    }

    // Vérifier si la partie est terminée après avoir posé la pièce
    if (checkVictoire()) {
        $_SESSION['etat'] = 'finPartie';
        header('Location: quantik.php');
        exit; // Terminer le script
    }

    $_SESSION['etat'] = 'choixPiece';
    header('Location: quantik.php');
}

function checkVictoire(): bool
{
    $actionQ = $_SESSION['actionQ'];
    // Vérification les lignes
    for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
        if ($actionQ->isRowWin($i)) {
            return true;
        }
    }
    // Vérification les colonnes
    for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
        if ($actionQ->isColWin($j)) {
            return true;
        }
    }
    // Vérifier les coins
    for ($k = 0; $k < 4; $k++) {
        if ($actionQ->isCornerWin($k)) {
            return true;
        }
    }
    return false;
}

