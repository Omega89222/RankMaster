<?php

declare(strict_types=1);

namespace Omega892\Commands\SubCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Omega892\RankManager;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class ReloadPermCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct("reloadperm", "Recharger les permission", ["reloadpermissions"]);
        $this->setPermission("rank.use");
    }

    protected function prepare() : void {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        $rankManager = RankManager::getInstance();
        $rankManager->updatePlayerPermissions($sender);
        $sender->sendMessage("§eReload permission...");
    }
}
