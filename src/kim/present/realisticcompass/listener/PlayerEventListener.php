<?php

/*
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://opensource.org/licenses/MIT MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\realisticcompass\listener;

use kim\present\realisticcompass\RealisticCompass;
use pocketmine\event\Listener;
use pocketmine\event\player\{
	PlayerInteractEvent, PlayerItemHeldEvent, PlayerJoinEvent
};
use pocketmine\item\Item;
use pocketmine\Player;

class PlayerEventListener implements Listener{
	/** @var RealisticCompass */
	private $plugin;

	/**
	 * PlayerEventListener constructor.
	 *
	 * @param RealisticCompass $plugin
	 */
	public function __construct(RealisticCompass $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * @priority MONITOR
	 *
	 * @param PlayerItemHeldEvent $event
	 */
	public function onPlayerItemHeldEvent(PlayerItemHeldEvent $event) : void{
		if(!$event->isCancelled()){
			$this->check($event->getPlayer(), $event->getItem());
		}
	}

	/**
	 * @priority MONITOR
	 *
	 * @param PlayerJoinEvent $event
	 */
	public function onPlayerJoinEvent(PlayerJoinEvent $event) : void{
		$player = $event->getPlayer();
		$this->check($player, $player->getInventory()->getItemInHand());
	}

	/**
	 * @param Player $player
	 * @param Item   $item
	 */
	public function check(Player $player, Item $item) : void{
		if($this->plugin->isRealsticCompass($item)){
			$this->plugin->getTask()->addPlayer($player);
		}else{
			$this->plugin->getTask()->removePlayer($player);
			RealisticCompass::sendReal($player);
		}
	}

	/**
	 * @priority MONITOR
	 *
	 * @param PlayerInteractEvent $event
	 */
	public function onPlayerInteractEvent(PlayerInteractEvent $event) : void{
		if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
			$player = $event->getPlayer();
			if($this->plugin->isRealsticCompass($player->getInventory()->getItemInHand())){
				$player->sendPosition($player, 180.0, $player->pitch);
			}
		}
	}
}
