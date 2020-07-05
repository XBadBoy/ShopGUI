<?php

namespace openShopGUI\XBADBOY;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\level\sound\PopSound;
use libs\muqsit\invmenu\InvMenu;
use libs\muqsit\invmenu\InvMenuHandler;
use onebone\economyapi\EconomyAPI;
use openShopGUI\XISOQ\Sound\SoundSuccess;

class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$this->chest = InvMenu::create(InvMenu::TYPE_CHEST);
		$this->block = InvMenu::create(InvMenu::TYPE_CHEST);
		$this->mine = InvMenu::create(InvMenu::TYPE_CHEST);
		$this->tool = InvMenu::create(InvMenu::TYPE_CHEST);
		if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
        switch($cmd->getName()){                    
            case "buy":
                if($sender instanceof Player){
                    if(!isset($args[0])){
                    	$sender->sendMessage("§eTyping this §a/buy §7[Potion\Mine\Enchant\Tool\Eat\Block\Sell]");
                        return true;
                    }
                    $arg = array_shift($args);
                    switch($arg){
                    	case "potion":
                            $this->openPotionShop($sender);
                        break;
                        case "mine":
                            $this->openMineShop($sender);
                        break;
                        case "enchant":
                            $this->openEnchantShop($sender);
                        break;
                        case "tool":
                            $this->openToolShop($sender);
                        break;
                        case "eat":
                            $this->openEatShop($sender);
                        break;
                        case "block":
                            $this->openBlockShop($sender);
                        break;
                        case "sell":
                            $this->openSellShop($sender);
                        break;
                    }
                }
            break;
        }
        return true;
    }
    
    public function openBlockShop($sender){    	
	    $this->block->readonly();
	    $this->block->setListener([$this, "openBlockShop2"]);
        $this->block->setName("BlockShop");
	    $inventory = $this->block->getInventory();
	    $inventory->setItem(0, Item::get(5, 0, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(1, Item::get(5, 1, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(2, Item::get(5, 2, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(3, Item::get(5, 3, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(4, Item::get(5, 4, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(5, Item::get(5, 5, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(18, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(19, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(20, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(21, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(22, Item::get(381, 0, 1)->setCustomName("§l§cEXIT"));
        $inventory->setItem(23, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(24, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(25, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(26, Item::get(160, 1, 1)->setCustomName("---"));
	    $this->block->send($sender);
	}
	
	public function openBlockShop2(Player $sender, Item $item){
		$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->block->getInventory();
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->removeWindow($inventory);
        }
        if($item->getId() == 5 && $item->getDamage() == 0){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(5, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 5 && $item->getDamage() == 1){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(5, 1, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 5 && $item->getDamage() == 2){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(5, 2, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 5 && $item->getDamage() == 3){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(5, 3, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 5 && $item->getDamage() == 4){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(5, 4, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 5 && $item->getDamage() == 5){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(5, 5, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
    }
    
    public function openMineShop($sender){    	
	    $this->mine->readonly();
	    $this->mine->setListener([$this, "openMineShop2"]);
        $this->mine->setName("MineShop");
	    $inventory = $this->mine->getInventory();
	    $inventory->setItem(0, Item::get(266, 0, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(1, Item::get(265, 0, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(2, Item::get(388, 0, 1)->setLore(["\n§l§bSELL 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(3, Item::get(264, 0, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(4, Item::get(351, 4, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(5, Item::get(406, 0, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(6, Item::get(331, 0, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
	    $inventory->setItem(18, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(19, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(20, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(21, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(22, Item::get(381, 0, 1)->setCustomName("§l§cEXIT"));
        $inventory->setItem(23, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(24, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(25, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(26, Item::get(160, 1, 1)->setCustomName("---"));
	    $this->mine->send($sender);
	}
	
	public function openMineShop2(Player $sender, Item $item){
		$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->mine->getInventory();
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->removeWindow($inventory);
        }
        if($item->getId() == 266){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(266, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 265){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(265, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 388){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(388, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 264){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(264, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 351){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(351, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 406){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(406, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 331){
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(331, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
    }
	
    public function openToolShop($sender){    	
	    $this->tool->readonly();
	    $this->tool->setListener([$this, "openToolShop2"]);
        $this->tool->setName("ToolShop");
	    $inventory = $this->tool->getInventory();
        $inventory->setItem(2, Item::get(276, 0, 1)->setCustomName("Diamond Sword")->setLore(["Harga : 124000/1"]));
        $inventory->setItem(3, Item::get(278, 0, 1)->setCustomName("Diamond Pickaxe")->setLore(["Harga : 128000/1"]));
        $inventory->setItem(4, Item::get(293, 0, 1)->setCustomName("Diamond Hoe")->setLore(["Harga : 100000/1"]));
        $inventory->setItem(5, Item::get(279, 0, 1)->setCustomName("Diamond Axe")->setLore(["Harga : 120000/1"]));
        $inventory->setItem(6, Item::get(277, 0, 1)->setCustomName("Diamond Shovel")->setLore(["Harga : 100000/1"]));
        $inventory->setItem(18, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(19, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(20, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(21, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(22, Item::get(381, 0, 1)->setCustomName("§l§cEXIT"));
        $inventory->setItem(23, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(24, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(25, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(26, Item::get(160, 1, 1)->setCustomName("---"));
        $this->tool->send($sender);  
    }
    
    public function openToolShop2(Player $sender, Item $item){
    	$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->tool->getInventory();
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->removeWindow($inventory);
        }
    	if($item->getId() == 276){
    	    $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 124000){
		        $this->eco->reduceMoney($sender, "124000"); 
		        $inv = $sender->getInventory();
		        $inv->addItem(Item::get(276, 0, 1));
		    }else{
			    $sender->sendMessage("§cCheck your money!!");
			}
        }
        if($item->getId() == 278){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 128000){
			    $this->eco->reduceMoney($sender, "128000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(278, 0, 1));
		    }else{
			    $sender->sendMessage("§cCheck your money!!");
			}
        }
        if($item->getId() == 293){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
            $sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 100000){
			    $this->eco->reduceMoney($sender, "100000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(293, 0, 1));
		    }else{
			    $sender->sendMessage("§cCheck your money!!");
			}
        }
        if($item->getId() == 279){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 120000){
			    $this->eco->reduceMoney($sender, "120000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(279, 0, 1));
		    }else{
			    $sender->sendMessage("§cCheck your money!!");
			}
        }
        if($item->getId() == 277){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 100000){
			    $this->eco->reduceMoney($sender, "100000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(277, 0, 1));
		    }else{
			    $sender->sendMessage("§cCheck your money!!");
			}
        }
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
        }
    }
    
    public function openSellShop($sender){
	    $this->chest->readonly();
	    $this->chest->setListener([$this, "openSellShop2"]);
        $this->chest->setName("SellShop");
	    $inventory = $this->chest->getInventory();
        $inventory->setItem(0, Item::get(296, 0, 1)->setCustomName("Sell Wheat")->setLore(["Sell 64 = 48000/64"]));
        $inventory->setItem(1, Item::get(392, 0, 1)->setCustomName("Sell Potato")->setLore(["Sell 128 = 112000/128"]));
        $inventory->setItem(2, Item::get(360, 0, 1)->setCustomName("Sell Melon")->setLore(["Sell 32 = 38000/32"]));
        $inventory->setItem(3, Item::get(391, 0, 1)->setCustomName("Sell Carrot")->setLore(["Sell 64 = 43500/64"]));
        $inventory->setItem(4, Item::get(86, 0, 1)->setCustomName("Sell Pumpkin")->setLore(["Sell 64 = 53000/64"]));
        $inventory->setItem(10, Item::get(264, 0, 1)->setCustomName("Sell Diamond")->setLore(["Sell 64 = 250000/64"]));
        $inventory->setItem(11, Item::get(388, 0, 1)->setCustomName("Sell Emerald")->setLore(["Sell 64 = 305000/64"]));
        $inventory->setItem(12, Item::get(351, 4, 1)->setCustomName("Sell Lazuli")->setLore(["Sell 64 = 118000/64"]));
        $inventory->setItem(13, Item::get(263, 0, 1)->setCustomName("Sell Coal")->setLore(["Sell 64 = 11000/64"]));
        $inventory->setItem(14, Item::get(265, 0, 1)->setCustomName("Sell Iron Ingot")->setLore(["Sell 64 = 83500/64"]));
        $inventory->setItem(15, Item::get(266, 0, 1)->setCustomName("Sell Gold Ingot")->setLore(["Sell 64 = 115000/64"]));
        $inventory->setItem(18, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(19, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(20, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(21, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(22, Item::get(381, 0, 1)->setCustomName("§l§cEXIT"));
        $inventory->setItem(23, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(24, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(25, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(26, Item::get(160, 1, 1)->setCustomName("---"));
        $this->chest->send($sender);  
    }
    
    public function openSellShop2(Player $sender, Item $item){
    	$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->chest->getInventory();
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->removeWindow($inventory);
        }
    	if($item->getId() == 296){
    	    $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $sender->getLevel()->addSound(new PopSound($sender));
	        $n = Item::get(296, 0, 64);
            $inv = $sender->getInventory()->getItemInHand($n);
			if($inv >= $n){
				$sender->getInventory()->remove($n);
				$this->eco->addMoney($sender, "48000");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §eWheat 64");
			}
        }
        if($item->getId() == 392){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $o = Item::get(392, 0, 128);
            $inv = $sender->getInventory()->getItemInHand($o);
			if($inv >= $o){
				$sender->getInventory()->remove($o);
				$this->eco->addMoney($sender, "112000");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §ePotato 128");
			}
        }
        if($item->getId() == 360){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
            $sender->getLevel()->addSound(new PopSound($sender));
	        $k = Item::get(360, 0, 32);
            $inv = $sender->getInventory()->getItemInHand($k);
			if($inv >= $k){
				$sender->getInventory()->remove($k);
				$this->eco->addMoney($sender, "38000");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §eMelon 32");
			}
        }
        if($item->getId() == 391){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));   
		    $i = Item::get(391, 0, 64);
            $inv = $sender->getInventory()->getItemInHand($i);
			if($inv >= $i){
				$sender->getInventory()->remove($i);
				$this->eco->addMoney($sender, "43500");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §eCarrot 64");
			}
        }
        if($item->getId() == 86){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $y = Item::get(86, 0, 64);
            $inv = $sender->getInventory()->getItemInHand($y);
			if($inv >= $y){
				$sender->getInventory()->remove($y);
				$this->eco->addMoney($sender, "53000");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §ePumpkin 64");
			}
        }
        if($item->getId() == 264){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));   
		    $p = Item::get(264, 0, 64);
            $inv = $sender->getInventory()->getItemInHand($p);
			if($inv >= $p){
				$sender->getInventory()->remove($p);
				$this->eco->addMoney($sender, "250000");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §eDiamond 64");
			}
        }
        if($item->getId() == 388){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));   
		    $e = Item::get(388, 0, 64);
            $inv = $sender->getInventory()->getItemInHand($e);
			if($inv >= $e){
				$sender->getInventory()->remove($e);
				$this->eco->addMoney($sender, "305000");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §eEmerald 64");
			}
        }
        if($item->getId() == 351){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));   
		    $f = Item::get(351, 4, 64);
            $inv = $sender->getInventory()->getItemInHand($f);
			if($inv >= $f){
				$sender->getInventory()->remove($f);
				$this->eco->addMoney($sender, "118000");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §eLazuli 64");
			}
        }
        if($item->getId() == 263){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));   
		    $c = Item::get(263, 0, 64);
            $inv = $sender->getInventory()->getItemInHand($c);
			if($inv >= $c){
				$sender->getInventory()->remove($c);
				$this->eco->addMoney($sender, "11000");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §eCoal 64");
			}
        }
        if($item->getId() == 265){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));   
		    $r = Item::get(265, 0, 64);
            $inv = $sender->getInventory()->getItemInHand($r);
			if($inv >= $r){
				$sender->getInventory()->remove($r);
				$this->eco->addMoney($sender, "83500");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §eIron_Ingot 64");
			}
        }
        if($item->getId() == 266){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
            $t = Item::get(266, 0, 64);
            $inv = $sender->getInventory()->getItemInHand($t);
			if($inv >= $t){
				$sender->getInventory()->remove($t);
				$this->eco->addMoney($sender, "115000");
			}else{
			    $sender->sendMessage("§l§e»§r §cYou don't have Item §f: §eGold_Ingot 64");
			}
        }
    }
    
    public function openPotionShop($sender){
	    $this->menu->readonly();
	    $this->menu->setListener([$this, "openPotionShop2"]);
        $this->menu->setName("PotionShop");
	    $inventory = $this->menu->getInventory();
        $inventory->setItem(0, Item::get(373, 0, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(1, Item::get(373, 1, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(2, Item::get(373, 2, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(3, Item::get(373, 3, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(4, Item::get(373, 4, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(5, Item::get(373, 5, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(6, Item::get(373, 6, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(7, Item::get(373, 7, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(8, Item::get(373, 8, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(9, Item::get(373, 9, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(10, Item::get(373, 10, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(11, Item::get(373, 11, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(12, Item::get(373, 12, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(13, Item::get(373, 13, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(14, Item::get(373, 14, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(15, Item::get(373, 15, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(16, Item::get(373, 16, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(17, Item::get(373, 17, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(18, Item::get(373, 18, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(19, Item::get(373, 19, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(20, Item::get(373, 20, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(21, Item::get(373, 21, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(22, Item::get(373, 22, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(23, Item::get(373, 23, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(24, Item::get(373, 24, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(25, Item::get(373, 25, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(26, Item::get(373, 26, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(27, Item::get(373, 27, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(28, Item::get(373, 28, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(29, Item::get(373, 29, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(30, Item::get(373, 30, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(31, Item::get(373, 31, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(32, Item::get(373, 32, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(33, Item::get(373, 33, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(34, Item::get(373, 34, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(35, Item::get(373, 35, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(36, Item::get(373, 36, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(37, Item::get(373, 37, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(38, Item::get(373, 38, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(39, Item::get(373, 39, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(40, Item::get(373, 40, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(41, Item::get(373, 41, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(45, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(46, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(47, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(48, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(49, Item::get(381, 0, 1)->setCustomName("§l§cEXIT"));
        $inventory->setItem(50, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(51, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(52, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(53, Item::get(339, 0, 1)->setCustomName("Next"));
        $this->menu->send($sender);
    }
        
    public function openPotionShop2(Player $sender, Item $item){
    	$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->menu->getInventory();
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->removeWindow($inventory);
        }
        if($item->getId() == 339){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
	        $menu->readonly();
            $menu->setName("PotionShop");
            $menu->setListener([$this, "openPotionShop3"]);
	        $inventory = $menu->getInventory();
            $inventory->setItem(0, Item::get(438, 0, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(1, Item::get(438, 1, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(2, Item::get(438, 2, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(3, Item::get(438, 3, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(4, Item::get(438, 4, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(5, Item::get(438, 5, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(6, Item::get(438, 6, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(7, Item::get(438, 7, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(8, Item::get(438, 8, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(9, Item::get(438, 9, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(10, Item::get(438, 10, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(11, Item::get(438, 11, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(12, Item::get(438, 12, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(13, Item::get(438, 13, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(14, Item::get(438, 14, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(15, Item::get(438, 15, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(16, Item::get(438, 16, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(17, Item::get(438, 17, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(18, Item::get(438, 18, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(19, Item::get(438, 19, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(20, Item::get(438, 20, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(21, Item::get(438, 21, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(22, Item::get(438, 22, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(23, Item::get(438, 23, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(24, Item::get(438, 24, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(25, Item::get(438, 25, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(26, Item::get(438, 26, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(27, Item::get(438, 27, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(28, Item::get(438, 28, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(29, Item::get(438, 29, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(30, Item::get(438, 30, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(31, Item::get(438, 31, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(32, Item::get(438, 32, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(33, Item::get(438, 33, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(34, Item::get(438, 34, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(35, Item::get(438, 35, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(36, Item::get(438, 36, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(37, Item::get(438, 37, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(38, Item::get(438, 38, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(39, Item::get(438, 39, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(40, Item::get(438, 40, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(41, Item::get(438, 41, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(45, Item::get(339, 0, 1)->setCustomName("Previous"));
            $inventory->setItem(46, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(47, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(48, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(49, Item::get(381, 0, 1)->setCustomName("§l§cEXIT"));
            $inventory->setItem(50, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(51, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(52, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(53, Item::get(160, 1, 1)->setCustomName("---"));
            $menu->send($sender);
        }
        if($item->getId() == 373 && $item->getDamage() == 0){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 1){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 1, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 2){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 2, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 3){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 3, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 4){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 4, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 5){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 5, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 6){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 6, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 7){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 7, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 8){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 8, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 9){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 9, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 10){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 10, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 11){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 11, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 12){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 12, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 13){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 13, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 14){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 14, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 15){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 15, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 16){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 16, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 17){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 17, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 18){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 18, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 19){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 19, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 20){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 20, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 21){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 21, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 22){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 22, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 23){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 23, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 24){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 24, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 25){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 25, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 26){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 26, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 27){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 27, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 28){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 28, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 29){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 29, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 30){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 30, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 31){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 31, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 32){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 32, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 33){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 33, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 34){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 34, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 35){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 35, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 36){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 36, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 37){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 37, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 38){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 38, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 39){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 39, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 40){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 40, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 373 && $item->getDamage() == 41){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(373, 41, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
    }
    
    public function openPotionShop3(Player $sender, Item $item){
    	$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->menu->getInventory();
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->removeWindow($inventory);
        }
        if($item->getId() == 339){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $this->openPotionShop($sender);
        }
        if($item->getId() == 438 && $item->getDamage() == 0){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 1){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 1, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 2){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 2, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 3){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 3, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 4){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 4, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 5){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 5, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 6){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 6, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 7){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 7, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 8){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 8, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 9){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 9, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 10){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 10, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 11){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 11, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 12){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 12, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 13){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 13, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 14){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 14, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 15){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 15, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 16){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 16, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 17){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 17, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 18){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 18, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 19){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 19, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 20){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 20, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 21){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 21, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 22){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 22, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 23){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 23, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 24){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 24, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 25){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 25, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 26){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 26, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 27){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 27, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 28){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 28, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 29){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 29, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 30){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 30, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 31){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 31, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 32){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 32, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 33){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 33, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 34){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 34, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 35){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 35, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 36){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 36, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 37){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 37, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 38){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 38, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 39){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 39, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 40){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 40, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 438 && $item->getDamage() == 41){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(438, 41, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
    }
    
    public function openEnchantShop($sender){
    	$this->menu->readonly();
	    $this->menu->setListener([$this, "openEnchantShop2"]);
        $this->menu->setName("EnchantShop");
	    $inventory = $this->menu->getInventory();
        $inventory->setItem(0, Item::get(403, 0, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(1, Item::get(403, 1, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(2, Item::get(403, 2, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(3, Item::get(403, 3, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(4, Item::get(403, 4, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(5, Item::get(403, 5, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(6, Item::get(403, 6, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(7, Item::get(403, 7, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(8, Item::get(403, 8, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(9, Item::get(403, 9, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(10, Item::get(403, 10, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(11, Item::get(403, 11, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(12, Item::get(403, 12, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(13, Item::get(403, 13, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(14, Item::get(403, 14, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(15, Item::get(403, 15, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(16, Item::get(403, 16, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(17, Item::get(403, 17, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(18, Item::get(403, 18, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(19, Item::get(403, 19, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(20, Item::get(403, 20, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(21, Item::get(403, 21, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(22, Item::get(403, 22, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(23, Item::get(403, 23, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(24, Item::get(403, 24, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(25, Item::get(403, 25, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(26, Item::get(403, 26, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(27, Item::get(403, 27, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(28, Item::get(403, 28, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(29, Item::get(403, 29, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(30, Item::get(403, 30, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(31, Item::get(403, 31, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(32, Item::get(403, 32, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(33, Item::get(403, 33, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(34, Item::get(403, 34, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(35, Item::get(403, 35, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(36, Item::get(403, 36, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(37, Item::get(403, 37, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(38, Item::get(403, 38, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(39, Item::get(403, 39, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(40, Item::get(403, 40, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(41, Item::get(403, 41, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(42, Item::get(403, 42, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(43, Item::get(403, 43, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(44, Item::get(403, 44, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
        $inventory->setItem(45, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(46, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(47, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(48, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(49, Item::get(381, 0, 1)->setCustomName("§l§cEXIT"));
        $inventory->setItem(50, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(51, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(52, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(53, Item::get(339, 0, 1)->setCustomName("Next"));
        $this->menu->send($sender);
    }
    
    public function openEnchantShop2(Player $sender, Item $item){
    	$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->menu->getInventory();
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->removeWindow($inventory);
        }
        if($item->getId() == 339){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $this->menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
	        $this->menu->readonly();
            $this->menu->setName("EnchantShop");
            $this->menu->setListener([$this, "openEnchantShop3"]);
	        $inventory = $this->menu->getInventory();
            $inventory->setItem(0, Item::get(403, 45, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(1, Item::get(403, 46, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(2, Item::get(403, 47, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(3, Item::get(403, 48, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(4, Item::get(403, 49, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(5, Item::get(403, 50, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(6, Item::get(403, 51, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(7, Item::get(403, 52, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(8, Item::get(403, 53, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(9, Item::get(403, 50, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(10, Item::get(403, 51, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(11, Item::get(403, 52, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(12, Item::get(403, 53, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(13, Item::get(403, 54, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(14, Item::get(403, 55, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(15, Item::get(403, 56, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(16, Item::get(403, 57, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(17, Item::get(403, 58, 1)->setLore(["\n§l§bBUY 1: §a$5000.0 §r§o(Left-Click)\n§l§bBUY 64: §a320000.0 §r§o(Left-Click)"]));
            $inventory->setItem(45, Item::get(339, 0, 1)->setCustomName("Previous"));
            $inventory->setItem(46, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(47, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(48, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(49, Item::get(381, 0, 1)->setCustomName("§l§cEXIT"));
            $inventory->setItem(50, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(51, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(52, Item::get(160, 1, 1)->setCustomName("---"));
            $inventory->setItem(53, Item::get(160, 1, 1)->setCustomName("---"));
            $this->menu->send($sender);
        }
        if($item->getId() == 403){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(403, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
    }
    
    public function openEnchantShop3(Player $sender, Item $item){
    	$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->menu->getInventory();
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->removeWindow($inventory);
        }
        if($item->getId() == 339){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $this->openEnchantShop($sender);
        }
        if($item->getId() == 403){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 5000){
			    $this->eco->reduceMoney($sender, "5000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(403, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
    }
    
    public function openEatShop($sender){
    	$this->chest->readonly();
	    $this->chest->setListener([$this, "openEatShop2"]);
        $this->chest->setName("Eatshop");
	    $inventory = $this->chest->getInventory();
        $inventory->setItem(0, Item::get(297, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(1, Item::get(400, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(2, Item::get(354, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(3, Item::get(463, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(4, Item::get(350, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(5, Item::get(412, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(6, Item::get(424, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(7, Item::get(320, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(8, Item::get(366, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(9, Item::get(357, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(10, Item::get(393, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(11, Item::get(413, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(12, Item::get(459, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(13, Item::get(282, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(14, Item::get(464, 0, 1)->setLore(["\n§l§bBUY 1: §a$1000.0 §r§o(Left-Click)\n§l§bBUY 64: §a64000.0 §r§o(Left-Click)"]));
        $inventory->setItem(18, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(19, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(20, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(21, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(22, Item::get(381, 0, 1)->setCustomName("§l§cEXIT"));
        $inventory->setItem(23, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(24, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(25, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(26, Item::get(160, 1, 1)->setCustomName("---"));
        $this->chest->send($sender);  
    }
    
    public function openEatShop2(Player $sender, Item $item){
    	$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->chest->getInventory();
        if($item->getId() == 381){
        	$sender->getLevel()->addSound(new PopSound($sender));
            $sender->removeWindow($inventory);
        }
        if($item->getId() == 297){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(297, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 400){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(400, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 354){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(354, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 463){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(463, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 350){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(350, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 412){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(412, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 424){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(424, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 320){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(320, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 366){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(366, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 357){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(357, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 393){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(393, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 413){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(413, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 459){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(459, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 282){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(282, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
        if($item->getId() == 464){
        	$sender->getLevel()->addSound(new SoundSuccess($sender->x, $sender->y, $sender->z), [$sender]);
        	$sender->getLevel()->addSound(new PopSound($sender));
	        $money = $this->eco->myMoney($sender);
	        if($money >= 1000){
			    $this->eco->reduceMoney($sender, "1000"); 
			    $inv = $sender->getInventory();
		        $inv->addItem(Item::get(464, 0, 1));
		        $sender->sendMessage("§aYou bought 1 item(s).");
		    }else{
			    $sender->sendMessage("§c§oYou don't have money to buy this item!");
			}
        }
    }
}