<?php
/**
 * Lever plugin for Craft CMS
 *
 * Lever Controller
 *
 * @author    Trevor Davis
 * @copyright Copyright (c) 2017 Trevor Davis
 * @link      https://www.viget.com
 * @package   Lever
 * @since     1.0.0
 */

namespace Craft;

class LeverController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = array('actionSaveApplicant',);

    /**
     * Process form submission, validate, and submit through Lever API
     * @return void
     */
    public function actionSaveApplicant()
    {
        $this->requirePostRequest();

        $response = craft()->lever->validate();

        // If array, there are errors
        if (is_array($response)) {
            $post = craft()->request->getPost();

            craft()->urlManager->setRouteVariables(array_merge($post, [
                'errors' => $response
            ]));
        } else {
            $saveApplicant = craft()->lever->saveApplicant();

            if ($saveApplicant) {
                $this->redirectToPostedUrl();
            }
        }
    }
}
