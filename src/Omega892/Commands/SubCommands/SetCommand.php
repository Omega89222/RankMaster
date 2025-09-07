<?php

declare(strict_types=1);

namespace Omega892\Commands\SubCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Omega892\Main;
use Omega892\Form\RankForm;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Omega892\RankManager;
use pocketmine\Server;

final class SetCommand extends BaseSubCommand {

    public function __construct(private Main $plugin) {
        parent::__construct("set", "Set a Rank");
        $this->setPermission("rank.use");
    }

    protected function prepare() : void {
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new RawStringArgument("rankName"));
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
    $targetName = $args["player"];
    $rankName   = $args["rankName"];

    $target = $sender->getServer()->getPlayerExact($targetName);

    if ($target === null) {
        $sender->sendMessage("§cLe joueur §e{$targetName} §cn’est pas en ligne.");
        return;
    }

    $rankManager = RankManager::getInstance();
    $rankManager->addRank($target, $rankName);

    $sender->sendMessage("§aVous avez ajouté le grade §e{$rankName} §aà §e{$target->getName()}§a !");
    $target->sendMessage("§aVous avez maintenant le grade §e{$rankName} §a!");
}

    public function getParent(): BaseCommand {
        return $this->parent;
    }
}