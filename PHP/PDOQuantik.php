<?php

namespace Quantik2024 ;
require_once 'Player.php';
use PDO;
use PDOException;
use PDOStatement;


class PDOQuantik
{
    private static PDO $pdo;
    private static PDOStatement|false $selectPlayerByID;
    private static PDOStatement|false $getLastGameIdForPlayer;

    public static function initPDO(string $sgbd, string $host, string $db, string $user, string $password, string $nomTable = ''): void
    {
        switch ($sgbd) {
            case 'pgsql':
                self::$pdo = new PDO('pgsql:host=' . $host . ' dbname=' . $db . ' user=' . $user . ' password=' . $password);
                break;
            case 'mysql':
                self::$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $password);
                break;
            default:
                exit ("Type de sgbd non correct : $sgbd fourni, 'mysql' ou 'pgsql' attendu");
        }

        // pour récupérer aussi les exceptions provenant de PDOStatement
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /* requêtes Préparées pour l'entitePlayer */
    private static PDOStatement $createPlayer;
    private static PDOStatement $selectPlayerByName;

    /******** Gestion des requêtes relatives à Player *************/
    public static function createPlayer(string $name): Player
    {
        if (!isset(self::$createPlayer))
            self::$createPlayer = self::$pdo->prepare('INSERT INTO Player(name) VALUES (:name)');
        self::$createPlayer->bindValue(':name', $name, PDO::PARAM_STR);
        self::$createPlayer->execute();
        return self::selectPlayerByName($name);
    }

    public static function selectPlayerByName(string $name): ?Player
    {
        if (!isset(self::$selectPlayerByName))
            self::$selectPlayerByName = self::$pdo->prepare('SELECT * FROM Player WHERE name=:name');
        self::$selectPlayerByName->bindParam(':name', $name, PDO::PARAM_STR);
        self::$selectPlayerByName->execute();
        $player = self::$selectPlayerByName->fetch();
        if($player)
            return new Player($player['name'], $player['id']);
        return null;
    }

    /*****
     * METHODES AJOUTEES
     ****/
    public static function selectPlayerByID(mixed $playerOne)
    {
       //retourne le nom du joueur en fonction de son identifiant
        if (!isset(self::$selectPlayerByID)) {
            self::$selectPlayerByID = self::$pdo->prepare('SELECT name FROM Player WHERE id = :id');
        }
        self::$selectPlayerByID->bindParam(':id', $playerOne, PDO::PARAM_INT);
        self::$selectPlayerByID->execute();
        return self::$selectPlayerByID->fetch();
    }

    /* requêtes préparées pour l'entiteGameQuantik */
    private static PDOStatement $createGameQuantik;
    private static PDOStatement $saveGameQuantik;
    private static PDOStatement $addPlayerToGameQuantik;
    private static PDOStatement $selectGameQuantikById;
    private static PDOStatement $selectAllGameQuantik;
    private static PDOStatement $selectAllGameQuantikByPlayerName;

    /******** Gestion des requêtes relatives à QuantikGame *************/

    /**
     * initialisation et execution de $createGameQuantik la requête préparée pour enregistrer une nouvelle partie
     */
    public static function createGameQuantik(string $playerName, string $json): void
    {
        if (!isset(self::$createGameQuantik)) {
            self::$createGameQuantik = self::$pdo->prepare('INSERT INTO QuantikGame(playerOne, gameStatus, json) VALUES ((SELECT id FROM Player WHERE name = :playerName), :gameStatus, :json)');
        }

        $gamestatus = 'constructed'; // Statut initial du jeu
        self::$createGameQuantik->bindParam(':playerName', $playerName, PDO::PARAM_STR);
        self::$createGameQuantik->bindParam(':gameStatus', $gamestatus, PDO::PARAM_STR);
        self::$createGameQuantik->bindParam(':json', $json, PDO::PARAM_STR);

        try {
            self::$createGameQuantik->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la création de la partie : " . $e->getMessage();
        }
    }


    /**
     * initialisation et execution de $saveGameQuantik la requête préparée pour changer
     * l'état de la partie et sa représentation json
     */
    public static function saveGameQuantik(string $gameStatus, string $json, int $gameId): void
    {
        /* TODO */
        if (!isset(self::$saveGameQuantik)) {
            self::$saveGameQuantik = self::$pdo->prepare('UPDATE QuantikGame SET gameStatus = :gameStatus, json = :json WHERE gameId = :gameId');
        }
        self::$saveGameQuantik->bindParam(':gameStatus', $gameStatus, PDO::PARAM_STR);
        self::$saveGameQuantik->bindParam(':json', $json, PDO::PARAM_STR);
        self::$saveGameQuantik->bindParam(':gameId', $gameId, PDO::PARAM_INT);
        self::$saveGameQuantik->execute();
    }

