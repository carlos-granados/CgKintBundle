<?php

namespace Cg\KintBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

class KintExtensionTest extends WebTestCase
{
    private $kintVars = array(
        'var',
        'var2',
        '"hello"',
        'app.user',
    );
    
   
    public function testVariables()
    {
        $client = $this->createClient();
        $router = self::$kernel->getContainer()->get('router');
        $crawler = $client->request('GET', $router->generate('test_variables'));
        $divs = $crawler->filter('.kint');
        for ($i=0;$i<$divs->count();$i++) {
            $kintVar = $divs->eq($i)->filterXPath('//dfn')->text();
            $this->assertEquals($kintVar ,$this->kintVars[$i]);           
        }
    }

    public function testTwigContext()
    {
        $client = $this->createClient();
        $router = self::$kernel->getContainer()->get('router');
        $crawler = $client->request('GET', $router->generate('test_twig_context'));
        $divs = $crawler->filter('.kint');
        $this->assertEquals(1,$divs->count());
        $kintVar = $divs->eq(0)->filterXPath('//dfn')->text();
        $this->assertEquals($kintVar ,'TWIG CONTEXT');           
    }

    public function testKintDisabled()
    {
        $options['config'] = 'disabled.yml';
        $client = $this->createClient($options);
        $router = self::$kernel->getContainer()->get('router');
        $crawler = $client->request('GET', $router->generate('test_variables'));
        $divs = $crawler->filter('.kint');
        $this->assertEquals(0,$divs->count());
    }

    public function testKintOptions()
    {
        $options['config'] = 'options.yml';
        $client = $this->createClient($options);
        $router = self::$kernel->getContainer()->get('router');
        $crawler = $client->request('GET', $router->generate('test_variables'));
        $divs = $crawler->filter('.kint');
        $this->assertRegExp('/DEPTH TOO GREAT/',$divs->eq(0)->text());
        $this->assertRegExp('/he.../',$divs->eq(2)->text());
    }

    protected function setUp()
    {
        $fs = new Filesystem();
        $fs->remove(__DIR__.'/Cache/');
    }

    protected static function createKernel(array $options = array())
    {
        return self::$kernel = new AppKernel(
            isset($options['config']) ? $options['config'] : 'default.yml'
        );
    }

}