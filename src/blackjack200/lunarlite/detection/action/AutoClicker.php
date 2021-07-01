<?php


namespace blackjack200\lunarlite\detection\action;


use blackjack200\lunarlite\detection\DetectionBase;
use blackjack200\lunarlite\user\User;

class AutoClicker extends DetectionBase {
	protected $maxCPS;

	public function __construct(User $user, string $name, string $fmt, ?string $webhookFmt, $data) {
		parent::__construct($user, $name, $fmt, $webhookFmt, $data);
		$this->maxCPS = $this->getConfiguration()->getExtraData()->MaxCPS;
	}

	public function check(...$data) : void {
		$CPS = $this->getUser()->CPS;
		if ($CPS >= $this->maxCPS) {
			$this->addVL(1);
			if ($this->overflowVL()) {
				$this->fail("CPS=$CPS");
			}
		} else {
			$this->VL *= $this->getConfiguration()->getReward();
		}
	}
}