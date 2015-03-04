<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('array_column'))
{
    function array_column($array, $key, $use_key = FALSE)
    {
        $res = array();
        foreach($array as $row)
        {
            if(isset($row[$key]))
            {
                if($use_key)
                {
                    $res[$key] = $row[$key];
                }
                else{
                    $res[] = $row[$key];
                }
            }
        }

        return $res;
    }
}
