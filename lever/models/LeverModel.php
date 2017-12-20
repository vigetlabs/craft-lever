<?php
/**
 * Lever plugin for Craft CMS
 *
 * Lever Model
 *
 * @author    Trevor Davis
 * @copyright Copyright (c) 2017 Trevor Davis
 * @link      https://www.viget.com
 * @package   Lever
 * @since     1.0.0
 */

namespace Craft;

class LeverModel extends BaseModel
{
    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'leverId' => array(AttributeType::String),
            'leverTitle' => array(AttributeType::String),
        ));
    }


    /**
     * Use the title as its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->leverTitle;
    }
}
