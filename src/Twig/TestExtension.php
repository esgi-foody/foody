<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TestExtension extends AbstractExtension {

    /**
     * @return array
     */
    public function getFilters():array
    {
        return [
            new TwigFilter('string', [$this, 'test']),
        ];
    }

    /**
     * @param $test
     * @return mixed
     */
    public function test($string)
    {
        return $string;
    }
}