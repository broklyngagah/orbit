<?php

namespace Orbit\Machine\Twig\Engine\TokenParsers;

use Twig\Engine\Nodes\Assets as Node;

/**
 * \Phalcon\Mvc\View\Engine\Twig\TokenParsers\Assets
 * The "asset" tag realization.
 * Example of usage:
 *  {% assets addCss('//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css', false) %}
 *  {% assets addCss('css/style.css', true) %}
 *  {% assets addJs('js/jquery.js') %}
 *  {% assets addJs('js/bootstrap.min.js') %}
 */
class Assets extends \Twig_TokenParser
{
    /**
     * {@inheritdoc}
     *
     * @param  \Twig_Token         $token
     * @return \Twig_NodeInterface
     */
    public function parse(\Twig_Token $token)
    {
        $methodName = $this->parser->getStream()->expect(\Twig_Token::NAME_TYPE)->getValue();
        $arguments = $this->parser->getExpressionParser()->parseArguments();
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
        return new Node(
            array('arguments' => $arguments),
            array('methodName' => $methodName),
            $token->getLine(),
            $this->getTag()
        );
    }
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getTag()
    {
        return 'assets';
    }
}