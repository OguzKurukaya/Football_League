<?php

if (!function_exists('stdToArray')) {
    function stdToArray($stdClass)
    {
      return json_decode(json_encode($stdClass), true);
    }
}
