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

    private static GemsAPI $instance;

    public function onLoad(): void
    {
        self::$instance = $this;
        $this->getLogger()->info("§eLoading GemsAPI...");
    }

    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "Data")) {
            @mkdir($this->getDataFolder() . "Data");
            $this->getLogger()->info("§bRegistered Data Folder Successfully!");
        }
        $this->checkScoreHud();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        CommandHandler::initialize();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }
    
    private function checkScoreHud(): void {
        $pluginManager = $this->getServer()->getPluginManager();
        if ($pluginManager->getPlugin("ScoreHud") !== null) {
            $pluginManager->registerEvents(new ScoreHudTagListener(), $this);
        } else {
            return;
        }
    }

    public static function getInstance(): GemsAPI
    {
        return self::$instance;

    }

    public function saveAllData(): void
    {
        foreach ($this->gemsMoney as $player => $amount) {
            $gems = new Config($this->getDataFolder() . "Data/" . $player . ".yml", Config::YAML);
            $gems->set("Gems", $amount);
            $gems->save();
        }
    }


    public function loadData(Player $player): void
    {
        $gems = new Config($this->getDataFolder() . "Data/" . $player->getName() . ".yml", Config::YAML);
        $this->gemsMoney[$player->getName()] = $gems->get("Gems");
    }


    public function saveData(Player $player): void
    {
        if (isset($this->gemsMoney[$player->getName()])) {
            $gems = new Config($this->getDataFolder() . "Data/" . $player->getName() . ".yml", Config::YAML);
            $gems->set("Gems", $this->gemsMoney[$player->getName()]);
            $gems->save();
        }
    }

    public function giveGemsBalance(string $player, float $amount): void
    {
        $player = $this->getServer()->getPlayerExact($player);
        $name = $player->getName();
        if ($player instanceof Player && isset($this->gemsMoney[$name]) && is_numeric($this->gemsMoney[$name])) {
            $this->gemsMoney[$name] = $this->gemsMoney[$name] + $amount;
        } else {
            $gems = new Config($this->getDataFolder() . "Data/" . $name . ".yml", Config::YAML);
            $gems->set("Gems", $gems->get("Gems") + $amount);
            $gems->save();
        }
        $event = new PlayerGemsBalChangeEvent($player, strval($this->getGemsBalance($player->getName())));
        $event->call();
    }

  public function getTopPlayerWithGems(): string {
    $players = $this->getServer()->getOnlinePlayers();
    $topPlayer = "";
    $topGems = 0;

    foreach ($players as $player) {
        $gems = $this->getGemsBalance($player->getName());
        if ($gems > $topGems) {
            $topGems = $gems;
            $topPlayer = $player->getName();
        }
    }

    return $topPlayer;
}

  public function setGems(string $playerName, float $amount): void {
        if ($amount < 0) {
            $this->getLogger()->warning("Gems amount cannot be negative!");
            return;
        }
        $playerData = new Config($this->getDataFolder() . "Data/" . $player . ".yml", Config::YAML);
        $playerData->set("Gems", $amount);
        $playerData->save();
    }
    
  public function getallgems(): array {
    $players = $this->getServer()->getOnlinePlayers();
    $allGems = [];

    foreach ($players as $player) {
        $gems = $this->getGemsBalance($player->getName());
        $allGems[$player->getName()] = $gems;
    }

    return $allGems;
    }
    
    public function takeGemsBalance(string $player, float $amount): void
    {
        $player = $this->getServer()->getPlayerExact($player);
        $name = $player->getName();
        if ($player instanceof Player && isset($this->gemsMoney[$name])) {
            $this->gemsMoney[$name] = $this->gemsMoney[$name] - $amount;
        } else {
            $gems = new Config($this->getDataFolder() . "Data/" . $name . ".yml", Config::YAML);
            $gems->set("Gems", $gems->get("Gems") - $amount);
            $gems->save();
        }
        $event = new PlayerGemsBalChangeEvent($player, strval($this->getGemsBalance($player->getName())));
        $event->call();
    }

    public function getGemsBalance(string $player): float
    {
        if ($this->getServer()->getPlayerExact($player) instanceof Player && isset($this->gemsMoney[$player])) {
            return (float) $this->gemsMoney[$player];
        } else {
            $gems = new Config($this->getDataFolder() . "Data/" . $player . ".yml", Config::YAML);
            $money = (float) $gems->get("Gems");
            $gems->save();
            return $money;
        }
    }
}
