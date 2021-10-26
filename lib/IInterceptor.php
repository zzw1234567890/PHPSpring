<?php

namespace Lib;

interface IInterceptor{
  	function intercept($clientId, $message) : bool;
}