<?php


namespace blackjack200\lunarlite\user\processor;


use blackjack200\lunarlite\detection\packet\ClientDataFaker;
use blackjack200\lunarlite\LunarLite;
use blackjack200\lunarlite\user\LoginData;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use Throwable;

class LoginProcessor extends Processor {
	public function processClient(DataPacket $packet) : void {
		if ($packet instanceof LoginPacket) {
			try {
				//TODO HACK
				$this->getUser()->loginData = new LoginData($packet);
				$this->getUser()->trigger(ClientDataFaker::class);
			} catch (Throwable $e) {
				if ($e instanceof ChainDataException) {
					LunarLite::getInstance()->getLogger()->info("Player {$this->getUser()->getPlayer()->getName()} is login without chainData(or error).");
					return;
				}
				LunarLite::getInstance()->getLogger()->error($e);
			}
		}
	}
}