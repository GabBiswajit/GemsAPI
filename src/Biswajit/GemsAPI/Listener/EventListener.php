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
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if (!file_exists(GemsAPI::getInstance()->getDataFolder() . "Data/" . $player->getName() . ".yml")) {
            new Config(GemsAPI::getInstance()->getDataFolder() . "Data/" . $player->getName() . ".yml", Config::YAML, array(
                "Gems" => 0,
            ));
        }
    }


    public function onQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        GemsAPI::getInstance()->saveData($player);
        unset(GemsAPI::getInstance()->gemsMoney[$player->getName()]);
    }

}
