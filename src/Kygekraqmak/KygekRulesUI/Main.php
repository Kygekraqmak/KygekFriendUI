<?php

# A plugin for PocketMine-MP that will show rules of a server in an UI form.
# Copyright (C) 2020 Kygekraqmak
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <https://www.gnu.org/licenses/>.

namespace Kygekraqmak\KygekRulesUI;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\command\{Command, CommandSender, CommandExecutor, ConsoleCommandSender, PluginIdentifiableCommand};

use Kygekraqmak\KygekRulesUI\commands\Rules;
use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener {

  private static $instance;

  public function onEnable() {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    @mkdir($this->getDataFolder());
    $this->saveResource("config.yml");
    $this->getServer()->getCommandMap()->register("rules", new Rules($this));
    self::$instance = $this;
    if (!$this->getConfig()->exists("config-version")) {
      $this->getLogger()->notice("§eYour configuration file is from another version. Updating the Config...");
      $this->getLogger()->notice("§eThe old configuration file can be found at config_old.yml");
      rename($this->getDataFolder()."config.yml", $this->getDataFolder()."config_old.yml");
      $this->saveResource("config.yml");
      return;
    }
    if (version_compare("1.2", $this->getConfig()->get("config-version"))) {
      $this->getLogger()->notice("§eYour configuration file is from another version. Updating the Config...");
      $this->getLogger()->notice("§eThe old configuration file can be found at config_old.yml");
      rename($this->getDataFolder()."config.yml", $this->getDataFolder()."config_old.yml");
      $this->saveResource("config.yml");
      return;
    }
  }

  public static function getInstance() {
    return self::$instance;
  }

  public function kygekRulesUI($player) {
    $form = new SimpleForm(function (Player $player, int $data = null) {
      if ($data === null) {
        return true;
      }
      switch ($data) {
        case 0:
        break;
      }
    });
    $title = str_replace("{player}", $player->getName(), $this->getConfig()->get("title"));
    $content = str_replace("{player}", $player->getName(), $this->getConfig()->get("content"));
    $button = str_replace("{player}", $player->getName(), $this->getConfig()->get("button"));
    $form->setTitle($title);
    $form->setContent($content);
    $form->addButton($button);
    $form->sendToPlayer($player);
    return $form;
  }

}
