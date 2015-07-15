<?php

namespace Orbit\Machine\Twig\Engine;

/**
 * \Phalcon\Mvc\View\Engine\Twig\CoreExtension
 * Core extension for Twig engine.
 * Currently supports only work with \Phalcon\Assets\Manager.
 */
class CoreExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'phalcon-core-extension';
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getFunctions()
    {
        $options = [
            'needs_environment' => true,
            'pre_escape' => 'html',
            'is_safe' => ['html'],
        ];

        return [
            'assetsOutputCss' => new \Twig_Function_Method($this, 'functionAssetsOutputCss', $options),
            'assetsOutputJs' => new \Twig_Function_Method($this, 'functionAssetsOutputJs', $options),
        ];
    }

    /**
     * Returns string with CSS.
     *
     * @param  Environment $env
     * @param  string|null $options Assets CollectionName
     * @return string
     */
    public function functionAssetsOutputCss(Environment $env, $options = null)
    {
        return $this->getAssetsOutput($env, 'outputCss', $options);
    }

    /**
     * Returns string with JS.
     *
     * @param  Environment $env
     * @param  string|null $options Assets CollectionName
     * @return string
     */
    public function functionAssetsOutputJs(Environment $env, $options = null)
    {
        return $this->getAssetsOutput($env, 'outputJs', $options);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getTokenParsers()
    {
        return [
            new TokenParsers\Assets(),
        ];
    }

    /**
     * Proxy method that handles return of assets instead of instant output.
     *
     * @param  Environment $env
     * @param  string $method
     * @param  string|null $options Assets CollectionName
     * @return string
     */
    protected function getAssetsOutput(Environment $env, $method, $options = null)
    {
        $env->getDi()->get('assets')->useImplicitOutput(false);
        $result = $env->getDi()->get('assets')->$method($options);
        $env->getDi()->get('assets')->useImplicitOutput(true);

        return $result;
    }
}