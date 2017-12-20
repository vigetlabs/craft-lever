<?php
/**
 * Lever plugin for Craft CMS
 *
 * Lever FieldType
 *
 * @author    Trevor Davis
 * @copyright Copyright (c) 2017 Trevor Davis
 * @link      https://www.viget.com
 * @package   Lever
 * @since     1.0.0
 */

namespace Craft;

class LeverFieldType extends BaseFieldType
{
    protected $multi = true;

    /**
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('Lever');
    }

    /**
     * @return mixed
     */
    public function defineContentAttribute()
    {
        return AttributeType::Mixed;
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return string
     */
    public function getInputHtml($name, $value)
    {
        if (!$value) {
            $value = new LeverModel();
        }

        $id = craft()->templates->formatInputId($name);
        $namespacedId = craft()->templates->namespaceInputId($id);

        $data = craft()->lever->getPositions();

        $options = array();

        foreach ($data as $position) {
            $option = new LeverModel();
            $option->leverId = $position['id'];
            $option->leverTitle = $position['text'];

            $options[] = $option;
        }

        $variables = array(
            'id' => $id,
            'name' => $name,
            'namespaceId' => $namespacedId,
            'values' => $value,
            'options' => $options
        );

        return craft()->templates->render('lever/fields/LeverFieldType.twig', $variables);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function prepValueFromPost($value)
    {
        if (empty($value)) {
            return null;
        }

        $values = array();

        foreach ($value as $selection) {
            $selection = json_decode($selection);
            $model = new LeverModel();
            $model->leverId = $selection->leverId;
            $model->leverTitle = $selection->leverTitle;

            $values[] = $model;
        }

        return $values;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function prepValue($value)
    {
        return $value;
    }
}
