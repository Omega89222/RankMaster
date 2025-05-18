<?php

namespace Omega892\Rank\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Omega892\Rank\Main;

class RankList extends Command {

    public function __construct() {
        parent::__construct("ranklist", "Operateur -> Regarder tous les grades existants", "/ranklist");
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Cette commande est réservée aux joueurs !");
            return;
        }
        $config = Main::$rank; 
        $grades = array_keys($config->getAll());
        $sender->sendMessage("§fVoici tous les §egrades §f: \n");
        foreach($grades as $grade){
            $sender->sendMessage("§f- §e$grade \n");
        }
        $config->save();
    }
}






