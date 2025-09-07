<?php

declare(strict_types=1);

namespace Omega892\Commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use Omega892\Main;
use Omega892\Commands\SubCommands\CreateCommand;
use Omega892\Commands\SubCommands\SetCommand;
use Omega892\Commands\SubCommands\ListCommand;
use Omega892\Commands\SubCommands\DeleteCommand;

final class RankSubCommand extends BaseCommand {

    public function __construct(private Main $plugin) {
        parent::__construct($plugin, "rankmaster", "RankMaster commands");
        $this->setAliases(["rank", "rm"]);
        $this->setPermission("rank.use");
        $this->setPermissionMessage("§cYou don't have permission to us this command!");
    }

    public function prepare() : void {
        $this->registerSubCommand(new CreateCommand($this->plugin));
        $this->registerSubCommand(new SetCommand($this->plugin));
        $this->registerSubCommand(new ListCommand($this->plugin));
        $this->registerSubCommand(new DeleteCommand($this->plugin));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        $sender->sendMessage("§cNo subcommand provided, try using: /" . $aliasUsed . " help");
    }

    public function getPermission(): ?string {
        return "rank.use";
    }
}