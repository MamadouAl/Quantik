<?php
// updateContent.php

namespace Quantik2024;
require_once '../PHP/PDOQuantik.php';
require_once '../PHP/Player.php';
require_once './env/db.php';
use Exception;

// Initialisez votre connexion PDO
PDOQuantik::initPDO($_ENV['sgbd'], $_ENV['host'], $_ENV['database'], $_ENV['user'], $_ENV['password']);

// Récupérez vos données
$games = PDOQuantik::getAllGameQuantik();

// Construisez le tableau associatif avec les listes de jeux
$response = [
    'initializedGames' => [],
    'waitingGames' => [],
    'finishedGames' => []
];

foreach ($games as $game) {
    if ($game['gameStatus'] == 'initialized' || $game['gameStatus'] == 'waitingForPlayer') {
        $playerOne = PDOQuantik::selectPlayerByID($game['playerOne']);
        $playerTwo = PDOQuantik::selectPlayerByID($game['playerTwo']);

        $response['initializedGames'][] = [
            'gameId' => $game['gameId'],
            'playerOne' => $playerOne['name'],
            'playerTwo' => $playerTwo['name']
        ];
    } elseif ($game['gameStatus'] == 'constructed') {
        $playerOne = PDOQuantik::selectPlayerByID($game['playerOne']);
        $disabled = ($_SESSION['player']->getId() === $game['playerOne']) ? 'disabled' : '';

        $response['waitingGames'][] = [
            'gameId' => $game['gameId'],
            'playerOne' => $playerOne['name'],
            'disabled' => $disabled
        ];
    } elseif ($game['gameStatus'] == 'finished') {
        $playerOne = PDOQuantik::selectPlayerByID($game['playerOne']);
        $playerTwo = PDOQuantik::selectPlayerByID($game['playerTwo']);

        $response['finishedGames'][] = [
            'gameId' => $game['gameId'],
            'playerOne' => $playerOne['name'],
            'playerTwo' => $playerTwo['name']
        ];
    }
}

// Envoyez la réponse en tant que JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
