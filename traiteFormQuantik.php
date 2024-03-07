<?php
namespace Quantik2024;
require_once 'PHP/PDOQuantik.php';
require_once './ressourcesQuantik/env/db.php';
require_once 'PHP/QuantikGame.php';
require_once 'PHP/ActionQuantik.php';

use AbstractUIGenerator;
use ActionQuantik;
use PieceQuantik;
use PlateauQuantik;

PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);

session_start();
if (!isset($_SESSION['player'])) {
    header('HTTP/1.1 303 See Other');
    header('Location: ressourcesQuantik/login.php');
    exit();
}
if(isset($_POST['piece'])){
    $_SESSION['piece'] = $_POST['piece'];
    $_SESSION['etat'] = 'posePiece';
    header('Location: index.php');
}else
if (isset($_POST['posePiece'])) {
    $pos = explode(',', $_POST['posePiece']); //on récupère les coordonnées
    $gameID = $_SESSION['gameID'];
    $game = PDOQuantik::getGameQuantikById($gameID);
    if($game->currentPlayer == 0){
        
        $piece = $game->pieceWhite->getPieceQuantiks($_SESSION['piece']);
        $_SESSION['actionQ'] = new ActionQuantik($game->plateau);
        $_SESSION['actionQ']->posePiece($pos[0], $pos[1], $piece);
        //echo  $game->getJson();
        $game->pieceWhite->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());
        $game->currentPlayer = ($game->currentPlayer + 1) % 2;
       // echo $game->couleurPlayer[1];
        PDOQuantik::saveGameQuantik("waitingForPlayer", $game->getJson(),$gameID);

    }else if ($game->currentPlayer == 1){
        $piece = $game->pieceBlack->getPieceQuantiks($_SESSION['piece']);
        $_SESSION['actionQ'] = new ActionQuantik($game->plateau);
        $_SESSION['actionQ']->posePiece($pos[0], $pos[1], $piece);
        $game->pieceBlack->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());
        $game->currentPlayer = ($game->currentPlayer + 1) % 2;

        PDOQuantik::saveGameQuantik("waitingForPlayer", $game->getJson(),$gameID);
    }
        /*
        $piece = $game->pieceBlack->getPieceQuantiks($_SESSION['piece']);
        $_SESSION['actionQ']->posePiece($pos[0], $pos[1], $piece);*/

    //mettre une pièce vide à la place de la pièce choisie
    // if($_SESSION['QuantikGame']->currentPlayer == 0) {
    //     $_SESSION['QuantikGame']->pieceWhite->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());
    //     //$_SESSION['QuantikGame']->piceWhite ->removePieceQuantik($_SESSION['piece']);
    // }else {
    //     $_SESSION['QuantikGame']->pieceBlack->setPieceQuantiks($_SESSION['piece'], PieceQuantik::initVoid());
    // }

    // Vérifier si la partie est terminée après avoir posé la pièce
    if (checkVictoire()) {
        $_SESSION['etat'] = 'consultePartieVictoire';
        $_SESSION['etatApp'] = 'Victoire';
        PDOQuantik::saveGameQuantik("finished", $game->getJson(),$gameID);

        header('Location: index.php');
        exit; // Terminer le script
    }

    $_SESSION['etat'] = 'choixPiece';
    header('Location: index.php');
}
else

    switch ($_POST['action']) {
        case 'constructed':
            //creer le jeu dans la base de données
        $p = $_SESSION['player'];
        $name = $p->getName();
        $game = new QuantikGame([$p]);
        PDOQuantik::createGameQuantik($name, $game->getJson());
        header('Location: ./ressourcesQuantik/quantik.php');
        break;
        case 'initialized':
            //initialiser le jeu
            $gameID = $_POST['gameID'];
            $game = PDOQuantik::getGameQuantikById($gameID);
            $game->setJesonPlayerTwo($_SESSION['player']->getJson());
            PDOQuantik::addPlayerToGameQuantik($_SESSION['player']->getName(), $game->getJson(), $gameID);
            header('Location: ./ressourcesQuantik/quantik.php');
            break;

        case 'waitingForPlayer':
            //attendre un autre joueur
            $_SESSION['gameID'] = $_POST['gameID'];
            $_SESSION['etatApp'] = 'consultePartieEnCours';
            $_SESSION['etat'] = 'choixPiece';
            header('Location: index.php');

            break;
        case 'finished':
            //partie terminée
            $_SESSION['gameID'] = $_POST['gameID'];
            $_SESSION['etatApp'] = 'Victoire';
            $_SESSION['etat'] = 'consultePartieVictoire';
            header('Location: index.php');
            break;

        case 'recommencer':
            //session_unset();
            $_SESSION['etatApp'] = 'home';
            header('Location: index.php');
            break;
        case 'AnnulerChoixPiece':
            $game = PDOQuantik::getGameQuantikById($_SESSION['gameID']);
            $_SESSION['etat'] = 'choixPiece';
            $_SESSION['piece'] = null; //on annule le choix de la pièce
            $game->currentPlayer = ($game->currentPlayer + 1) % 2;
            header('Location: index.php');
            break;
        case 'deconnexion':
            session_start();
            session_unset(); // Supprimer toutes les variables de session
            session_destroy(); // Détruire la session
            header('HTTP/1.1 303 See Other');
            header('Location: ressourcesQuantik/login.php'); // Rediriger vers la page de connexion
            exit();
        default:

            break;
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

