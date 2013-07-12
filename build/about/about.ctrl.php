<?php

/**
 * Aboutus controller
 *
 * @package about
 * @author Andreas (lemon-head)
 * @copyright hmm
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License (GPL)
 * @version $Id$
 */
class AboutController extends RoxControllerBase
{
    public function index($args = false)
    {
        $request = $args->request;

        if (!isset($request[0])) {
            // then who activated the about controller?
            $page = new AboutTheideaPage();
        } else if ($request[0] != 'about') {
            $page = $this->_getPageByKeyword($request[0], isset($request[1]) ? $request[1] : false);
        } else if (!isset($request[1])) {
            $page = new AboutTheideaPage();
        } else {
            $page = $this->_getPageByKeyword($request[1], isset($request[2]) ? $request[2] : false);
        }
        return $page;
    }

    private function _getPageByKeyword($keyword, $keyword_2)
    {
        switch ($keyword) {
            case 'thepeople':
                return new AboutThepeoplePage();
            case 'getactive':
                return new AboutGetactivePage();
            case 'newsletters':
            case 'missions':
            case 'bod':
            case 'help':
            case 'terms':
            case 'impressum':
            case 'affiliations':
            case 'privacy':
                $page = new AboutGenericPage($keyword);
                $page->setModel(new AboutModel());
                return $page;
            case 'stats':
            case 'statistics':
                error_log("Keyword_2: " . $keyword_2);
                if (!empty($keyword_2)) {
                    // return the given image
                    header('Content-type: image/png');
                    $statsDir = new PDataDir('statimages');
                    $statsDir->readFile($keyword_2);
                    PPHP::PExit();
                }
                $statsModel = new StatsModel();
                // Generate new statsImages if needed
                $statsModel->generateStatsImages();
                $page = new AboutStatisticsPage();
                $page->setModel($statsModel);
                return $page;
            case 'feedback':
            case 'contact':
            case 'contactus':
            case 'support':
                if (isset($keyword_2) && $keyword_2 == "submit") {
                    return new FeedbackSentPage;
                }
                $page = new FeedbackPage();
                $page->model = new FeedbackModel();
                return $page;
            case 'faq':
            case 'faqs':
                $model = new AboutModel;
                $faq_categories = $model->getFaqsCategorized();
                if ($faq_section = $model->getFaqSection($keyword_2)) {
                    $page = new AboutFaqsectionPage;
                    $page->faq_section = $faq_section;
                    $page->key = $keyword_2;
                } else {
                    $page = new AboutFaqPage;
                }
                $page->faq_categories = $faq_categories;
                return $page;
            case 'idea':
            case 'theidea':
            default:
                return new AboutTheideaPage();
        }
    }

    public function feedbackCallback($args, $action, $mem_redirect, $mem_resend)
    {
        if (isset($args->post))
        {
            $request = $args->request;
            $model = new FeedbackModel;
            $mem_redirect->post = $args->post;
            if (!$model->getLoggedInMember() && !filter_var($args->post['FeedbackEmail'], FILTER_VALIDATE_EMAIL))
            {
                $mem_redirect->errors = array('FeedbackErrorBadEmail');
                return false;
            }
            if (isset($args->post['IdCategory']) && $args->post['FeedbackQuestion'] != '')
            {
                if ($model->sendFeedback($args->post))
                {
                    // Redirect if "redirect" GET parameter was set when first
                    // calling the feedback form.
                    if (isset($args->post['redirect'])
                        && !empty($args->post['redirect'])) {
                        return $args->post['redirect'];
                    } else {
                        return 'feedback/submit';
                    }
                }
                else
                {
                    $mem_redirect->errors = array('FeedbackErrorSendfailed');
                    return false;
                }
            }
            else
            {
                $mem_redirect->errors = array('FeedbackErrorDataMissing');
                return false;
            }
        }
        else
        {
            return false;
        }
    }

}
