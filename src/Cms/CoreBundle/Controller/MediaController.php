<?php
/**
 * User: Brian Anderson
 * Date: 6/25/13
 * Time: 6:34 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Media;

use Aws\S3\S3Client;

class MediaController extends Controller {

    public function newAction($siteId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        return $this->render('CmsCoreBundle:Media:new.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
        ));
    }

    public function createAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $siteId = $this->getRequest()->request->get('siteId');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        $user = $this->get('security.context')->getToken()->getUser();
        // ensure user has permissions to add media to this site

        if ( ! isset($_FILES['media']) )
        {
            throw new \InvalidArgumentException('Sorry about this... the file upload failed. Please try again.');
        }
        $mediaFile = $_FILES['media'];
        if ( $mediaFile['error'] > 0 )
        {
            throw new \InvalidArgumentException('Sorry about this... the file upload failed. Please try again.');
        }
        // persist to db
        $media = new Media();
        $media->setFile($mediaFile);
        $media->setSiteId($siteId);
        $success = $this->get('persister')->save($media);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( ! $success AND $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.media_new', array('siteId' => $siteId) ));
        }
        // persist to storage service
        $mediaResult = $this->get('media_manager')->setMedia($media)->persist();
        if ( $mediaResult )
        {
            $success = $this->get('persister')->save($mediaResult, false, NULL);
            $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
            if ( $xmlResponse )
            {
                return $xmlResponse;
            }
            if ( $success )
            {
                return $this->redirect($this->generateUrl('cms_core.media_read', array('id' => $media->getId())));
            }
        }
        $this->get('session')->getFlashBag()->set('notices', 'Media upload failed. Please try again.');
        return $this->redirect($this->generateUrl('cms_core.media_readAll', array('siteId' => $siteId)));
    }

    public function readAction($id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $media = $this->get('persister')->getRepo('CmsCoreBundle:Media')->find($id);
        if ( ! $media )
        {
            throw $this->createNotFoundException('Media with id '.$id.' not found');
        }
        $dimensions = $media->getMetadata('dimensions');
        return $this->render('CmsCoreBundle:Media:edit.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'media' => $media,
        ));
    }

    public function updateAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $filename = (string)$this->getRequest()->request->get('filename');
        $media = $this->get('persister')->getRepo('CmsCoreBundle:Media')->find($id);
        if ( ! $media )
        {
            throw $this->createNotFoundException();
        }
        if ( $filename )
        {
            $media->setFilename($filename);
        }
        $success = $this->get('persister')->save($media);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.media_read', array('id' => $id) ));
    }

    public function readAllAction($siteId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        // ensure user has access to read site
        $media = $this->get('persister')->getRepo('CmsCoreBundle:Media')->findAllBySiteId($siteId);
        return $this->render('CmsCoreBundle:Media:index.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
            'media' => $media,
        ));
    }

    public function deleteAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $media = $this->get('persister')->getRepo('CmsCoreBundle:Media')->find($id);
        if ( ! $media )
        {
            throw $this->createNotFoundException('Media with id '.$id.' not found');
        }
        $this->get('media_manager')->setMedia($media)->delete();
        $success = $this->get('persister')->delete($media);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.media_readAll', array('siteId' => $media->getSiteId()) ));
    }

}