<?php

declare(strict_types = 1);

namespace Biswajit\GemsAPI\Listener;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use pocketmine\utils\Config;
use Biswajit\GemsAPI\Events\PlayerGemsBalChangeEvent;
use pocketmine\event\Listener;
use Biswajit\GemsAPI\GemsAPI;
use pocketmine\Server;

class ScoreHudTagListener implements Listener
{
    public function onBalChange(PlayerGemsBalChangeEvent $event): void
    {
        $bal = $event->getBalance();
        $player = $event->getPlayer();

        if ($player->isOnline()) {
            (new PlayerTagUpdateEvent($player, new ScoreTag("gems.bal", strval($bal))))->call();
        }
    }

    public function getGems(TagsResolveEvent $event) {
        $player = $event->getPlayer();
        $tag = $event->getTag();

        if ($tag->getName() === "gems.bal") {
            $tag->setValue(strval (GemsAPI::getInstance()->getGemsBalance($player->getName())));

        }
    }
}
