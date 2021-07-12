<?php

/*
 * This file is part of KintBundle.
 *
 * (c) 2012 Carlos Granados
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cg\KintBundle\Twig\Extension;

use Kint;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Twig\Template;

class KintExtension extends AbstractExtension
{
    private $enabled;
    private $nesting_depth;
    private $string_length;
    private $kernel;

    public function __construct($enabled, $nesting_depth, $string_length, $kernel)
    {
        $this->enabled = $enabled;
        $this->nesting_depth = $nesting_depth;
        $this->string_length = $string_length;
        $this->kernel = $kernel;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            'kint' => new TwigFunction('kint', array($this, 'twig_kint'), array('is_safe' => array('html'), 'needs_context' => true)),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'kint';
    }

    public function twig_kint($context)
    {
        if (!$this->enabled) {
            return;
        }

        Kint::$displayCalledFrom = false;
        Kint::$devel = false;
        Kint::$maxLevels = $this->nesting_depth;
        Kint::$maxStrLength = $this->string_length;
        Kint::$appRootDirs = array(
            $this->kernel->getRootDir() => '&lt;APP ROOT&gt;'
        );

        $output = '';

        $count = func_num_args();
        if (1 === $count) {
            //no extra parameters passed, so we dump the whole twig context
            $kint_variable = array();
            foreach ($context as $key => $value) {
                if (!$value instanceof Template) {
                    $kint_variable[$key] = $value;
                }
            }

            ob_start();
            Kint::dump($kint_variable);
            $output = ob_get_clean();
            $output = str_replace('$kint_variable', 'TWIG CONTEXT', $output);

        } else {

            for ($i = 1; $i < $count; $i++) {
                $kint_variable = func_get_arg($i);
                ob_start();
                Kint::dump($kint_variable);
                $result = ob_get_clean();
                $output .= str_replace('$kint_variable', $i, $result);
            }
        }

        return $output;

    }

}
