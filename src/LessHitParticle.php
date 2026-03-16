<?php

namespace lesshitparticle;

use pocketmine\event\EventPriority;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use ReflectionException;

final class LessHitParticle extends PluginBase {

    use SingletonTrait;

    /**
     * @var float
     */
    private float $criticalHitFloat = 55.0;

    /**
     * @var float
     */
    private float $magicalHitFloat = 15.0;

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
        $config = $this->getConfig();
        $this->criticalHitFloat = (float) $config->getNested("less-hit-particle.settings.float.critical-hit", 55.0);
        $this->magicalHitFloat = (float) $config->getNested("less-hit-particle.settings.float.magical-hit", 15.0);
        $this->getServer()->getPluginManager()->registerEvent(DataPacketSendEvent::class, function (DataPacketSendEvent $event): void {
            $packets = $event->getPackets();
            foreach ($packets as $packet) {
                if (!$packet instanceof AnimatePacket) {
                    continue;
                }
                switch ($packet->action) {
                    case AnimatePacket::ACTION_CRITICAL_HIT:
                        $packet->data = $this->criticalHitFloat;
                        break;
                    case AnimatePacket::ACTION_MAGICAL_CRITICAL_HIT:
                        $packet->data = $this->magicalHitFloat;
                        break;
                }
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