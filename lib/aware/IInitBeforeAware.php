<?php

namespace Lib\Aware;

interface IInitBeforeAware{
    function initBefore($instance, $reflection);
}