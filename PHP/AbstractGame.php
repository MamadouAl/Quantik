<?php
//classe abstraite AbstractGame

abstract class AbstractGame
{
    protected int $gameID;
    protected array $players;
    public Player $currentPlayer;
    public string $gameStatus;

}