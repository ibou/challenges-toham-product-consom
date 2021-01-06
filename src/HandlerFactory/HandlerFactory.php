<?php


namespace App\HandlerFactory;

/**
 * Class HandlerFactory
 * @package App\HandlerFactory
 */
class HandlerFactory implements HandlerFactoryInterface
{
    private iterable $handlers;
    
    /**
     * HandlerFactory constructor.
     * @param iterable $handlers
     */
    public function __construct(iterable $handlers)
    {
        $this->handlers = $handlers;
    }
    
    
    public function createHandler(string $handler, $data): HandlerInterface
    {
    
    }
    
}