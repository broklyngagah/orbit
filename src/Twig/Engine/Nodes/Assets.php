<?php

namespace Orbit\Machine\Twig\Engine\Nodes;

class Assets extends \Twig_Node
{
    /**
     * {@inheritdoc}
     *
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write('$this->env->getDI()->get(\'assets\')->')
            ->raw($this->getAttribute('methodName'))
            ->write('(');
        $nbArgs = count($this->getNode('arguments'));
        $i = 0;
        foreach($this->getNode('arguments') as $argument) {
            $compiler->subcompile($argument);
            if(++$i < $nbArgs) {
                $compiler->raw(', ');
            }
        }
        $compiler->write(");\n");
    }
}