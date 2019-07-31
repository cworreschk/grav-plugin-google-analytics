<?php
namespace Grav\Plugin;

require_once __DIR__ . '/traits/CodeGenerationTrait.php';

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;


/**
 * Class GoogleAnalyticsPlugin
 * @package Grav\Plugin
 */
class GoogleAnalyticsPlugin extends Plugin
{
    use CodeGenerationTrait;

    /** @var string $trackingId */
    protected $trackingId;

    /**
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized() : void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) return;

        // Don't proceed if the IP address is blocked
        $blockedIps = $this->config->get('plugins.google-analytics.blocked_ips', []);
        if (in_array($this->getIpAddress(), $blockedIps)) return;

        // Don't proceed if there is no GA Tracking ID
        $this->trackingId = trim($this->config->get('plugins.google-analytics.tracking_id', ''));
        if (empty($this->trackingId)){
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
    public function onOutputGenerated(Event $e) : void
    {
        $code = implode(PHP_EOL, [
            '<!-- Global site tag (gtag.js) - Google Analytics -->',
            "<script src=\"https://www.googletagmanager.com/gtag/js?id={$this->trackingId}\" async></script>",
            '<script>',
            "  {$this->generateAnalyticsCode()}",
            '</script>',
        ]);

        $content = preg_replace('/<head\s?\S*?(>)/si', "$0\n\n{$code}\n", $this->grav->output);
        $this->grav->output = $content;
    }
}
