<?php

namespace Omega892\Rank;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\scheduler\Task;

use Omega892\Rank\Commands\RankSet;
use Omega892\Rank\Commands\RankCreate;
use Omega892\Rank\Events\JoinEvents;
use Omega892\Rank\Events\RankEvents;
use Omega892\Rank\Commands\RankList;
use Omega892\Rank\Commands\Rank;
use Omega892\Rank\Commands\RankRemove;
use Omega892\Rank\Commands\RankInfo;
use Omega892\Rank\Commands\RankHelp;
use Omega892\Rank\Commands\RankEdit;

class Main extends PluginBase {

    use SingletonTrait;
    public Config $config;
    public Config static $webhook;
    public static Config $rank;

    public function onEnable(): void {
        self::setInstance($this);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();

        $this->saveResource("rank.yml");
        self::$rank = new Config($this->getDataFolder() . 'rank.yml', Config::YAML);

        $this->saveResource("webhook.yml");
        self::$webhook = new Config($this->getDataFolder() . 'webhook.yml', Config::YAML);

        $this->getServer()->getCommandMap()->register("rankset", new RankSet());
        $this->getServer()->getCommandMap()->register("rankcreate", new RankCreate());
        $this->getServer()->getCommandMap()->register("ranklist", new RankList());
        $this->getServer()->getCommandMap()->register("rank", new Rank());
        $this->getServer()->getCommandMap()->register("rankremove", new RankRemove());
        $this->getServer()->getCommandMap()->register("rankhelp", new RankHelp());
        $this->getServer()->getCommandMap()->register("rankinfo", new RankInfo());
        $this->getServer()->getCommandMap()->register("rankedit", new RankEdit());
        
        $this->getServer()->getPluginManager()->registerEvents(new RankEvents(),$this);
        $this->getServer()->getPluginManager()->registerEvents(new JoinEvents(),$this);
    }
}