<?php


namespace App\HandlerFactory;

/**
 * Interface HandlerInterface
 * @package App\HandlerFactory
 */
interface HandlerInterface
{
    public function createHandler(string $handler, $data);
}