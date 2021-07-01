<?php


namespace blackjack200\lunarlite\task;


use blackjack200\lunarlite\detection\action\AutoClicker;
use blackjack200\lunarlite\user\processor\InGameProcessor;
use blackjack200\lunarlite\user\UserManager;
use pocketmine\scheduler\Task;

class ProcessorSecondTrigger extends Task {
	public function onRun(int $currentTick) : void {
		foreach (UserManager::getUsers() as $user) {
			//TODO This shouldn't be hardcoded
			$user->trigger(AutoClicker::class);
			$user->triggerProcessor(InGameProcessor::class, null);
		}
	}
}