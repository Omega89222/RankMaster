<?php

namespace Omega892\Rank\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Omega892\Rank\Main;
use pocketmine\player\Player;

class RankInfo extends Command {
    public function __construct() {
        parent::__construct("rankinfo", "Joueur -> Voir les informations de rank d'un joueur", "/rankinfo");
        $this->setPermission("pocketmine.group.user");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Cette commande est réservée aux joueurs !");
            return;
        }
        if (!isset($args[0])){
            $sender->sendMessage("§fIl manque le §enom du joueur §f!");
            return;
        }

        $config = Main::getInstance()->getConfig();
        $configRank = Main::$rank;
        $target = $sender->getServer()->getPlayerExact($args[0]);

        if (!$target instanceof Player) {
            $sender->sendMessage("§cLe joueur n'est pas connecté.");
            return;
        }

        $name = $target->getName();

        if(!$config->exists($args[0])) {
            $sender->sendMessage("§fCe joueur §en'existe pas §f.");
            return;
        }
        $rankTarget = $config->getNested("{$name}.rank", "none");

        $sender->sendMessage("§fVoici le rank de §e$name §f: §e$rankTarget");
    }
}