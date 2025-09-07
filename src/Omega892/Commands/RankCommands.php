<?php

namespace Omega892\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use Omega892\Main;
use Omega892\RankManager;
use Omega892\Form\RankForm;

class RankCommands extends Command {

    public function __construct() {
        parent::__construct("rankmaster", "Gérer les grades", "/rank <set|create|delete|list>", ["rank"]);
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        $config = Main::$config;

        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("only-ig"));
            return;
        }

        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("no-perm"));
            return;
        }

        if (count($args) < 1) {
            $sender->sendMessage($config->get("usage"));
            return;
        }

        $type = strtolower($args[0]);
        $rankManager = RankManager::getInstance();

        switch ($type) {
            case "set":
                if (count($args) < 3) {
                    $sender->sendMessage("§cUsage: /rank set <joueur> <grade>");
                    return;
                }

                $targetName = $args[1];
                $rank = $args[2];
                $rankManager->addRank($targetName, $rank);

                $sender->sendMessage("§aVous avez ajouté le grade §e{$rank} §aà §e{$targetName}§a !");

                $target = Server::getInstance()->getPlayerExact($targetName);
                if ($target !== null && $target->isOnline()) {
                    $target->sendMessage("§aVous avez maintenant le grade §e{$rank} §a!");
                }
                break;

            case "create":
                RankForm::createRankForm($sender);
                break;

            case "delete":
                if (count($args) < 2) {
                    $sender->sendMessage("§cUsage: /rank delete <grade>");
                    return;
                }

                $rank = $args[1];
                $rankManager->deleteRank($rank);
                $sender->sendMessage("§aVous avez supprimé le grade §e{$rank} §a!");
                break;

            case "list":
                $ranks = $rankManager->getAllRanks();
                if (empty($ranks)) {
                    $sender->sendMessage("§cAucun grade trouvé.");
                } else {
                    $sender->sendMessage("§eGrades disponibles: §a" . implode(", ", $ranks));
                }
                break;

            default:
                $sender->sendMessage($config->get("usage"));
                break;
        }
    }
}