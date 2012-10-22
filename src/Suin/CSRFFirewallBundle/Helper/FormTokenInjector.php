<?php

namespace Suin\CSRFFirewallBundle\Helper;

use Suin\CSRFFirewallBundle\Helper\FormToken;

class FormTokenInjector
{
    /**
     * Inject form token
     * @param string $html HTML to inject tokens
     * @param FormToken $token
     * @return string HTML injected tokens
     */
    public function inject($html, FormToken $token)
    {
        $pattern = "#(?P<openTag><form[^>]*method\s*=\s*(['\"])post\\2[^>]*>)(?P<innerHTML>.*?)(?P<closeTag></form>)#is";

        $html = preg_replace_callback($pattern, function(array $matches) use($token) {
            $openTag   = $matches['openTag'];
            $closeTag  = $matches['closeTag'];
            $innerHTML = $matches['innerHTML'];
            return $openTag.$token.$innerHTML.$closeTag;
        }, $html);

        return $html;
    }
}
