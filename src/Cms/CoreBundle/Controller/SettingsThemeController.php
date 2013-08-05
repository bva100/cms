<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 2:08 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SettingsThemeController extends Controller {

    public function currentAction($siteId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->getSite($siteId);
        $currentThemeArray = $site->getCurrentTheme();
        if (isset($currentThemeArray['orgId']) AND isset($currentThemeArray['themeId']) )
        {
            $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($currentThemeArray['orgId']);
            $theme = $themeOrg->getTheme($currentThemeArray['themeId']);
        }
        if ( ! isset($theme) or ! $theme )
        {
            $theme = null;
        }
        return $this->render('CmsCoreBundle:Settings:themeCurrent.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
            'theme' => $theme,
        ));
    }

    public function repoAction($siteId)
    {
        $site = $this->getSite($siteId);
        return $this->render('CmsCoreBundle:Settings:themeRepo.html.twig', array(
            'site' => $site,
        ));
    }

    public function depotAction($siteId)
    {
        $site = $this->getSite($siteId);
        $themeOrgs = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->findAll();
        return $this->render('CmsCoreBundle:Settings:themeDepot.html.twig', array(
            'site' => $site,
            'themeOrgs' => $themeOrgs,
        ));
    }

    public function getSite($siteId)
    {
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        return $site;
    }

}