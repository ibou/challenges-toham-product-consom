<?php


namespace App\HandlerFactory;

/**
 * Interface HandlerFactoryInterface
 * @package App\HandlerFactory
 */
interface HandlerFactoryInterface
{
    /**
     * @param string $handler
     * @param mixed $data
     * @return HandlerInterface
     */
    public function createHandler(string $handler, mixed $data): HandlerInterface;
}