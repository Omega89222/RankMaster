<?php

namespace Omega892;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Omega892\RankManager;
use CortexPE\Commando\PacketHooker;
use Omega892\Commands\RankSubCommand;
use Omega892\Events\RankEvents;

class Main extends PluginBase {
    use SingletonTrait;

    private RankManager $rankManager;
    public static Config $config;

    public function onEnable(): void {
        if(!PacketHooker::isRegistered()){
            PacketHooker::register($this);
        }
        self::setInstance($this);
        $this->saveDefaultConfig();
        self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        new RankManager($this->getDataFolder());

        $this->getServer()->getPluginManager()->registerEvents(new RankEvents(), $this);
        $this->getServer()->getCommandMap()->register("rankmaster", new RankSubCommand($this));
    }
    public function getRankManager(): RankManager {
        return $this->rankManager;
    }
}