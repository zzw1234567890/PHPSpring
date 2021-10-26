<?php

namespace Lib\Aware;

interface IComponentAware{
    function parseComponent($appContext, $instance, $reflection);
}