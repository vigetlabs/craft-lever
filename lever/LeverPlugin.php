<?php
/**
 * Lever plugin for Craft CMS
 *
 * Wrapper to integrate with the Lever API
 *
 * @author    Trevor Davis
 * @copyright Copyright (c) 2017 Trevor Davis
 * @link      https://www.viget.com
 * @package   Lever
 * @since     1.0.0
 */

namespace Craft;

class LeverPlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init()
    {
        parent::init();
    }

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
    public function getDescription()
    {
        return Craft::t('Save job applicants to Lever');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/vigetlabs/craft-lever/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/vigetlabs/craft-lever/master/releases.json';
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper()
    {
        return 'Trevor Davis';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'https://www.viget.com';
    }

    /**
     * @return bool
     */
    public function hasCpSection()
    {
        return false;
    }
}
