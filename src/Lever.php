<?php
/**
 * Lever plugin for Craft CMS 3.x
 *
 * Wrapper to integrate with the Lever API
 *
 * @link      https://www.viget.com/
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace viget\lever;

use viget\lever\services\LeverService as LeverServiceService;
use viget\lever\fields\LeverField as LeverFieldField;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class Lever
 *
 * @author    Trevor Davis
 * @package   Lever
 * @since     2.0.0
 *
 * @property  LeverServiceService $leverService
 */
class Lever extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Lever
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '2.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'lever/default/save-applicant';
            }
        );

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = LeverFieldField::class;
            }
        );

        Craft::info(
            Craft::t(
                'lever',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel()
    {
        return new \viget\lever\models\Settings();
    }

}
