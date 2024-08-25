<?php

declare(strict_types=1);

namespace Seworqs\Laminas\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class HtmlClass extends AbstractHelper
{
    protected $classes = [];

    public function __invoke(...$classes)
    {
        $this->classes = array_merge($this->classes, $classes);
        return $this;
    }

    public function add(...$classes)
    {

        foreach ($classes as $class) {
            if (is_array($class)) {
                foreach ($class as $c) {
                    $this->_addClass($c);
                }
            } else {
                $this->_addClass($class);
            }
        }
        return $this;
    }

    private function _addClass($class)
    {
        if (! in_array($class, $this->classes)) {
            $this->classes[] = $class;
        }
    }

    public function remove(...$classes)
    {

        foreach ($classes as $class) {
            if (is_array($class)) {
                foreach ($class as $c) {
                    $this->_removeClass($c);
                }
            } else {
                $this->_removeClass($class);
            }
        }
        return $this;
    }

    private function _removeClass($class)
    {
        $this->classes = array_filter($this->classes, function ($c) use ($class) {

            return $c !== $class;
        });
        return $this;
    }
    public function reset()
    {
        $this->classes = [];
        return $this;
    }

    public function __toString()
    {
        return implode(' ', $this->classes);
    }
}
