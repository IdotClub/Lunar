<?php


namespace blackjack200\lunarlite\user\processor;


use blackjack200\lunarlite\LunarLite;
use blackjack200\lunarlite\user\User;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;

class InGameProcessor extends Processor {
	private bool $tag;

	public function __construct(User $user) {
		parent::__construct($user);
		$this->tag = LunarLite::getInstance()->getConfig()->get('CPSTag');
	}

	public function processClient(DataPacket $packet) : void {
		if (($packet instanceof InventoryTransactionPacket) && $packet->trData instanceof UseItemOnEntityTransactionData && $packet->trData->getActionType() === UseItemOnEntityTransactionData::ACTION_ATTACK) {
			$this->addClick();
		}
		if ($packet instanceof LevelSoundEventPacket && $packet->sound === LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE) {
			$this->addClick();
		}
	}

	public function addClick() : void {
		$CPS = &$this->getUser()->CPS;
		$CPS++;
		if ($this->tag) {
			$this->getUser()->getPlayer()->sendPopup("CPS=§b$CPS");
		}
	}

	public function check(...$data) : void {
		$usr = $this->getUser();
		foreach ($usr->getPlayer()->getEffects() as $effect) {
			if ($effect->getDuration() === 1) {
				$usr->getExpiredInfo()->set($effect->getId());
			}
		}
		$usr->CPS = 0;
	}
}