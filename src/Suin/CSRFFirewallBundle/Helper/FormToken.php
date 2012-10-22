<?php

namespace Suin\CSRFFirewallBundle\Helper;

class FormToken
{
    /** @var string */
    private $name;
    /** @var string */
    private $value;

    /**
     * Set token name
     * @param string $name
     * @return FormToken
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Return token name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set token value
     * @param string $value
     * @return FormToken
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Return token value
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Render form token HTML
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '<input type="hidden" name="%s" value="%s" />',
            htmlspecialchars($this->name),
            htmlspecialchars($this->value)
        );
    }
}
