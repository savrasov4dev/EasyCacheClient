<?php

namespace App;

use Socket;

class EasyCache
{
    private Socket $socket;

    public function __construct(
        private readonly CacheConnectorI $connector,
        private readonly int $maxReadLength
    ) {}

    public function get(string $key): string
    {
        $this->socket = $this->connector->open();

        $request = json_encode([
            'method' => 'get',
            'key' => $key,
            'data' => null,
            'expire' => null
        ]);

        $this->write($request);

        $response = $this->read();

        $this->connector->close($this->socket);

        return $response;
    }

    public function set(string $key, string $data, ?int $expire = null): bool
    {

        if ($expire === null || $expire > 0) {

            $this->socket = $this->connector->open();

            $request = json_encode([
                'method' => 'set',
                'key' => $key,
                'data' => $data,
                'expire' => $expire
            ]);

            $this->write($request);

            $response = $this->read();

            $this->connector->close($this->socket);

            if ($response === 'OK') {
                return true;
            }
        }

        return false;
    }

    private function read(): string
    {
        if (false === ($request = socket_read($this->socket, $this->maxReadLength))) {

            echo "Unable to read $this->socket socket:\n" . socket_strerror(socket_last_error()) . "\n";

            $request = '';
        }

        return $request;
    }

    private function write(string $request): void
    {
        $length = strlen($request);

        do {
            if (false === ($sent = socket_write($this->socket, $request, $length))) {
                echo "Unable to write $this->socket socket:\n" . socket_strerror(socket_last_error()) . "\n";
                break;
            }

            if ($sent === $length) {
                break;
            }

            $request = substr($request, $sent);
            $length -= $sent;

        } while (true);
    }

}