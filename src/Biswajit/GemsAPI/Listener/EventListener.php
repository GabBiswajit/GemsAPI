<?php

declare(strict_types = 1);

namespace Biswajit\GemsAPI\Listener;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use Biswajit\GemsAPI\GemsAPI;
use pocketmine\Server;

class EventListener implements Listener
{
  public function onJoin(PlayerJoinEvent $event)
{
    $player = $event->getPlayer();
    $playerName = $player->getName();
    $stmt = GemsAPI::getInstance()->database->prepare("INSERT OR IGNORE INTO player_data (player_name, gems) VALUES (:name, 0)");
    $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
    $stmt->execute();
    $stmt->close();
}

public function onQuit(PlayerQuitEvent $event): void
{
    $player = $event->getPlayer();
    $playerName = $player->getName();
    if (isset($this->gemsMoney[$playerName])) {
        $currentGems = $this->gemsMoney[$playerName];
        $stmt = GemsAPI::getInstance()->database->prepare("UPDATE player_data SET gems = :gems WHERE player_name = :name");
        $stmt->bindValue(":name", $playerName, SQLITE3_TEXT);
        $stmt->bindValue(":gems", $currentGems, SQLITE3_FLOAT);
        $stmt->execute();
        $stmt->close();
        unset($this->gemsMoney[$playerName]);
    }
 }
}
