<?php

declare(strict_types=1);

namespace Aboshxm2\CustomMessages;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{
    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @priority HIGH
     */
    public function onJoin(PlayerJoinEvent $event): void
    {
        if(!$this->getConfig()->getNested("join.enable")) return;

        $player = $event->getPlayer();

        $message = TextFormat::colorize($this->getConfig()->getNested("join.message"));

        $message = str_replace("{player}", $player->getName(), $message);

        $event->setJoinMessage($message);
    }

    /**
     * @priority HIGH
     */
    public function onQuit(PlayerQuitEvent $event): void
    {
        if(!$this->getConfig()->getNested("quit.enable")) return;

        $player = $event->getPlayer();

        $message = TextFormat::colorize($this->getConfig()->getNested("quit.message"));

        $message = str_replace("{player}", $player->getName(), $message);

        $event->setQuitMessage($message);
    }
    /**
     * @priority HIGH
     */
    public function onDeath(PlayerDeathEvent $event): void
    {
        $player = $event->getPlayer();

        $lastDamageCause = $player->getLastDamageCause();

        if($lastDamageCause instanceof EntityDamageByEntityEvent and ($killer = $lastDamageCause->getDamager()) instanceof Player) {
            if(!$this->getConfig()->getNested("kill.enable")) return;

            $message = TextFormat::colorize($this->getConfig()->getNested("kill.message"));

            $message = str_replace(["{player}", "{killer}"], [$player->getName(), $killer->getName()], $message);

            $event->setDeathMessage($message);
        }else {
            if(!$this->getConfig()->getNested("death.enable")) return;

            $message = TextFormat::colorize($this->getConfig()->getNested("death.message"));

            $message = str_replace("{player}", $player->getName(), $message);

            $event->setDeathMessage($message);
        }
    }
}