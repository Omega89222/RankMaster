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

final class ListCommand extends BaseSubCommand {

    public function __construct(private Main $plugin) {
        parent::__construct("list", "List a Rank");
        $this->setPermission("rank.use");
    }

    protected function prepare() : void {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        $rankManager = RankManager::getInstance();
        $ranks = $rankManager->getAllRanks();
        if (empty($ranks)) {
            $sender->sendMessage("§cAucun grade trouvé.");
        } else {
            $sender->sendMessage("§eGrades disponibles: §a" . implode(", ", $ranks));
        }
    }

    public function getParent(): BaseCommand {
        return $this->parent;
    }
}