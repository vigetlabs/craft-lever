<?php
/**
 * Lever plugin for Craft CMS 4.x
 *
 * Wrapper to integrate with the Lever API
 *
 * @link      https://www.viget.com/
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace viget\lever\controllers;

use viget\lever\Lever;

use Craft;
use craft\web\Controller;

/**
 * @author    Trevor Davis
 * @package   Lever
 * @since     2.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|int|bool $allowAnonymous = ['save-applicant'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionSaveApplicant()
    {
        $this->requirePostRequest();

        $response = Lever::getInstance()->leverService->validateRequiredFields();

        // If array, there are errors
        if (is_array($response)) {
            $post = Craft::$app->request->post();

            Craft::$app->urlManager->setRouteParams(array_merge($post, [
                'errors' => $response
            ]));
        } else {
            $saveApplicant = Lever::getInstance()->leverService->saveApplicant();

            if ($saveApplicant) {
                $this->redirectToPostedUrl();
            }
        }
    }
}
