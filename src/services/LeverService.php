<?php
/**
 * Lever plugin for Craft CMS 3.x
 *
 * Wrapper to integrate with the Lever API
 *
 * @link      https://www.viget.com/
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace viget\lever\services;

use viget\lever\Lever;

use Craft;
use craft\base\Component;
use GuzzleHttp\Client;
use craft\helpers\Json;
use yii\validators\EmailValidator;
use craft\web\UploadedFile;

/**
 * @author    Trevor Davis
 * @package   Lever
 * @since     2.0.0
 */
class LeverService extends Component
{
    protected $apiKey;
    protected $site;
    protected $baseUrl;

    // Public Methods
    // =========================================================================
    public function __construct()
    {
        $this->apiKey = Lever::getInstance()->settings->apiKey;
        $this->site = Lever::getInstance()->settings->site;
        $this->baseUrl = 'https://api.lever.co/v0/postings/' . $this->site;
    }

    /**
     * Save applicant via Lever API
     * @return boolean
     */
    public function saveApplicant()
    {
        $post = Craft::$app->request->post();
        $applyUrl = $this->baseUrl . '/' . $post['position'] . '?key=' . $this->apiKey;
        $optionalFields = ['phone', 'org', 'comments', 'silent', 'source'];
        $multipart = [];

        $multipart[] = [
            'name' => 'name',
            'contents' => $post['name'],
        ];

        $multipart[] = [
            'name' => 'email',
            'contents' => $post['email'],
        ];

        // Add optional fields if they were provided
        foreach ($optionalFields as $key) {
            if (isset($post[$key])) {

                $multipart[] = [
                    'name' => $key,
                    'contents' => $post[$key],
                ];
            }
        }

        // Split multiple URLs by newline
        if (isset($post['urls'])) {
            $urls = explode("\r\n", $post['urls']);

            foreach ($urls as $url) {
                $provider = parse_url($url, PHP_URL_HOST);

                $multipart[] = [
                    'name' => "urls[$provider]",
                    'contents' => $url,
                ];
            }
        }

        // Attach resume if it exists
        if (isset($_FILES['resume']) && $_FILES['resume']['name']) {
            $resume = UploadedFile::getInstanceByName('resume');

            $multipart[] = [
                'name' => 'resume',
                'contents' => fopen($resume->tempName, 'r'),
                'filename' => $resume->name,
            ];
        }

        try {
            $client = new Client();
            $response = $client->request('POST', $applyUrl, [
                'multipart' => $multipart
            ]);

            if ($response->getStatusCode() === 200) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Craft::error(
                'Error saving applicant: '.$e->getMessage(),
                __METHOD__
            );

            return false;
        }
    }

    /**
     * Validate required fields
     * @return mixed
     */
    public function validateRequiredFields()
    {
        $post = Craft::$app->request->post();
        $emailValidator = new EmailValidator();
        $errors = [];

        if (!$post['position']) {
            $errors['position'] = ['Position is a required field'];
        }

        if (!$post['name']) {
            $errors['name'] = ['Name is a required field'];
        }

        if (!$post['email']) {
            $errors['email'] = ['Email is a required field'];
        } else if(!$emailValidator->validate($post['email'])) {
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
            $client = new Client();
            $response = $client->get($this->baseUrl . '?mode=json');

            if ($response->getStatusCode() !== 200) return;

            $data = Json::decodeIfJson($response->getBody()->getContents());

            return $data;
        } catch (Exception $e) {
            Craft::error(
                'Error retrieving positions: '.$e->getMessage(),
                __METHOD__
            );

            return false;
        }
    }
}
