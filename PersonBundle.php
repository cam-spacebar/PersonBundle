<?php

namespace VisageFour\Bundle\PersonBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PersonBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
