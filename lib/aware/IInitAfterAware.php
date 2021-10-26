<?php

namespace Lib\Aware;

interface IInitAfterAware{
    function initAfter($instance, $reflection);
}