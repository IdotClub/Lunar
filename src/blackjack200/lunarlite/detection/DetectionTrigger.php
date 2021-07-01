<?php


namespace blackjack200\lunarlite\detection;


interface DetectionTrigger {
	/**
	 * @param class-string $class
	 */
	public function trigger(string $class) : void;
}