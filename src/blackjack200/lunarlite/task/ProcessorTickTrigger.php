<?php


namespace blackjack200\lunarlite\task;


use blackjack200\lunarlite\detection\action\NukerA;
use blackjack200\lunarlite\detection\combat\KillAuraA;
use blackjack200\lunarlite\user\processor\MovementProcessor;
use blackjack200\lunarlite\user\UserManager;
use pocketmine\scheduler\Task;

class ProcessorTickTrigger extends Task {
	public function onRun(int $currentTick) : void {
		foreach (UserManager::getUsers() as $user) {
			//TODO This shouldn't be hardcoded
			$user->trigger(NukerA::class);
			$user->trigger(KillAuraA::class);
			$user->triggerProcessor(MovementProcessor::class);
		}
	}
}