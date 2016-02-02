<?php

namespace VT\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VT\ApiBundle\Entity\DocSDP;
use VT\ApiBundle\Entity\Subject;
use VT\ApiBundle\Entity\Contribution;
use VT\ApiBundle\Entity\Categorie;

class AjaxController extends Controller
{
	 /**
	 * @Route("/roomTeachers/contribution/add/video", name="innova_platin_upload", options={"expose"=true})
	 */
    public function uploadAction()
    {
		$idsujet = $_POST["idsujet"];
		$infos = ($_POST["infos"] == "empty") ? null : $_POST["infos"];
		$request = $this->container->get('request');
		$contribution = new Contribution();
		$user = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
		$subject = $em->getRepository('ApiBundle:Subject')->find($idsujet);
		$contribution->setDate(new \Datetime());
    	$contribution->setUser($user);
    	$contribution->setTime(new \Datetime());
    	$contribution->setType("video");
    	$contribution->setInfos($infos);
    	$file = "uploads/".$_POST["video-filename"];
    	$contribution->setContents($file);
    	$contribution->setSubject($subject);
    	$em = $this->getDoctrine()->getManager();
        $em->persist($contribution);
        $em->flush();
		$webPath = $this->get('kernel')->getRootDir().'/../web/uploads/';
		// $webPath = $this->container->getParameter('kernel.root_dir').'/../web/uploads/';
		foreach(array('video') as $type) {
		    if (!empty($_FILES["${type}-blob"])) { 
		    	// print_r($_FILES);
		        $fileName = $_POST["${type}-filename"];
		        // $uploadDirectory = "/Users/Mahmoud/htdocs/Symfony/web/uploads/".$fileName;
		        $uploadDirectory = $webPath.$fileName;
		        if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
		            echo(" problem moving uploaded file");
		        }
		               $response = $uploadDirectory;
		                return new Response($response); 
		    }
		}
    }
}
