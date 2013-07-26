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
        $siteId = (string)$this->getRequest()->request->get('siteId');
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

    public function addAction()
    {
        $id = (string)$this->getRequest()->request->get('id');
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        // ensure user has permissions to add media to this site
        $user = $this->get('security.context')->getToken()->getUser();
        $nodeId = (string)$this->getRequest()->request->get('nodeId');
        if ( $nodeId )
        {
            $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($nodeId);
            if ( ! $node )
            {
                throw $this->createNotFoundException('Node with id '.$nodeId.' not found');
            }
            // ensure user has permissions to add media to this node
        }
        $filename = (string)$this->getRequest()->request->get('filename');
        $storage = (string)$this->getRequest()->request->get('storage');
        $url = (string)$this->getRequest()->request->get('url');
        $mime = (string)$this->getRequest()->request->get('mime');
        $size = (int)$this->getRequest()->request->get('size');
        $media = $id ? $this->get('persister')->getRepo('CmsCoreBundle:Media')->find($id) : new Media();
        if ( ! $media )
        {
            throw $this->createNotFoundException('Media with id '.$id.' not found');
        }
        if ( $siteId )
        {
            $media->setSiteId($siteId);
        }
        if ( $filename )
        {
            $media->setFilename($filename);
        }
        if ( $storage )
        {
            $media->setStorage($storage);
        }
        if ( $url )
        {
            $media->setUrl($url);
        }
        if ( $mime )
        {
            $media->setMime($mime);
        }
        if ( $size )
        {
            $media->setSize($size);
        }
        if ( $nodeId )
        {
            $media->addNodeId($nodeId);
        }
        $success = $this->get('persister')->save($media, false, false);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.site_read', array('siteId' => $siteId)));
    }

    public function readAction($siteId, $id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        $media = $this->get('persister')->getRepo('CmsCoreBundle:Media')->find($id);
        if ( ! $media )
        {
            throw $this->createNotFoundException('Media with id '.$id.' not found');
        }
        $dimensions = $media->getMetadata('dimensions');
        return $this->render('CmsCoreBundle:Media:edit.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
            'media' => $media,
        ));
    }

    public function updateAction()
    {
        $id = (string)$this->getRequest()->request->get('id');
        $filename = (string)$this->getRequest()->request->get('filename');
        $metadata = json_decode((string)$this->getRequest()->request->get('metadata'));
        $media = $this->get('persister')->getRepo('CmsCoreBundle:Media')->find($id);
        if ( ! $media )
        {
            throw $this->createNotFoundException();
        }
        if ( $filename )
        {
            $media->setFilename($filename);
        }
        if ( $metadata )
        {
            $media->setMetadata($metadata);
        }
        $success = $this->get('persister')->save($media, false, false);
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
        $format = (string)$this->getRequest()->query->get('format');
        $search = $this->getRequest()->query->get('search');
        $startDate = $this->getRequest()->query->get('startDate');
        $endDate = $this->getRequest()->query->get('endDate');
        $association = $this->getRequest()->query->get('association');
        $type = $this->getRequest()->query->get('type');
        $sortBy = (string)$this->getRequest()->query->get('sortBy');
        if ( ! $sortBy )
        {
            $sortBy = 'created';
        }
        $sortOrder = (string)$this->getRequest()->query->get('sortOrder');
        if ( $sortOrder )
        {
            $sortOrder = 'desc';
        }
        $page = $this->getRequest()->query->get('page');
        if ( ! $page )
        {
            $page = 1;
        }
        $limit = (int)$this->getRequest()->query->get('limit');
        if ( ! $limit )
        {
            $limit = 12;
        }
        $nextPage = $limit*($page-1) >= $limit ? false : true ;
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        // ensure user has access to read site
        $media = $this->get('persister')->getRepo('CmsCoreBundle:Media')->findAllBySiteIdAndType($siteId, $type, array(
            'offset' => $limit*($page-1),
            'limit' => $limit,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'association' => $association,
            'sort' => array('by' => $sortBy, 'order' => $sortOrder),]
        ));
        
        if ( $format === 'json' )
        {
            $serializer = $this->get('jms_serializer');
            $array = array();
            foreach ($media as $mediaSingle) {
                $array[] = $mediaSingle;
            }
            $response = new Response( $serializer->serialize($array, 'json') );
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        else
        {
            return $this->render('CmsCoreBundle:Media:read.html.twig', array(
                'token' => $token,
                'notices' => $notices,
                'site' => $site,
                'media' => $media,
                'search' => $search,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'page' => $page,
                'limit' => $limit,
                'nextPage' => $nextPage,
                'type' => $type,
                'association' => $association,
            ));
        }
    }

    public function deleteAction()
    {
        $id = (string)$this->getRequest()->request->get('id');
        $media = $this->get('persister')->getRepo('CmsCoreBundle:Media')->find($id);
        if ( ! $media )
        {
            throw $this->createNotFoundException('Media with id '.$id.' not found');
        }
        $this->get('media_manager')->setMedia($media)->delete();
        $success = $this->get('persister')->delete($media, false, false);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.media_readAll', array('siteId' => $media->getSiteId()) ));
    }

}