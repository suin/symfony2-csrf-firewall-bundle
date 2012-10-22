<?php

namespace Suin\CSRFFirewallBundle\Tests\Unit\Helper;

use Suin\CSRFFirewallBundle\Helper\FormToken;

class FormTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testSetName()
    {
        $formToken = new FormToken();

        // precondition
        $this->assertAttributeSame(null, 'name', $formToken);

        // test
        $name = 'token-name';
        $formToken->setName($name);
        $this->assertAttributeSame($name, 'name', $formToken);
    }

    public function testGetName()
    {
        $formToken = new FormToken();

        // precondition
        $name = 'token-name';
        $formToken->setName($name);

        // test
        $this->assertSame($name, $formToken->getName());
    }

    public function testSetValue()
    {
        $formToken = new FormToken();

        // precondition
        $this->assertAttributeSame(null, 'value', $formToken);

        // test
        $value = 'token-value';
        $formToken->setValue($value);
        $this->assertAttributeSame($value, 'value', $formToken);
    }

    public function testGetValue()
    {
        $formToken = new FormToken();

        // precondition
        $value = 'token-value';
        $formToken->setValue($value);

        // test
        $this->assertSame($value, $formToken->getValue());
    }

    public function test__toString()
    {
        // precondition
        $formToken = (new FormToken)
            ->setName('token-name')
            ->setValue('token-value');

        // test
        $expect = '<input type="hidden" name="token-name" value="token-value" />';
        $this->assertSame($expect, strval($formToken));
    }
}
