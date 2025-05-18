<?php

namespace Omega892\Rank\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Omega892\Rank\Main;

class RankHelp extends Command {

    public function __construct() {
        parent::__construct("rankhelp", "Operateur -> Help rank", "/rankhelp");
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Cette commande est réservée aux joueurs !");
            return;
        }
        $sender->sendMessage("Voici toutes les commandes de §eRankSystem §7:\n- §e/rankcreate§f\n- §e/rankremove§f\n- §e/rankedit§f\n- §e/ranklist§f\n- §e/rankset§f\n- §e/rank§f\n\nVous pouvez égalements §emodifier §fles grades dans le fichier config §erank.yml §f.");
    }
}






