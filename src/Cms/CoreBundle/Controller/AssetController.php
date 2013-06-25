<?php
/**
 * User: Brian Anderson
 * Date: 6/24/13
 * Time: 3:33 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Asset;
use Cms\CoreBundle\Document\AssetHistory;

class AssetController extends Controller  {

    public function readAllAction($siteId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        $assets = $this->get('persister')->getRepo('CmsCoreBundle:Asset')->findAllBySiteId($siteId);
        return $this->render('CmsCoreBundle:Asset:index.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
            'assets' => $assets,
        ));
    }

    public function readAction($id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $asset = $this->get('persister')->getRepo('CmsCoreBundle:Asset')->find($id);
        if ( ! $asset )
        {
            throw $this->createNotFoundException('Asset with id '.$id.' not found');
        }
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($asset->getSiteId());
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        $history = $this->get('persister')->getRepo('CmsCoreBundle:AssetHistory')->findAllByParentId($id);
        $assetName = $this->get('namespace_helper')->setFullname($asset->getName())->getAssetName();
        return $this->render('CmsCoreBundle:Asset:edit.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'asset' => $asset,
            'assetName' => $assetName,
            'site' => $site,
            'history' => $history,
        ));
    }

    public function newAction($siteId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        $notices = $this->get('session')->getFlashBag()->get('notices');
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        return $this->render('CmsCoreBundle:Asset:edit.html.twig', array(
            'token' => $token,
            'site' => $site,
            'notices' => $notices,
        ));
    }

    public function saveAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $name = (string)$this->getRequest()->request->get('name');
        $ext = (string)$this->getRequest()->request->get('ext');
        $content = (string)$this->getRequest()->request->get('content');

        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found. Please try again.');
        }
        // ensure user has access to add assets to this particular site

        $asset = $id ? $this->get('persister')->getRepo('CmsCoreBundle:Asset')->find($id) : new Asset();
        if ( ! $asset )
        {
            throw $this->createNotFoundException('Asset with id '.$id.' was not found.');
        }
        $asset->setSiteId($siteId);
        if ( $name )
        {
            if ( substr_count($name, ':') >= 2 )
            {
                $name = $this->get('namespace_helper')->setFullname($name)->getAssetName();
            }
            $asset->setName($site->getNamespace().':Master:'.ucfirst($name));
        }
        if ( $ext )
        {
            $asset->setExt($ext);
        }
        if ( $content )
        {
            $asset->setContent($content);
        }

        // persist to asset entity
        $success = $this->get('persister')->save($asset);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse AND ! $success )
        {
            return $xmlResponse;
        }
        if ( ! $success AND ! $id )
        {
            return $this->redirect($this->generateUrl('cms_core.asset_new', array('siteId' => $siteId) ));
        }
        else if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.asset_read', array('id' => $asset->getId()) ));
        }

        // persist to history
        $history = new AssetHistory();
        $history->setParentId($asset->getId());
        $history->setContent($asset->getContent());
        $this->get('persister')->save($history, false, 'saved old version to history');

        // persist to filesystem
        $this->get('asset_manager')->save($asset->getName(), $asset->getExt(), $asset->getContent());

        // return
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.asset_read', array('id' => $asset->getId() ) ));
    }

    public function deleteAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $asset = $this->get('persister')->getRepo('CmsCoreBundle:Asset')->find($id);
        if ( ! $asset )
        {
            throw $this->createNotFoundException('Asset with id '.$id.' not found');
        }
        $siteId = $asset->getSiteId();
        $success = $this->get('persister')->delete($asset);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.asset_readAll', array('siteId' => $siteId) ));
    }

}