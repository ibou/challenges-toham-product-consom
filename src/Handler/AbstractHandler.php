<?php


namespace App\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractHandler
 * @package App\Handler
 */
abstract class AbstractHandler
{
    private FormFactoryInterface $formFactory;
    
    private FormInterface $form;
    
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }
    
    /**
     * @required
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }
    
    public function handle(Request $request, $data): bool
    {
        $this->form = $this->formFactory->create($this->getFormType(), $data, [])->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->process($data);
            return true;
        }
        
        return false;
    }
    
    abstract protected function getFormType(): string;
    
    abstract protected function process($data): void;
    
    public function createView(): FormView
    {
        return $this->form->createView();
    }
}
