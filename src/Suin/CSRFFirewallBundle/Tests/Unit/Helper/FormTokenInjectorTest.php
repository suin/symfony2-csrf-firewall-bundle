<?php

namespace Suin\CSRFFirewallBundle\Tests\Unit\Helper;

use Suin\CSRFFirewallBundle\Helper\FormToken;
use Suin\CSRFFirewallBundle\Helper\FormTokenInjector;

class FormTokenInjectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $html
     * @param $expect
     * @dataProvider dataForTestInject
     */
    public function testInject($html, $expect)
    {
        // precondition
        $token = (new FormToken)
            ->setName('token-name')
            ->setValue('token-value');

        // test
        $injector = new FormTokenInjector();
        $actual = $injector->inject($html, $token);
        $this->assertSame($expect, $actual);
    }

    public static function dataForTestInject()
    {
        $data = [

            // no method form
            [
                '<body><form>some html</form></body>',
                '<body><form>some html</form></body>',
            ],

            // get method form
            [
                '<body><form method="get">some html</form></body>',
                '<body><form method="get">some html</form></body>',
            ],

            // post method form
            [
                '<body><form method="post">some html</form></body>',
                '<body><form method="post"><input type="hidden" name="token-name" value="token-value" />some html</form></body>',
            ],

            // post method form with upper case method name
            [
                '<body><form method="POST">some html</form></body>',
                '<body><form method="POST"><input type="hidden" name="token-name" value="token-value" />some html</form></body>',
            ],

            // post method form with upper case
            [
                '<body><FORM METHOD="POST">some html</FORM></body>',
                '<body><FORM METHOD="POST"><input type="hidden" name="token-name" value="token-value" />some html</FORM></body>',
            ],

            // two post forms
            [
                '<body>
                    <form method="post">first-form-contents</form>
                    some html may be here
                    <form method="post">second-form-contents</form>
                </body>',
                '<body>
                    <form method="post"><input type="hidden" name="token-name" value="token-value" />first-form-contents</form>
                    some html may be here
                    <form method="post"><input type="hidden" name="token-name" value="token-value" />second-form-contents</form>
                </body>',
            ],

            // post form with attributes
            [
                '<body><form action="/edit/" method="post" id="foo">some html</form></body>',
                '<body><form action="/edit/" method="post" id="foo"><input type="hidden" name="token-name" value="token-value" />some html</form></body>',
            ],

            // post form contains HTMLs
            [
                '<body>
                    <form method="post">
                        <input type="text" name="title" value="">
                        <textarea rows="2" cols="30" name="body"></textarea>
                    </form>
                </body>',
                '<body>
                    <form method="post"><input type="hidden" name="token-name" value="token-value" />
                        <input type="text" name="title" value="">
                        <textarea rows="2" cols="30" name="body"></textarea>
                    </form>
                </body>',
            ],
        ];

        return $data;
    }
}
