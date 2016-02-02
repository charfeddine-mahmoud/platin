<?php

namespace VT\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use VT\ApiBundle\Entity\File;
use VT\ApiBundle\Entity\CategorieDoc;
use VT\ApiBundle\Entity\Subject;
use VT\ApiBundle\Entity\Contribution;
use VT\ApiBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;

class PhotoController extends Controller
{
    public function addFileAction(Request $request)
    {
        $files = $this->getRequest()->files;
        $em = $this->getDoctrine()->getManager();
        if(!empty($files))
        {
          $user = $this->get('security.context')->getToken()->getUser();
          $dir = $this->get('kernel')->getRootDir().'/../web/uploads/';
          foreach ($files as $f) {
              $filename = $f->getClientOriginalName();
              $extension = pathinfo($filename, PATHINFO_EXTENSION);
              $name = "file".rand(1, 99999)."platin".".".$extension;
              $file = $f->move($dir, $name);
              $pathFile = "uploads/".$name;
              $file = new File();
              $file->setName($filename);
              $file->setCreationdate(new \DateTime());
              $file->setUser($user);
              $file->setPathFile($pathFile);
              $file->setDate(new \Datetime());
              $file->setTime(new \Datetime());
              $file->setActive(0);
              $em->persist($file);
              $em->flush();
              $imageId = $file->getId();
              $arraydecode = json_encode($imageId);
              $response = new Response($arraydecode);
              $response->headers->set('Content-Type', 'application/json');
          }
        }

        return $response;
    }

    public function deleteFileAction($imageId)
    {
       $em = $this->getDoctrine()->getManager();
       $file = $em->getRepository('ApiBundle:File')->find($imageId);
       if (!empty($file)) {
          $categoriesFile = $em->getRepository('ApiBundle:CategorieDoc')->findBy(array("fileId" => $file));
          if (!empty($categoriesFile)) {
             foreach ($categoriesFile as $categoryFile) {
                $em->remove($categoryFile);
             }
          }
          $dir = $this->get('kernel')->getRootDir().'/../web/';
          unlink($dir.$file->getPathFile());
          $em->remove($file);
          $em->flush();
       }
       $response = new Response();
       $response->headers->set('Content-Type', 'application/json');

       return $response;
    }
}
