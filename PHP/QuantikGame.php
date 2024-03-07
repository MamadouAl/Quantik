<?php

namespace Quantik2024;
require_once 'AbstractGame.php';
require_once 'Player.php';
require_once 'PlateauQuantik.php';

use PlateauQuantik;
use ArrayPieceQuantik;
use PieceQuantik;
use Quantik2024\Player;
use Quantik2024\AbstractGame;

class QuantikGame extends AbstractGame
{
    public PlateauQuantik $plateau;
    public  ArrayPieceQuantik $pieceBlack;
    public ArrayPieceQuantik $pieceWhite;
    public array $couleurPlayer;

    /* TODO implantation schéma UML */
    public function __construct(array $players) {
        $this->plateau = new PlateauQuantik();
        $this->pieceBlack = ArrayPieceQuantik::initPiecesNoires();
        $this->pieceWhite = ArrayPieceQuantik::initPiecesBlanches();
//        $this->players = $players;
        $this->players = array_pad($players, 2, new Player());
        $this->currentPlayer = 0;
        $this->gameStatus = "choixPiece";
        $this->gameID = 0;
        // $this->couleurPlayer = [$this->players[0], $this->players[1]];
        $this->couleurPlayer = [PieceQuantik::BLACK, PieceQuantik::WHITE];
    }

    public function __toString(): string
    {
        return 'Partie n°' . $this->gameID . ' lancée par joueur ' . $this->getPlayers()[0];
    }
    public function getJson(): string
    {
        $json = '{';
        $json .= '"plateau":' . $this->plateau->getJson();
        $json .= ',"piecesBlanches":' . $this->pieceWhite->getJson();
        $json .= ',"piecesNoires":' . $this->pieceBlack->getJson();
        $json .= ',"currentPlayer":' . $this->currentPlayer;
        $json .= ',"gameID":' . $this->gameID;
        $json .= ',"gameStatus":' . json_encode($this->gameStatus);
        $json .= ',"players":[' . $this->getPlayers()[0]->getJson() . ',' . $this->getPlayers()[1]->getJson() . ']';
        if (is_null($this->couleurPlayer[1]))
            // $json .= ',"couleurPlayer":[' . $this->couleurPlayer[0]->getJson() . ']';
            $json .= ',"couleurPlayer":' .json_encode($this->couleurPlayer[0]);

        else
            $json .= ',"couleurPlayer":[' . json_encode($this->couleurPlayer[0]) . ',' . json_encode($this->couleurPlayer[1]) . ']';        
            // $json .= ',"couleurPlayer":[' . $this->couleurPlayer[0]->getJson() . ',' . $this->couleurPlayer[1]->getJson() . ']';
        return $json . '}';
    }
    public static function initQuantikGame(string $json): QuantikGame
    {
        $object = json_decode($json);
        $players = [];
//        foreach ($object->couleurPlayer as $stdObj) {
//            $p = new Player();
//            $p->setName($stdObj->name);
//            $p->setId($stdObj->id);
//            $players[] = $p;
//        }
        $player1 = Player::initPlayer(json_encode($object->players[0]));
        $player2 = Player::initPlayer(json_encode($object->players[1]));
        $qg = new QuantikGame([$player1, $player2]);
        $qg->plateau = PlateauQuantik::initPlateauQuantik($object->plateau);
        $qg->pieceWhite = ArrayPieceQuantik::initArrayPieceQuantik($object->piecesBlanches);
        $qg->pieceBlack = ArrayPieceQuantik::initArrayPieceQuantik($object->piecesNoires);
        $qg->currentPlayer = $object->currentPlayer;
        $qg->gameID = $object->gameID;
        $qg->gameStatus = $object->gameStatus;
        return $qg;
    }

    private function getPlayers(): array
    {
        return $this->players;
    }

    public function setJesonPlayerTwo(string $json): void
    {
        $this->players[1] = Player::initPlayer($json);
    }


}
//
//$player1 = new Player();
//$player1->setName("John");
//$player1->setId(1);
//
//$player2 = new Player();
//$player2->setName("Paul");
//$player2->setId(2);
//
//
//$players = [$player1 ];
//$game = new QuantikGame($players);
//echo $game->getJson()."\n";
//
//$game2 = QuantikGame::initQuantikGame($game->getJson());
//echo $game2;