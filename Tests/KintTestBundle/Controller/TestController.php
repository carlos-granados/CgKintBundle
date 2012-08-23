<?php
namespace Cg\KintBundle\Tests\KintTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function showVariablesAction()
    {
        $var = array (
            array(
                array(
                    'test' => 1                
                )
            )
        );
        return $this->render('KintTestBundle:Test:variables.html.twig', array('var'=>$var));
    }

    public function showTwigContextAction()
    {
        return $this->render('KintTestBundle:Test:twig_context.html.twig');
    }
    
}
?>
