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

final class SetPermRankCommand extends BaseSubCommand {

    public function __construct(private Main $plugin) {
        parent::__construct("setpermissiontrank", "Définir le rank par default", ["setpermrank"]);
        $this->setPermission("rank.use");
    }

    protected function prepare() : void {
        $this->registerArgument(0, new RawStringArgument("rank"));
        $this->registerArgument(1, new RawStringArgument("permission"));
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        $rankManager = RankManager::getInstance();
        $rank = $args["rank"];
        $permission = $args["permission"];

        $rankManager->addPermissionAtRank($rank, $permission);
    }

    public function getParent(): BaseCommand {
        return $this->parent;
    }
}