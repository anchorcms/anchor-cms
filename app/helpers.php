<?php

namespace Anchorcms {

    function dd(...$args)
    {
        throw new \ErrorException(var_export($args));
    }

}
