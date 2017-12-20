<?php
/**
 * Lever plugin for Craft CMS
 *
 * Lever Service
 *
 * @author    Trevor Davis
 * @copyright Copyright (c) 2017 Trevor Davis
 * @link      https://www.viget.com
 * @package   Lever
 * @since     1.0.0
 */

namespace Craft;

use Guzzle\Http\Client;

class LeverService extends BaseApplicationComponent
{
    protected $apiKey;
    protected $site;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = craft()->config->get('apiKey', 'lever');
        $this->site = craft()->config->get('site', 'lever');
        $this->baseUrl = 'https://api.lever.co/v0/postings/' . $this->site;
    }

    /**
     * Save applicant via Lever API
     * @return boolean
     */
    public function saveApplicant()
    {
        $post = craft()->request->getPost();
        $applyUrl = $this->baseUrl . '/' . $post['position'] . '?key=' . $this->apiKey;
        $optionalFields = ['phone', 'org', 'comments', 'silent', 'source'];

        $body = [
            'name' => $post['name'],
            'email' => $post['email'],
        ];

        // Add optional fields if they were provided
        foreach ($optionalFields as $key) {
            if (isset($post[$key])) {
                $body[$key] = $post[$key];
            }
        }

        // Split multiple URLs by newline
        if (isset($post['urls'])) {
            $body['urls'] = explode("\r\n", $post['urls']);
        }

        try {
            $client = new Client();
            $request = $client->post($applyUrl, [], $body);

            // Attach resume if it exists
            if (isset($_FILES['resume']) && $_FILES['resume']['name']) {
                $resume = \CUploadedFile::getInstanceByName('resume');

                $request->addPostFile('resume', $resume->tempName, $resume->type, $resume->name);
            }

            $response = $request->send();

            if ($response->isSuccessful()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            LeverPlugin::log('Error saving applicant: '.$e->getMessage(), LogLevel::Error);

            return false;
        }
    }

    /**
     * Validate required fields
     * @return mixed
     */
    public function validate()
    {
        $post = craft()->request->getPost();
        $errors = [];

        if (!$post['position']) {
            $errors['position'] = ['Position is a required field'];
        }

        if (!$post['name']) {
            $errors['name'] = ['Name is a required field'];
        }

        if (!$post['email']) {
            $errors['email'] = ['Email is a required field'];
        } else if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ['Please enter a valid email'];
        }

        return (empty($errors)) ? true : $errors;
    }

    /**
     * Retrieve positions from the Lever API
     * @return mixed
     */
    public function getPositions()
    {
        try {
            $client = new Client($this->baseUrl .'?mode=json');
            $request = $client->get();
            $response = $request->send();

            if (!$response->isSuccessful()) {
                return;
            }

            $data = $response->json();

            return $data;
        } catch (Exception $e) {
            LeverPlugin::log('Error retrieving positions: '.$e->getMessage(), LogLevel::Error);

            return false;
        }
    }

}
