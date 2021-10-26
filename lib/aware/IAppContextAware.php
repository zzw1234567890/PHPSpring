<?php

namespace Lib\Aware;

use Lib\AppContext;

interface IAppContextAware{
    function initBefore(AppContext $appContext);

    function initAfter(AppContext $appContext);
}