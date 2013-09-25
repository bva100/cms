<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 2:28 PM
 */

namespace Cms\CoreBundle\Controller;

use Cms\CoreBundle\Document\Acl;
use Cms\CoreBundle\Document\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Site;
use Cms\CoreBundle\Document\Group;

class SiteController extends Controller {

    public function newAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        return $this->render('CmsCoreBundle:Site:new.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'domain' => (string)$this->getRequest()->query->get('domain'),
            'name' => (string)$this->getRequest()->query->get('name'),
        ));
    }

    public function saveAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $name = (string)$this->getRequest()->request->get('name');
        $domain = (string)$this->getRequest()->request->get('domain');
        $site = $id ? $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($id) : new Site();
        if ( ! $site ){
            return $this->createNotFoundException('Site not found');
        }
        if ( ! $id ){
            $user = $this->get('security.context')->getToken()->getUser();
            $usersGroup = new Group();
            $usersGroup->setName('users')->addUserId($user->getId());
            $site->addGroup($usersGroup);
            $supersGroup= new Group();
            $supersGroup->setName('supers')->addUserId($user->getId());
            $site->addGroup($supersGroup);
        }
        if ( $name ){
            $site->setName($name);
            $site->setNamespace(str_replace(' ', '', $name));
        }
        if ( $domain ){
            $unique = $this->get('site_manager_unique')->domainCheck($domain);
            if ( ! $unique ){
                throw new \InvalidArgumentException('The domain name '.$domain.' has already been registered with PipeStack. If this is a mistake please contact customer support by emailing support@pipestack.com.');
            }
            $site->addDomain($domain);
        }
        $success = $this->get('persister')->save($site);
        if ( ! $success AND ! $id )
        {
            return $this->redirect($this->generateUrl('cms_core.site_new', array('domain' => $domain, 'name' => $name)));
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.site_new'));
        }
        return $this->redirect($this->generateUrl('cms_core.site_read', array('id' => $site->getId())));
    }

    public function settingsAction($id)
    {
        $site = $this->getSite($id);
        $contentTypes = $site->getContentTypes();
        return $this->render('CmsCoreBundle:Site:settings.html.twig', array(
            'site' => $site,
            'contentTypes' => $contentTypes,
        ));
    }

    public function userGroupsReadAllAction($siteId)
    {
        $site = $this->getSite($siteId);
        $groups = $site->getGroups();
        return $this->render('CmsCoreBundle:Site:userGroups.html.twig', array(
            'site' => $site,
            'groups' => $groups,
        ));
    }

    public function userGroupsReadAction($siteId, $groupId)
    {
        $site = $this->getSite($siteId);
        $group = $site->getGroup($groupId);
        if ( ! $group ){
            throw $this->createNotFoundException('Group with id '.$groupId.' for site with id '.$siteId.' not found');
        }
        $users = $this->get('persister')->getRepo('CmsUserBundle:User')->findByIds($group->getUserIds());
        return $this->render('CmsCoreBundle:Site:userGroup.html.twig', array(
            'site' => $site,
            'group' => $group,
            'users' => $users,
        ));
    }

    public function userGroupsAddUserAction($siteId, $groupId)
    {
        $site = $this->getSite($siteId);
        $group = $site->getGroup($groupId);
        if ( ! $group ){
            throw $this->createNotFoundException('Group with id '.$groupId.' for site with id '.$siteId.' not found');
        }
        return $this->render('CmsCoreBundle:Site:userGroupsAddUser.html.twig', array(
            'site' => $site,
            'group' => $group,
//            'notices' => array((string)$this->getRequest()->query->get('notices')),
            'notices' => $this->get('session')->getFlashBag()->get('notices'),
            'email' => (string)$this->getRequest()->query->get('email'),
            'token' => $this->get('csrfToken')->createToken()->getToken(),
        ));
    }

    public function userGroupsAddUserProcessAction($siteId, $groupId)
    {
        $email = $this->getRequest()->request->get('email');
        $site = $this->getSite($siteId);
        $group = $site->getGroup($groupId);
        if ( ! $group ){
            throw $this->createNotFoundException('Group with id '.$groupId.' for site with id '.$siteId.' not found');
        }
        $user = $this->get('persister')->getRepo('CmsUserBundle:User')->findByEmail($email);
        if ( count($user) < 1 ){
            $this->get('session')->getFlashBag()->set('notices', 'User with email '.$email.' not found');
            return $this->redirect($this->generateUrl('cms_core.site_userGroup_addUser', array('siteId' => $siteId, 'groupId' => $groupId, 'email' => $email, 'notices' => 'User with email '.$email.' not found')));
        }
        echo '<pre>', \var_dump('here'); die();
        
    }
    
    

    public function readAction($id)
    {
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($id);


        $contentTypes = $site->getContentTypes();
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteId($id);
        return $this->render('CmsCoreBundle:Site:index.html.twig', array(
            'site' => $site,
            'contentTypes' => $contentTypes,
            'nodes' => $nodes,
        ));
    }

    public function deleteAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($id);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        // ensure use has proper permission to delete this site
        $success = $this->get('persister')->delete($site);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.app_index'));
    }

    public function addThemeAction()
    {
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $themeOrgId = (string)$this->getRequest()->request->get('themeOrgId');
        $themeId = (string)$this->getRequest()->request->get('themeId');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($themeOrgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('theme organization with id '.$themeOrgId.' not found');
        }
        // validate user access to themeOrg
        $theme = $themeOrg->getTheme($themeId);
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$themeId.' not found');
        }
        // validate user access to theme

        $helper = $this->get('theme_template')->setSite($site)->setThemeOrg($themeOrg)->setTheme($theme)->setPersister($this->get('persister'));
        $nameAffix = $helper->getTemplateNameAffix();
        $site->addTheme(array('id' => $themeId,'orgId' => $themeOrgId,'name' => $theme->getName(),'image' => $theme->getImage('featured')));
        $site->addTemplateName($nameAffix.'Components');
        foreach ($theme->getLayouts() as $templateName){
            $site->addTemplateName($nameAffix.$templateName);
            $helper->saveTemplate($helper->createChildLayoutTemplate($templateName));
        }
        $helper->saveTemplate($helper->createChildComponentsTemplate());
        $success = $this->get('persister')->save($site);
        return $this->get('xmlResponse')->execute($this->getRequest(), $success);
    }

    public function selectThemeAction()
    {
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $themeOrgId = (string)$this->getRequest()->request->get('themeOrgId');
        $themeId = (string)$this->getRequest()->request->get('themeId');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($themeOrgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('theme organization with id '.$themeOrgId.' not found');
        }
        // validate user access to themeOrg
        $theme = $themeOrg->getTheme($themeId);
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$themeId.' not found');
        }
        // validate user and site has access to theme

        $helper = $this->get('theme_template')->setSite($site)->setThemeOrg($themeOrg)->setTheme($theme)->setPersister($this->get('persister'));
        $results = $helper->saveMasterTemplates();
        if ( ! $results )
        {
            $response = new Response('failed');
            $response->setStatusCode(500);
            return $response;
        }
        $site->setCurrentTheme($themeOrgId, $themeId);
        return $this->get('xmlResponse')->execute($this->getRequest(), $this->get('persister')->save($site));
    }

    public function getSite($id)
    {
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($id);
        if ( ! $site ){
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        return $site;
    }
}