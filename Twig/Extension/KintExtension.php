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
use Twig_Extension;
use Twig_Function_Method;
use Twig_Environment;
use Kint;

class KintExtension extends Twig_Extension
{
    private $enabled;
    private $nesting_depth;
    private $string_length;
    private $kernel;
  
    public function __construct($enabled,$nesting_depth,$string_length,$kernel)
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
            'kint' => new Twig_Function_Method($this,'twig_kint', array('is_safe' => array('html'), 'needs_context' => true, 'needs_environment' => true)),
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

    public function twig_kint(Twig_Environment $env, $context)
    {
        if (!$env->isDebug() || !$this->enabled) {
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
        if (2 === $count) {
            $kint_variable = array();
            foreach ($context as $key => $value) {
                if (!$value instanceof Twig_Template) {
                    $kint_variable[$key] = $value;
                }
            }

            ob_start();
            Kint::dump($kint_variable);
            $output = ob_get_clean();
            $output = str_replace ('$kint_variable','TWIG CONTEXT',$output);     

        } else {

            //we try to get the names of the variables to display

            $trace = debug_backtrace();
            $callee = $trace[0];

            $lines   = file($callee['file']);
            $source = $lines[$callee['line']-1];

            preg_match('/twig_kint\((.+)\);/',$source,$matches);
            $parameters = $matches[1];
            $parameters = preg_replace('/\$this->getContext\(\$context, "(.+)"\)/U',"$1",$parameters);
            do {
                $parameters = preg_replace('/\$this->getAttribute\((.+), "(.+)"\)/U',"$1.$2",$parameters,1,$found);
            } while ($found);

            $parameters = explode(', ',$parameters);

            for ($i = 2; $i < $count; $i++) {
                $kint_variable = func_get_arg($i);
                ob_start();
                Kint::dump($kint_variable);
                $result = ob_get_clean();
                $output .= str_replace ('$kint_variable',$parameters[$i],$result);
            }
        }

        return $output;

    }

}


