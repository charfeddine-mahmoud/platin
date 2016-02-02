<?php

namespace VT\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use VT\ApiBundle\Entity\File;
use VT\ApiBundle\Entity\CategorieDoc;
use VT\ApiBundle\Entity\Subject;
use VT\ApiBundle\Entity\Contribution;
use VT\ApiBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use \ZipArchive;

class CategorieController extends RestController
{
    public function getCategoriesPublicAction(Request $request)
    {
    	$data = [];
    	$em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('ApiBundle:Type')->find(1);
        if (!empty($type)) {
            $categories = $em->getRepository('ApiBundle:Categorie')->findBy(array("type" => $type));
            foreach ($categories as $category) {
                $data[] = array("categoryId" => $category->getId(), "categoryName" => $category->getNom());
            }
        }

		return $data;
    }

    public function getCategoriesThemeAction(Request $request)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('ApiBundle:Type')->find(2);
        if (!empty($type)) {
            $categories = $em->getRepository('ApiBundle:Categorie')->findBy(array("type" => $type));
            foreach ($categories as $category) {
                $data[] = array("categoryId" => $category->getId(), "categoryName" => $category->getNom());
            }
        }

        return $data;
    }

    public function getCategoriesNiveauAction(Request $request)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('ApiBundle:Type')->find(3);
        if (!empty($type)) {
            $categories = $em->getRepository('ApiBundle:Categorie')->findBy(array("type" => $type));
            foreach ($categories as $category) {
                $data[] = array("categoryId" => $category->getId(), "categoryName" => $category->getNom());
            }
        }

        return $data;
    }

    public function getCategoriesObjectifAction(Request $request)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('ApiBundle:Type')->find(4);
        if (!empty($type)) {
            $categories = $em->getRepository('ApiBundle:Categorie')->findBy(array("type" => $type));
            foreach ($categories as $category) {
                $data[] = array("categoryId" => $category->getId(), "categoryName" => $category->getNom());
            }
        }

        return $data;
    }

    public function getCategoriesAutreAction(Request $request)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('ApiBundle:Type')->find(6);
        if (!empty($type)) {
            $categories = $em->getRepository('ApiBundle:Categorie')->findBy(array("type" => $type));
            foreach ($categories as $category) {
                $data[] = array("categoryId" => $category->getId(), "categoryName" => $category->getNom());
            }
        }

        return $data;
    }

    public function addFileDataAction(Request $request)
    {
    	$data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if(!empty($data))
        {
        	$file = $em->getRepository('ApiBundle:File')->find($data["imageId"]);
        	$file->setName($data["filename"]);
            $file->setDate(new \Datetime());
            $file->setTime(new \Datetime());
            $file->setActive(1);
        	$em->persist($file);
            $em->flush();
            $fileId = $file->getId();
            $arraydecode = json_encode($fileId);
            $response = new Response($arraydecode);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

    }

    public function addTagAction(Request $request)
    {
    	$data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        if(!empty($data))
        {
            $file = $em->getRepository('ApiBundle:File')->find($data["docId"]);
        	if ($data["categoryId"] == 0) 
        	{
                $type = $em->getRepository('ApiBundle:Type')->find($data["typeId"]);
        		$categorie = new Categorie();
        		$categorie->setNom($data["categoryName"]);
                $categorie->setType($type);
        		$em->persist($categorie);
        		$em->flush();
        		$categoryId = $categorie->getId();
        	}
        	else
        	{
        		$categoryId = $data["categoryId"];
        	}
            $category = $em->getRepository('ApiBundle:Categorie')->find($categoryId);
        	$categorieDoc = new CategorieDoc();
        	$categorieDoc->setCategorieId($category);
        	$categorieDoc->setFileId($file);
        	$em->persist($categorieDoc);
        	$em->flush();

        	return new Response();
        }
    	
    }

    public function getSupportAction()
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository('ApiBundle:File')->findBy(array("active" => 1));
        $user = $this->get('security.context')->getToken()->getUser();
        foreach ($files as $file) {
            $categoriesFile = $em->getRepository('ApiBundle:CategorieDoc')->findBy(array("fileId" => $file));
            $categoriesPublic = [];
            $categoriesNiveau = [];
            $categoriesTheme = [];
            $categoriesObjectif = [];
            $categoriesAutre = [];
            foreach ($categoriesFile as $categoryFile) {
                switch ($categoryFile->getCategorieId()->getType()->getId()) {
                    case 1:
                        $categoriesPublic[] = $categoryFile->getCategorieId()->getNom();
                        break;
                    case 2:
                        $categoriesNiveau[] = $categoryFile->getCategorieId()->getNom();
                        break;
                    case 3:
                        $categoriesTheme[] = $categoryFile->getCategorieId()->getNom();
                        break;
                    case 5:
                        $categoriesObjectif[] = $categoryFile->getCategorieId()->getNom();
                        break;
                    case 6:
                        $categoriesAutre[] = $categoryFile->getCategorieId()->getNom();
                        break;
                }
                
            }
            $owner = $user->getId() == $file->getUser()->getId() ? true : false; 
            $data[] = array("fileId" => $file->getId(), "owner" => $owner, "name" => $file->getName(), "date" => date('d-m-Y', $file->getDate()->getTimestamp()), "time" => date('H:i:s', $file->getTime()->getTimestamp()), "user" => $file->getUser()->getUsername(), "categoriesPublic" => $categoriesPublic, "categoriesNiveau" => $categoriesNiveau, "categoriesTheme" => $categoriesTheme, "categoriesObjectif" => $categoriesObjectif, "categoriesAutre" => $categoriesAutre);
        }

        return $data;

    }

    public function deleteContributionAction(Contribution $contribution)
    {
        $em = $this->getDoctrine()->getManager();
        $dir = $this->get('kernel')->getRootDir().'/../web/';
        unlink($dir.$contribution->getContents());
        $em->remove($contribution);
        $em->flush();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getSubjectsAction()
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $subjects = $em->getRepository('ApiBundle:Subject')->findAll();
        $user = $this->get('security.context')->getToken()->getUser();
        foreach ($subjects as $subject) {
            $contributions = $em->getRepository('ApiBundle:Contribution')->findBy(array("subject" => $subject));
            $numberContributions = count($contributions);
            $owner = $user->getId() == $subject->getUser()->getId() ? true : false; 
            $data[] = array("owner" => $owner, "messages" => $numberContributions, "subject" => $subject->getSujet(), "description" => $subject->getConsigne(), "subjectId" => $subject->getId(), "date" => date('d-m-Y', $subject->getDate()->getTimestamp()), "user" => $subject->getUser()->getUsername());
        }
        $arraydecode = json_encode($data);
        $response = new Response($arraydecode);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    public function getContributionsAction(Subject $subject)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $contributions = $em->getRepository('ApiBundle:Contribution')->findBy(array("subject" => $subject));
        $user = $this->get('security.context')->getToken()->getUser();
        $dir = $this->get('kernel')->getRootDir().'/../web/';
        foreach ($contributions as $contribution) {
            $owner = $user->getId() == $contribution->getUser()->getId() ? true : false;
            $video = $contribution->getContents();
            $infos = $contribution->getInfos();
            $data[] = array("infos" => $infos,"contributionId" => $contribution->getId(), "video" => $video, "owner" => $owner, "date" => date('d-m-Y', $contribution->getDate()->getTimestamp()), "time" => date('H:i:s', $contribution->getTime()->getTimestamp()), "user" => $contribution->getUser()->getUsername());
        }
        $arraydecode = json_encode($data);
        $response = new Response($arraydecode);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function addSubjectDataAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        if(!empty($data))
        {
            $user = $this->get('security.context')->getToken()->getUser();
            $subject = new Subject();
            $subject->setSujet($data["title"]);
            $subject->setConsigne($data["description"]);
            $subject->setDate(new \Datetime());
            $subject->setUser($user);
            $em->persist($subject);
            $em->flush();
            $result = array("type" => "success", "message" => "le nouveau sujet a été ajouté avec succès");
        }
        else
        {
            $result = array("type" => "danger", "message" => "Une erreur est survenue lors de l'ajout");
        }
        $arraydecode = json_encode($result);
        $response = new Response($arraydecode);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getSubjectDataAction(Subject $subject)
    {
        $em = $this->getDoctrine()->getManager();
        $contributions = $em->getRepository('ApiBundle:Contribution')->findBy(array("subject" => $subject));
        $numberContributions = count($contributions);
        $contributionText = ($numberContributions > 1) ? "contributions" : "contribution";
        $data = array("name" => $subject->getSujet(), "subjectId" => $subject->getId(), "messages" => $numberContributions, "text" => $contributionText);
        $arraydecode = json_encode($data);
        $response = new Response($arraydecode);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    public function recoverydownloadAction(File $file)
    {
        $nom = $file->getPathFile();
        // commencer compression
        $dirZip = $this->get('kernel')->getRootDir().'/../web/'.$nom;
        // echo $dirZip;
        // exit();
        $this->Zip($dirZip, './doc.zip');
        // proposer telechargement
        header('Content-Transfer-Encoding: binary'); //Transfert en binaire (fichier).
        header('Content-Disposition: attachment; filename="doc.zip"'); //Nom du fichier.
        header('Content-Length: '.filesize('doc.zip')); //Taille du fichier.
        readfile('doc.zip');
        // fin telechargement
        // fin compression
        // suppression zip
        $fileZipToDelete = $this->get('kernel')->getRootDir().'/../web/doc.zip';
        unlink($fileZipToDelete);
        return $this->redirect($this->generateUrl('innova_platin_page_recuperer_document'));
    }

    function Zip($source, $destination)
    {
      if (is_string($source)) $source_arr = array($source); // convert it to array

      if (!extension_loaded('zip')) {
          return false;
      }

      $zip = new ZipArchive();
      if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
          return false;
      }

      foreach ($source_arr as $source)
      {
          if (!file_exists($source)) continue;
          $source = str_replace('\\', '/', realpath($source));

          if (is_dir($source) === true)
          {
              $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

              foreach ($files as $file)
              {
                  $file = str_replace('\\', '/', realpath($file));

                  if (is_dir($file) === true)
                  {
                      $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                  }
                  else if (is_file($file) === true)
                  {
                      $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                  }
              }
          }
          else if (is_file($source) === true)
          {
              $zip->addFromString(basename($source), file_get_contents($source));
          }

      }

      return $zip->close();

    }
}
