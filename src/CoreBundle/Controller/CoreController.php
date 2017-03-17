<?php
namespace CoreBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
class CoreController extends Controller
{
  // La page d'accueil
  public function indexAction()
  {
    // On retourne simplement la vue de la page d'accueil
    // L'affichage des 3 dernières annonces utilisera le contrôleur déjà existant dans PlatformBundle
    return $this->render('CoreBundle:Core:index.html.twig');
    // La méthode longue $this->get('templating')->renderResponse('...') est parfaitement valable
  }
}
