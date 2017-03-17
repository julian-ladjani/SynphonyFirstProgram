<?php
// src/Entretien/PlatformBundle/Controller/AdvertController.php
namespace Entretien\PlatformBundle\Controller;
use Entretien\PlatformBundle\Entity\Advert;
use Entretien\PlatformBundle\Entity\Application;
use Entretien\PlatformBundle\Form\AdvertEditType;
use Entretien\PlatformBundle\Form\AdvertType;
use Entretien\PlatformBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
    $advert = $em->getRepository('EntretienPlatformBundle:Advert')->find($id);
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    // Récupération de la liste des candidatures de l'annonce
    $listApplications = $em
      ->getRepository('EntretienPlatformBundle:Application')
      ->findBy(array('advert' => $advert))
    ;
    // Récupération des AdvertSkill de l'annonce
    $listAdvertSkills = $em
      ->getRepository('EntretienPlatformBundle:AdvertSkill')
      ->findBy(array('advert' => $advert))
    ;
    $listComment = $em->getRepository('EntretienPlatformBundle:Comment')->findBy(array('advert' => $advert));

    if ($comment != 0 && $type == 1) {
      $DelComment = $ee->getRepository('EntretienPlatformBundle:Comment')->findOneById($comment);
      $ee->remove($DelComment);
      $ee->flush();
      return $this->redirectToRoute('Entretien_platform_view', array('id'=> $id));
    }
    $advert = $em->getRepository('EntretienPlatformBundle:Advert')->find($id);
    
    $newComment = new Comment();
    $newComment->setDate(new \Datetime());
    $newComment->setAdvert($advert);
    $form = $this->get('form.factory')->createBuilder(FormType::class, $newComment)
      ->add('content',   TextareaType::class)
      ->add('author',    TextType::class)
      ->add('Envoyer',      SubmitType::class)
      ->getForm()
    ;
    if ($request->isMethod('POST')) {
      $form->handleRequest($request);
      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($newComment);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirectToRoute('Entretien_platform_view', array('id' => $id));
      }
    }
    
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
    // On crée un objet Advert
    $advert = new Advert();

    // J'ai raccourci cette partie, car c'est plus rapide à écrire !
        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

    // Si la requête est en POST
    if ($request->isMethod('POST')) {
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {
        // On enregistre notre objet $advert dans la base de données, par exemple
        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('Entretien_platform_view', array('id' => $advert->getId()));
      }
    }

    // À ce stade, le formulaire n'est pas valide car :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
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

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
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
