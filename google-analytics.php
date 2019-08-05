<?php

namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use Grav\Framework\Psr7\ServerRequest;
use Grav\Plugin\GoogleAnalytics\Utils;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class GoogleAnalyticsPlugin
 * @package Grav\Plugin
 */
class GoogleAnalyticsPlugin extends Plugin
{

    /** @var string $trackingId */
    protected $trackingId;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                ['autoload', 100001],
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
     * [onPluginsInitialized:100000] Composer autoload.
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Don't proceed if the IP address is blocked
        $blockedIps = $this->config->get('plugins.google-analytics.blocked_ips', []);
        if (in_array(Utils::getIpAddress(), $blockedIps, true)) {
            return;
        }

        // Don't proceed if "Do Not Track" is activated
        if ($this->config->get('plugins.google-analytics.do_not_track', false)) {
            $dnt = $this->grav->get('request')->getHeader('DNT');
            if (!empty($dnt) && ((int)$dnt[0] === 1)) {
                return;
            }
        }

        // Don't proceed if there is no GA Tracking ID
        $this->trackingId = trim($this->config->get('plugins.google-analytics.tracking_id', ''));
        if (empty($this->trackingId)) {
            $this->grav['debugger']->addMessage('Google Analytics Plugin: No Tracking ID configured!', 'error');
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onOutputGenerated' => ['onOutputGenerated', 0],
        ]);
    }

    /**
     * The output has been processed by the Twig templating engine and is now just a string of HTML.
     *
     * @param Event $e
     */
    public function onOutputGenerated(Event $e): void
    {
        $code = Utils::generateAnalyticsCode($this->config);
        $code = implode(PHP_EOL, [
            '<!-- Global site tag (gtag.js) - Google Analytics -->',
            "<script src=\"https://www.googletagmanager.com/gtag/js?id={$this->trackingId}\" async></script>",
            '<script>',
            "  {$code}",
            '</script>',
        ]);

        $content = preg_replace('/<head\s?\S*?(>)/si', "$0\n\n{$code}\n", $this->grav->output);
        $this->grav->output = $content;
    }
}
