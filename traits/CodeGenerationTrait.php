<?php
namespace Grav\Plugin;

/**
 * Trait CodeGenerationTrait
 */
trait CodeGenerationTrait
{
    /**
     * Retrieve the client ip address
     *
     * @return string
     */
    protected function getIpAddress() : string {
        $params = ['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED'];
        foreach ($params as $param) {
            if (isset($_SERVER)) {
                if (isset($_SERVER[$param])) return $_SERVER[$param];
            } else {
                if (getenv($param)) return getenv($param);
            }
        }
    }

    /**
     * Build the GA options
     *
     * @return string
     */
    protected function buildAnalyticsOptions() : string {
        $options = [];

        if ($this->config->get('plugins.google-analytics.anonymize_ip', false)) {
            $options['anonymize_ip'] = true;
        }

        if (!$this->config->get('plugins.google-analytics.advertising_features', true)) {
            $options['allow_ad_personalization_signals'] = false;
        }

        // Cookie
        if (!empty($this->config->get('plugins.google-analytics.cookie_domain', ''))) {
            $options['cookie_domain'] = $this->config->get('plugins.google-analytics.cookie_domain');
        }
        if (!empty($this->config->get('plugins.google-analytics.cookie_expires', ''))) {
            $options['cookie_expires'] = $this->config->get('plugins.google-analytics.cookie_expires');
        }
        if (!empty($this->config->get('plugins.google-analytics.cookie_prefix', ''))) {
            $options['cookie_prefix'] = $this->config->get('plugins.google-analytics.cookie_prefix');
        }
        if (!$this->config->get('plugins.google-analytics.cookie_update', true)) {
            $options['cookie_update'] = false;
        }


        return str_replace('"', '\'', json_encode($options));
    }

    /**
     * Generate the whole GA gtag code
     *
     * @return string
     */
    protected function generateAnalyticsCode() : string {
        $code = [];

        if ($this->config->get('plugins.google-analytics.opt_out', false)) {
            $code[] = "window['ga-disable-{$this->trackingId}'] = true;";
        }

        $name = trim($this->config->get('plugins.google-analytics.object_name', 'gtag'));

        $code[] = 'window.dataLayer = window.dataLayer || [];';
        $code[] = "function {$name}(){dataLayer.push(arguments);}";
        $code[] = "{$name}('js', new Date());";

        $options = $this->buildAnalyticsOptions();
        $code[] = empty($options) ? "gtag('config', '{$this->trackingId}');" : "gtag('config', '{$this->trackingId}', {$options});";

        return implode("\n  ", $code);
    }

}