<?php

namespace Bajke\BookBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BookBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }
}
