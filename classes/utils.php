<?php

namespace Grav\Plugin\GoogleAnalytics;

use Grav\Common\Config\Config;

/**
 * Class Utils
 * @package Grav\Plugin\GoogleAnalytics
 */
class Utils
{

    /**
     * Retrieve the client ip address
     *
     * @return string
     */
    public static function getIpAddress(): string
    {
        $params = ['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED'];
        foreach ($params as $param) {
            if (isset($_SERVER)) {
                if (isset($_SERVER[$param])){
                    return $_SERVER[$param];
                }
            } elseif (getenv($param)) {
                return getenv($param);
            }
        }

        return '';
    }

    /**
     * Build the GA options
     *
     * @param Config $config Grav configuration
     * @return string
     */
    protected static function buildAnalyticsOptions(Config $config): string
    {
        $options = [];

        if ($config->get('plugins.google-analytics.anonymize_ip', false)) {
            $options['anonymize_ip'] = true;
        }

        if (!$config->get('plugins.google-analytics.advertising_features', true)) {
            $options['allow_ad_personalization_signals'] = false;
        }

        // Cookie
        if (!empty($config->get('plugins.google-analytics.cookie_domain', ''))) {
            $options['cookie_domain'] = $config->get('plugins.google-analytics.cookie_domain');
        }
        if (!empty($config->get('plugins.google-analytics.cookie_expires', ''))) {
            $options['cookie_expires'] = $config->get('plugins.google-analytics.cookie_expires');
        }
        if (!empty($config->get('plugins.google-analytics.cookie_prefix', ''))) {
            $options['cookie_prefix'] = $config->get('plugins.google-analytics.cookie_prefix');
        }
        if (!$config->get('plugins.google-analytics.cookie_update', true)) {
            $options['cookie_update'] = false;
        }

        return str_replace('"', '\'', json_encode($options));
    }

    /**
     * Generate the whole GA gtag code
     *
     * @param Config $config Grav configuration
     * @return string
     */
    public static function generateAnalyticsCode(Config $config): string
    {

        $tracking_id = trim($config->get('plugins.google-analytics.tracking_id', ''));
        $code = [];

        if ($config->get('plugins.google-analytics.opt_out', false)) {
            $code[] = "window['ga-disable-{$tracking_id}'] = true;";
        }

        $name = trim($config->get('plugins.google-analytics.object_name', 'gtag'));

        $code[] = 'window.dataLayer = window.dataLayer || [];';
        $code[] = "function {$name}(){dataLayer.push(arguments);}";
        $code[] = "{$name}('js', new Date());";

        $options = static::buildAnalyticsOptions($config);
        $code[] = empty($options) ? "{$name}('config', '{$tracking_id}');" : "{$name}('config', '{$tracking_id}', {$options});";

        return implode("\n  ", $code);
    }

}