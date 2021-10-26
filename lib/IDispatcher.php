<?php

namespace Lib;

interface IDispatcher{
  	function dispatch($clientId, $message);
}