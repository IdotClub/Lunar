<?php


namespace blackjack200\lunarlite;


use blackjack200\lunarlite\configuration\DetectionConfiguration;
use blackjack200\lunarlite\detection\action\AutoClicker;
use blackjack200\lunarlite\detection\action\FastBreakA;
use blackjack200\lunarlite\detection\action\NukerA;
use blackjack200\lunarlite\detection\combat\KillAuraA;
use blackjack200\lunarlite\detection\combat\KillAuraB;
use blackjack200\lunarlite\detection\combat\MultiAura;
use blackjack200\lunarlite\detection\combat\ReachA;
use blackjack200\lunarlite\detection\combat\velocity\VelocityB;
use blackjack200\lunarlite\detection\DetectionBase;
use blackjack200\lunarlite\detection\movement\AirSwim;
use blackjack200\lunarlite\detection\movement\AntiImmobile;
use blackjack200\lunarlite\detection\movement\fly\FlyA;
use blackjack200\lunarlite\detection\movement\fly\FlyB;
use blackjack200\lunarlite\detection\movement\fly\FlyE;
use blackjack200\lunarlite\detection\movement\motion\MotionB;
use blackjack200\lunarlite\detection\movement\speed\SpeedA;
use blackjack200\lunarlite\detection\movement\speed\SpeedC;
use blackjack200\lunarlite\detection\packet\AntiToolBox;
use blackjack200\lunarlite\detection\packet\BadPacketA;
use blackjack200\lunarlite\detection\packet\BadPacketB;
use blackjack200\lunarlite\detection\packet\BadPacketC;
use blackjack200\lunarlite\detection\packet\ClientDataFaker;
use blackjack200\lunarlite\user\User;

final class DetectionRegistry {
	/** @var DetectionConfiguration[] */
	private static array $configurations = [];

	private function __construct() { }

	public static function initConfig() : void {
		$detections = [
			'AutoClicker' => AutoClicker::class,
			'AntiToolBox' => AntiToolBox::class,
			'FastBreakA' => FastBreakA::class,
			'NukerA' => NukerA::class,

			'MultiAura' => MultiAura::class,
			'ReachA' => ReachA::class,
			'VelocityB' => VelocityB::class,
			'KillAuraA' => KillAuraA::class,
			'KillAuraB' => KillAuraB::class,

			'AirSwim' => AirSwim::class,
			'AntiImmobile' => AntiImmobile::class,
			'SpeedA' => SpeedA::class,
			'SpeedC' => SpeedC::class,
			'MotionB' => MotionB::class,
			'FlyA' => FlyA::class,
			'FlyB' => FlyB::class,
			'FlyE' => FlyE::class,

			'BadPacketA' => BadPacketA::class,
			'BadPacketB' => BadPacketB::class,
			'BadPacketC' => BadPacketC::class,
			'ClientDataFaker' => ClientDataFaker::class,
		];

		foreach ($detections as $name => $class) {
			self::register($class, $name, false);
		}
	}

	private static function register(string $class, string $name, bool $object) : void {
		self::$configurations[$class] = new DetectionConfiguration(
			$class,
			$name,
			LunarLite::getInstance()->getConfig()->get($name),
			$object
		);
	}

	public static function getConfigurations() : array {
		return self::$configurations;
	}

	/**
	 * @return DetectionBase[]
	 */
	public static function getDetections(User $user) : array {
		$detections = [];
		foreach (self::$configurations as $configuration) {
			$detection = self::create($user, $configuration);
			if ($detection !== null) {
				$detections[$configuration->getClass()] = $detection;
			}
		}
		return $detections;
	}

	private static function create(User $user, DetectionConfiguration $configuration) : ?DetectionBase {
		if ($configuration->isEnable()) {
			$class = $configuration->getClass();
			return new $class(
				$user,
				$configuration->getName(),
				LunarLite::getInstance()->getFormat(),
				LunarLite::getInstance()->getWebhookFormat(),
				clone $configuration
			);
		}
		return null;
	}

	public static function unregister(string $class) : void {
		unset(self::$configurations[$class]);
	}
}