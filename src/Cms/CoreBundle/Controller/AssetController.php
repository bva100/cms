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
        $type = $this->getRequest()->query->get('type');
        $search = $this->getRequest()->query->get('search');
        $page = (int)$this->getRequest()->query->get('page');
        if ( ! $page )
        {
            $page = 1;
        }
        $limit = (int)$this->getRequest()->query->get('limit');
        if ( ! $limit )
        {
            $limit = 14;
        }
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        $assets = $this->get('persister')->getRepo('CmsCoreBundle:Asset')->findAllBySiteIdAndType($siteId, $type, array(
            'offset' => $limit*($page-1),
            'limit' => $limit,
            'sort' => array('by' => 'created', 'order' => 'descending'),
            'search' => $search,
        ));
        $nextPage = $limit*($page-1) >= $limit ? false : true;
        return $this->render('CmsCoreBundle:Asset:index.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
            'assets' => $assets,
            'type' => $type,
            'page' => $page,
            'limit' => $limit,
            'search' => $search,
            'nextPage' => $nextPage,
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
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        return $this->render('CmsCoreBundle:Asset:edit.html.twig', array(
            'token' => $token,
            'site' => $site,
            'notices' => $notices,
        ));
    }

    public function saveAction()
    {
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
            $oldContent = $asset->getContent();
            $asset->setContent($content);
        }
        // persist to asset entity
        $success = $this->get('persister')->save($asset, false, false);
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
        if ( isset($oldContent) AND $oldContent !== $content )
        {
            $history = new AssetHistory();
            $history->setParentId($asset->getId());
            $history->setContent($oldContent);
            $this->get('persister')->save($history, false, false);
        }
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
        $histories = $this->get('persister')->getRepo('CmsCoreBundle:AssetHistory')->findAllByParentId($asset->getId());
        $siteId = $asset->getSiteId();
        $this->get('asset_manager')->delete($asset->getName(), $asset->getExt());
        foreach ($histories as $history)
        {
            $this->get('persister')->delete($history, true, false);
        }
        $success = $this->get('persister')->delete($asset, false, false);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.asset_readAll', array('siteId' => $siteId) ));
    }

}