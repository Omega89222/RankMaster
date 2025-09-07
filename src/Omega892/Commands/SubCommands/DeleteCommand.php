<?php

declare(strict_types=1);

namespace Omega892\Commands\SubCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Omega892\Main;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Omega892\RankManager;

final class DeleteCommand extends BaseSubCommand {

    public function __construct(private Main $plugin) {
        parent::__construct("delete", "Supprimer un grade");
        $this->setPermission("rank.use");
    }

    protected function prepare() : void {
        $this->registerArgument(0, new RawStringArgument("rank"));
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        $rankManager = RankManager::getInstance();
        $rank = $args[0];
        $rankManager->deleteRank($rank);
        $sender->sendMessage("§aVous avez supprimé le grade §e{$rank} §a!");
    }

    public function getParent(): BaseCommand {
        return $this->parent;
    }
}