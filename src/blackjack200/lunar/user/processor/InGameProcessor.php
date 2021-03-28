<?php


namespace blackjack200\lunar\user\processor;


use blackjack200\lunar\Lunar;
use blackjack200\lunar\user\User;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;

class InGameProcessor extends Processor {
	private bool $tag;

	public function __construct(User $user) {
		parent::__construct($user);
		$this->tag = Lunar::getInstance()->getConfig()->get('CPSTag');
	}

	public function processClient(DataPacket $packet) : void {
		if ($packet instanceof InventoryTransactionPacket) {
			if ($packet->trData instanceof UseItemOnEntityTransactionData) {
				$this->addClick();
			}
		}
		if ($packet instanceof LevelSoundEventPacket && $packet->sound === LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE) {
			$this->addClick();
		}
	}

	public function addClick() : void {
		$this->getUser()->CPS++;
		if ($this->tag) {
			$this->getUser()->getPlayer()->sendPopup("CPS=§b{$this->getUser()->CPS}");
		}
	}

	public function check(...$data) : void {
		$this->getUser()->CPS = 0;
	}
}