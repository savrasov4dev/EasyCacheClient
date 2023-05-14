<?php

namespace App;

use Socket;

class CacheConnector implements CacheConnectorI
{
    public function __construct(
        private readonly string $address,
        private readonly int    $domain = AF_UNIX,
        private readonly int    $type = SOCK_STREAM,
        private readonly int    $protocol = 0
    ) {}

    public function open(): Socket
    {
        $socket = socket_create($this->domain, $this->type, $this->protocol) OR die(
            "Unable to create $this->domain socket:\n" . socket_strerror(socket_last_error()) . "\n"
        );

        socket_connect($socket, $this->address) OR die(
            "Unable to connect $this->address :\n" . socket_strerror(socket_last_error()) . "\n"
        );

        return $socket;
    }

    public function close(Socket $socket): void
    {
        socket_close($socket);
    }
}