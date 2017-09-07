<?php
namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class IndexController
{
    /**
     * Affichage de la Page d'Accueil
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function indexAction(Application $app) {

        # Déclaration d'un Message
        $message = 'Accueil';

        # Affichage dans la Vue
        return $app['twig']->render('index.html.twig',[
            'message'  => $message
        ]);
    }

    public function categoriesAction(Application $app) {

        # Déclaration de categorie
        $produits   = $app['db']->fetchAll('SELECT * FROM articles ORDER BY productTypeName DESC LIMIT 6');
        $categories = $app['db']->fetchAll('SELECT DISTINCT productTypeName FROM articles');
        $construs   = $app['db']->fetchAll("SELECT DISTINCT constructeur FROM articles");
        $modes      = $app['db']->fetchAll("SELECT DISTINCT model FROM articles");

        # Affichage dans la Vue
        return $app['twig']->render('categories.html.twig',[
            'categories'  => $categories,
            'construs'  => $construs,
            'modes'  => $modes,
            'produits'  => $produits
        ]);
    }

    public function productTypeNameAjax(Application $app, Request $request) {
      if($request->isMethod('POST')) :

        $sql = 'SELECT * FROM articles WHERE productTypeName = "'.$request->get('productTypeName').'"';

        if($request->get('constructeur') != '') {
            $sql .= ' AND constructeur = "'.$request->get('constructeur').'"';
        }

        $produits   = $app['db']->fetchAll($sql);
        return $app->json($produits);

      endif;
    }

    public function constructeurAjax(Application $app, Request $request) {
      if($request->isMethod('POST')) :

        $sql = 'SELECT * FROM articles WHERE constructeur = "'.$request->get('constructeur').'"';

        if($request->get('productTypeName') != '') {
            $sql .= ' AND productTypeName  = "'.$request->get('productTypeName').'"';
        }

        $produits   = $app['db']->fetchAll($sql);
        return $app->json($produits);

      endif;
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
   * Affichage de la Page Connexion
   * @return Symfony\Component\HttpFoundation\Response;
   */
  public function connexionAction(Application $app, Request $request) {
      return $app['twig']->render('connexion.html.twig',[
          'error'         => $app['security.last_error']($request),
          'last_username' => $app['session']->get('_security.last_username')
      ]);
  }

  /**
   * Affichage de la Page Inscription
   * @return Symfony\Component\HttpFoundation\Response;
   */
  public function inscriptionAction(Application $app) {
      return $app['twig']->render('connexion.html.twig');
  }

  /**
   * Traitement POST du Formulaire d'Inscription
   * use Symfony\Component\HttpFoundation\Request;
   */
  public function inscriptionPost(Application $app, Request $request) {

      # Vérification et Sécurisation des données POST
      # ...

      # Connexion à la Base de Données
      $auteur = $app['idiorm.db']->for_table('auteur')->create();

      # Affectation des valeurs
      $auteur->PRENOMAUTEUR       =   $request->get('PRENOMAUTEUR');
      $auteur->NOMAUTEUR          =   $request->get('NOMAUTEUR');
      $auteur->EMAILAUTEUR        =   $request->get('EMAILAUTEUR');
      $auteur->MDPAUTEUR          =   $app['security.encoder.digest']->encodePassword($request->get('MDPAUTEUR'), '');

      # On persiste en BDD
      $auteur->save();

      # On envoie un email de confirmation ou de bienvenue
      # On envoie une notification à l'administrateur
      # ...

      # On redirige l'utilisateur sur la page de connexion
      return $app->redirect('connexion?inscription=success');
  }
}
