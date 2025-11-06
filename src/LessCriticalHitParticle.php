<?php

namespace lesscriticalhitparticle;

use pocketmine\event\EventPriority;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use ReflectionException;

final class LessCriticalHitParticle extends PluginBase {

    use SingletonTrait;

    /**
     * @var float
     */
    private float $float = 55.0;

    /**
     * @return void
     */
    protected function onLoad(): void {
        self::setInstance($this);
        $this->saveDefaultConfig();
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    protected function onEnable(): void {
        $this->float = floatval($this->getConfig()->getNested("less-critical-hit-particle.settings.float", 55.0));
        $this->getServer()->getPluginManager()->registerEvent(DataPacketSendEvent::class, static function (DataPacketSendEvent $event): void {
            $packets = $event->getPackets();
            foreach ($packets as $packet) {
                if (!($packet instanceof AnimatePacket && $packet->action === AnimatePacket::ACTION_CRITICAL_HIT)) {
                    continue;
                }
                $packet->data = $this->float;
            }
        }, EventPriority::NORMAL, $this);
        $this->getLogger()->notice($this->getName() . " ✅");
    }

    /**
     * @return void
     */
    protected function onDisable(): void {
        $this->getLogger()->notice($this->getName() . " ❌");
    }

}