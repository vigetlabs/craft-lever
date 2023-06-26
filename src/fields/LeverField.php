<?php
/**
 * Lever plugin for Craft CMS 4.x
 *
 * Wrapper to integrate with the Lever API
 *
 * @link      https://www.viget.com/
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace viget\lever\fields;

use viget\lever\Lever;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    Trevor Davis
 * @package   Lever
 * @since     2.0.0
 */
class LeverField extends Field
{
    // Public Properties
    // =========================================================================
    //
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('lever', 'Lever');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        if (empty($value)) return NULL;
        if (is_array($value)) return $value;

        $selections = Json::decodeIfJson($value);
        $values = [];

        foreach ($selections as $selection) {
            $values[] = is_array($selection) ? $selection : Json::decodeIfJson($selection);
        }

        return $values;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml(mixed $value, ?\craft\base\ElementInterface $element = null): string
    {
        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        $data = Lever::getInstance()->leverService->getPositions();

        $options = [];

        foreach ($data as $position) {
            $option = [
                'leverId' => $position['id'],
                'leverTitle' => $position['text'],
            ];

            $options[] = $option;
        }

        $variables = [
            'id' => $id,
            'name' => $this->handle,
            'namespaceId' => $namespacedId,
            'values' => $value,
            'options' => $options
        ];

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'lever/_components/fields/LeverField_input',
            $variables
        );
    }
}
