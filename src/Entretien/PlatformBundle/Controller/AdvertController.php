<?php
// src/Entretien/PlatformBundle/Controller/AdvertController.php
namespace Entretien\PlatformBundle\Controller;
use Entretien\PlatformBundle\Entity\Advert;
use Entretien\PlatformBundle\Form\AdvertEditType;
use Entretien\PlatformBundle\Form\AdvertType;
use Entretien\PlatformBundle\Form\CommentType;
use Entretien\PlatformBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
  public function indexAction($page)
  {
    if ($page < 1) {
      return $this->redirectToRoute('core_home');
    }
    $nbPerPage = 10;
    // On récupère notre objet Paginator
    $listAdverts = $this->getDoctrine()
      ->getManager()
      ->getRepository('EntretienPlatformBundle:Advert')
      ->getAdverts($page, $nbPerPage)
    ;
    $nbPages = ceil(count($listAdverts) / $nbPerPage);
    // Si la page n'existe pas, on retourne une 404
    if ($page > $nbPages) {
      return $this->redirectToRoute('core_home');
    }
    // On donne toutes les informations nécessaires à la vue
    return $this->render('EntretienPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts,
      'nbPages'     => $nbPages,
      'page'        => $page,
    ));
  }
  
  public function viewAction(Request $request, $id, $comment, $type)
  {
    $em = $this->getDoctrine()->getManager();
    $ee = $this->getDoctrine()->getEntityManager();
    
    ////////////ADVERT/////////////////////////////////////////////////
    
    $advert = $em->getRepository('EntretienPlatformBundle:Advert')->find($id);
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    $listApplications = $em
      ->getRepository('EntretienPlatformBundle:Application')
      ->findBy(array('advert' => $advert))
    ;
    $listAdvertSkills = $em
      ->getRepository('EntretienPlatformBundle:AdvertSkill')
      ->findBy(array('advert' => $advert))
    ;
    $advert = $em->getRepository('EntretienPlatformBundle:Advert')->find($id);
    
    /////COMMENT LIST////////////////////////////////////////////////
    
    $listComment = $em->getRepository('EntretienPlatformBundle:Comment')->findBy(array('advert' => $advert));

    if ($comment != 0 && $type == 1) {
      $DelComment = $ee->getRepository('EntretienPlatformBundle:Comment')->findOneById($comment);
      $ee->remove($DelComment);
      $ee->flush();
      return $this->redirectToRoute('Entretien_platform_view', array('id'=> $id));
    }
    
    //////////COMMENT FORM////////////////////////////////////////////
    
    $newComment = new Comment();
    $newComment->setDate(new \Datetime());
    $newComment->setAdvert($advert);
    $form = $this->get('form.factory')->create(CommentType::class, $newComment);

    if ($request->isMethod('POST')) {
      $form->handleRequest($request);
      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($newComment);
        $em->flush();
        return $this->redirectToRoute('Entretien_platform_view', array('id' => $id));
      }
    }
    
    /////////////RENDER///////////////////////////////////////
    
    return $this->render('EntretienPlatformBundle:Advert:view.html.twig', array(
      'advert'           => $advert,
      'listApplications' => $listApplications,
      'listAdvertSkills' => $listAdvertSkills,
      'listComment'      => $listComment,
      'form'             => $form->createView(),
    ));
  }

  public function addAction(Request $request)
  {
    $advert = new Advert();
        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

    if ($request->isMethod('POST')) {
      $form->handleRequest($request);
      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirectToRoute('Entretien_platform_view', array('id' => $advert->getId()));
      }
    }
    return $this->render('EntretienPlatformBundle:Advert:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $advert = $em->getRepository('EntretienPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirectToRoute('Entretien_platform_view', array('id' => $advert->getId()));
    }

    return $this->render('EntretienPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView(),
    ));
  }
  
  public function deleteAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('EntretienPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create();
    $listComment = $em->getRepository('EntretienPlatformBundle:Comment')->findBy(array('advert' => $advert));

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      foreach ($listComment as $listComment) {
        $em->remove($listComment);
      }
      $em->remove($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

      return $this->redirectToRoute('Entretien_platform_home');
    }
    
    return $this->render('EntretienPlatformBundle:Advert:delete.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView(),
    ));
  }
  
  public function menuAction($limit)
  {
    $em = $this->getDoctrine()->getManager();
    $listAdverts = $em->getRepository('EntretienPlatformBundle:Advert')->findBy(
      array(),                 // Pas de critère
      array('date' => 'desc'), // On trie par date décroissante
      $limit,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );
    return $this->render('EntretienPlatformBundle:Advert:menu.html.twig', array(
      'listAdverts' => $listAdverts
    ));
  }
}
