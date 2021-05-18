<?php

namespace augmentations\craft\json;

use augmentations\craft\json\fields\JsonField;
use augmentations\craft\json\gql\JsonDecodeDirective;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterGqlDirectivesEvent;
use craft\services\Fields;
use craft\services\Gql;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    /**
     * @var self
     */
    public static $plugin;

    /**
     * @var string
     */
    public $schemaVersion = "1.0.0";

    /**
     * @var bool
     */
    public $hasCpSettings = false;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        self::$plugin = $this;

        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (
            RegisterComponentTypesEvent $event
        ) {
            $event->types[] = JsonField::class;
        });

        Event::on(Gql::class, Gql::EVENT_REGISTER_GQL_DIRECTIVES, function (
            RegisterGqlDirectivesEvent $event
        ) {
            $event->directives[] = JsonDecodeDirective::class;
        });
    }
}
