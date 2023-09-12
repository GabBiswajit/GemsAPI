<?php

declare(strict_types = 1);

namespace Biswajit\GemsAPI;

use Biswajit\GemsAPI\CommandHandler\CommandHandler;
use pocketmine\plugin\PluginBase;
use Biswajit\GemsAPI\Listener\EventListener;
use pocketmine\command\Command;
use pocketmine\Server;
use Biswajit\GemsAPI\Listener\ScoreHudTagListener;
use Biswajit\GemsAPI\Events\PlayerGemsBalChangeEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class GemsAPI extends PluginBase implements Listener
{
    public array $gemsMoney = [];

    public \SQLite3 $database;

    private static GemsAPI $instance;

    public function onLoad(): void
    {
        self::$instance = $this;
  
    }

    public function onEnable(): void
    {
        $this->database = new \SQLite3($this->getDataFolder() . "playerdata.db");

        $query = "CREATE TABLE IF NOT EXISTS player_data (player_name TEXT PRIMARY KEY, gems FLOAT)";
        $this->database->exec($query);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        CommandHandler::initialize();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public static function getInstance(): GemsAPI
    {
        return self::$instance;
    }

    public function saveAllData(): void
    {
        foreach ($this->gemsMoney as $playerName => $amount) {
            $stmt = $this->database->prepare("UPDATE player_data SET gems = :gems WHERE player_name = :name");
            $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
            $stmt->bindValue(":gems", $amount, SQLITE3_FLOAT);
            $stmt->execute();
        }
    }

    public function loadData(Player $player): void
    {
        $playerName = $player->getName();
        $stmt = $this->database->prepare("SELECT gems FROM player_data WHERE player_name = :name");
        $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
        $result = $stmt->execute();

        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row !== false) {
            $this->gemsMoney[$playerName] = (float)$row['gems'];
        } else {
            $this->gemsMoney[$playerName] = 0.0;
        }
    }

    public function saveData(Player $player): void
    {
        $playerName = $player->getName();
        
        $stmt = $this->database->prepare("UPDATE player_data SET gems = :gems WHERE player_name = :name");
        $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
        $stmt->bindValue(":gems", $this->gemsMoney[$playerName], SQLITE3_FLOAT);
        $stmt->execute();
    }

    public function giveGemsBalance(string $playerName, float $amount): void
    {
        if ($amount < 0) {
            $this->getLogger()->warning("Gems amount cannot be negative!");
            return;
        }

        $stmt = $this->database->prepare("SELECT gems FROM player_data WHERE player_name = :name");
        $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
        $result = $stmt->execute();

        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row !== false) {
            $currentBalance = (float)$row['gems'];

            $newBalance = $currentBalance + $amount;

            $stmt = $this->database->prepare("UPDATE player_data SET gems = :newBalance WHERE player_name = :name");
            $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
            $stmt->bindValue(":newBalance", $newBalance, SQLITE3_FLOAT);
            $stmt->execute();

            $this->gemsMoney[$playerName] = $newBalance;
        } else {
            $this->getLogger()->warning("Player not found in the database.");
        }
    }

  public function getTopPlayerWithGems(): string {
    $stmt = $this->database->query("SELECT player_name, gems FROM player_data ORDER BY gems DESC LIMIT 1");

    $row = $stmt->fetchArray(SQLITE3_ASSOC);

    if ($row !== false) {
        return $row['player_name'];
    } else {
        return "";
    }
}


  public function setGems(string $playerName, float $amount): void {
    if ($amount < 0) {
        $this->getLogger()->warning("Gems amount cannot be negative!");
        return;
    }
    
    $stmt = $this->database->prepare("UPDATE player_data SET gems = :gems WHERE player_name = :name");
    $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
    $stmt->bindValue(":gems", $amount, SQLITE3_FLOAT);
    $stmt->execute();
    $this->gemsMoney[$playerName] = $amount;
}

    
  public function getallgems(): array {
    $allGems = [];

    $stmt = $this->database->query("SELECT player_name, gems FROM player_data");
    
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $playerName = $row['player_name'];
        $gems = (float)$row['gems'];
        $allGems[$playerName] = $gems;
    }

    return $allGems;
}

    
    public function takeGemsBalance(string $player, float $amount): void
{
    $playerName = $player;

    $stmt = $this->database->prepare("SELECT gems FROM player_data WHERE player_name = :name");
    $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row !== false) {
        $currentBalance = (float)$row['gems'];

        if ($currentBalance >= $amount) {
         
            $newBalance = $currentBalance - $amount;

            $stmt = $this->database->prepare("UPDATE player_data SET gems = :newBalance WHERE player_name = :name");
            $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
            $stmt->bindValue(":newBalance", $newBalance, SQLITE3_FLOAT);
            $stmt->execute();

            $this->gemsMoney[$playerName] = $newBalance;
        } else {
            $this->getLogger()->warning("Insufficient gems balance for deduction.");
        }
    } else {
        $this->getLogger()->warning("Player not found in the database.");
    }
}


    public function getGemsBalance(string $player): float
{
    $playerName = $player;

    $stmt = $this->database->prepare("SELECT gems FROM player_data WHERE player_name = :name");
    $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row !== false) {
        return (float)$row['gems'];
    } else {
        return 0.0;
    }
 }
}
