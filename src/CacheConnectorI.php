<?php

namespace App;

use Socket;

interface CacheConnectorI
{
    public function open(): Socket;

    public function close(Socket $socket): void;

}