    /**
     * initialisation et execution de $addPlayerToGameQuantik la requête préparée pour intégrer le second joueur
     */
    public static function addPlayerToGameQuantik(string $playerName, string $json, int $gameId): void
    {
        //modifie le statut de la partie et ajoute le second joueur
        if (!isset(self::$addPlayerToGameQuantik)) {
            self::$addPlayerToGameQuantik = self::$pdo->prepare('UPDATE QuantikGame SET playerTwo = (SELECT id FROM Player WHERE name = :name),
                       json = :json, gamestatus = \'initialized\' WHERE gameId = :gameId');
        }
        self::$addPlayerToGameQuantik->bindParam(':name', $playerName, PDO::PARAM_STR);
        self::$addPlayerToGameQuantik->bindParam(':json', $json, PDO::PARAM_STR);
        self::$addPlayerToGameQuantik->bindParam(':gameId', $gameId, PDO::PARAM_INT);
        self::$addPlayerToGameQuantik->execute();
    }

    /**
     * initialisation et execution de $selectAllGameQuantikById la requête préparée pour récupérer
     * une instance de quantikGame en fonction de son identifiant
     */
    public static function getGameQuantikById(int $gameId): ?QuantikGame
    {
        // Retourne une instance de QuantikGame en fonction de son identifiant
        if (!isset(self::$selectGameQuantikById)) {
            self::$selectGameQuantikById = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE gameId = :gameId');
        }

        self::$selectGameQuantikById->bindParam(':gameId', $gameId, PDO::PARAM_INT);
        self::$selectGameQuantikById->execute();

        $gameData = self::$selectGameQuantikById->fetch(PDO::FETCH_ASSOC);
        return QuantikGame::initQuantikGame($gameData['json']);
    }

    /**
     * initialisation et execution de $selectAllGameQuantik la requête préparée pour récupérer toutes
     * les instances de quantikGame
     */
    public static function getAllGameQuantik(): array
    {
        //retourne un tableau de toutes les instances de QuantikGame
        if (!isset(self::$selectAllGameQuantik)) {
            self::$selectAllGameQuantik = self::$pdo->query('SELECT * FROM QuantikGame');
        }
        return self::$selectAllGameQuantik->fetchAll();
    }

    /**
     * initialisation et execution de $selectAllGameQuantikByPlayerName la requête préparée pour récupérer les instances
     * de quantikGame accessibles au joueur $playerName
     * ne pas oublier les parties "à un seul joueur"
     */
    public static function getAllGameQuantikByPlayerName(string $playerName): array
    {
        /* TODO */
        if (!isset(self::$selectAllGameQuantikByPlayerName)) {
            self::$selectAllGameQuantikByPlayerName = self::$pdo->prepare('SELECT * FROM QuantikGame WHERE playerOne = (SELECT id FROM Player WHERE name = :name) OR playerTwo = (SELECT id FROM Player WHERE name = :name)');
        }
        self::$selectAllGameQuantikByPlayerName->bindParam(':name', $playerName, PDO::PARAM_STR);
        self::$selectAllGameQuantikByPlayerName->execute();
        return self::$selectAllGameQuantikByPlayerName->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * initialisation et execution de la requête préparée pour récupérer
     * l'identifiant de la dernière partie ouverte par $playername
     */
    public static function getLastGameIdForPlayer(string $playerName): int
    {
        if (!isset(self::$getLastGameIdForPlayer)) {
            self::$getLastGameIdForPlayer = self::$pdo->prepare('SELECT MAX(gameId) as lastID FROM QuantikGame WHERE playerOne = (SELECT id FROM Player WHERE name = :name) OR playerTwo = (SELECT id FROM Player WHERE name = :name)');
        }
        self::$getLastGameIdForPlayer->bindParam(':name', $playerName, PDO::PARAM_STR);
        self::$getLastGameIdForPlayer->execute();
        $row = self::$getLastGameIdForPlayer->fetch(PDO::FETCH_ASSOC);
        return $row['lastid'];
    }
}



