<?php

declare(strict_types=1);

namespace NhanAZ\QueryServer;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;

class QueryTask extends AsyncTask {


    public function __construct(
        private string $info
    ){}

    public function onRun() : void
    {
        try {
            $status = Internet::getURL("https://api.mcsrvstat.us/2/" . $this->info);
        } catch (\pocketmine\utils\InternetException $e) {
            var_dump("§e>§f Error:§c Your IP does not open the port or the device does not match!");
            var_dump("§e>§c Try another query method using §b/querys");
        }
        $status = json_decode($status->getBody());
        $this->setResult($status);
    }

    public function onCompletion() : void
    {
        $result = $this->getResult();
        Main::logInfo($result);
    }
}