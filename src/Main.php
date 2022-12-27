<?php

declare(strict_types=1);

namespace Aboshxm2\CustomMessages;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{
    public array $lastHits = [];

    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @priority MONITOR
     */
    public function onHit(EntityDamageByEntityEvent $event): void
    {
        $player = $event->getEntity();
        $damager = $event->getDamager();

        if(!$player instanceof Player or !$damager instanceof Player) return;

        $this->lastHits[$player->getName()] = $damager->getName();
    }

    /**
     * @priority MONITOR
     */
    public function onRegain(EntityRegainHealthEvent $event): void
    {
        $player = $event->getEntity();
        if(!$player instanceof Player) return;

        if(($event->getAmount() + $player->getHealth()) >= 20) {
            if(isset($this->lastHits[$player->getName()])) {
                unset($this->lastHits[$player->getName()]);
            }
        }
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

        if(isset($this->lastHits[$player->getName()])) {
            unset($this->lastHits[$player->getName()]);
        }

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

        if(isset($this->lastHits[$player->getName()])) {
            $damagerName = $this->lastHits[$player->getName()];

            if(!$this->getConfig()->getNested("kill.enable")) return;

            $message = TextFormat::colorize($this->getConfig()->getNested("kill.message"));

            $message = str_replace(["{player}", "{killer}"], [$player->getName(), $damagerName], $message);

            $event->setDeathMessage($message);

            unset($this->lastHits[$player->getName()]);
        }else {
            if(!$this->getConfig()->getNested("death.enable")) return;

            $message = TextFormat::colorize($this->getConfig()->getNested("death.message"));

            $message = str_replace("{player}", $player->getName(), $message);

            $event->setDeathMessage($message);
        }
    }
}