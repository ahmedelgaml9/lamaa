<?php

namespace BadeePayfort;


use BadeePayfort\Services\PayfortAPI;
use BadeePayfort\Services\PayfortRedirection;

class PayfortFacadeAccessor
{
    /**
     * Get Payfort API services provider
     *
     * @param array $extra_config Extra configurations
     * @return \BadeePayfort\Services\PayfortAPI
     */
    public static function api($extra_config = [])
    {
        $config = array_merge(config('payfort'), $extra_config);
        return new PayfortAPI($config);
    }

    /**
     * Get Payfort Redirection services provider
     *
     * @param array $extra_config Extra configurations
     * @return \BadeePayfort\Services\PayfortRedirection
     */
    public static function redirection($extra_config = [])
    {
        $config = array_merge(config('payfort'), $extra_config);
        return new PayfortRedirection($config);
    }
}
