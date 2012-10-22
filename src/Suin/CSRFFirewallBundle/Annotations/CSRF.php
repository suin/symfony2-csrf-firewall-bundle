<?php

namespace Suin\CSRFFirewallBundle\Annotations;

/**
 * @Annotation
 */
class CSRF
{
    public $check = true;

    /**
     * @return bool
     */
    public function checkIsEnabled()
    {
        if ( $this->check == true ) {
            return true;
        } else {
            return false;
        }
    }
}
