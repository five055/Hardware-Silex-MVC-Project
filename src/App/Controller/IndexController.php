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
        $produits   = $app['db']->fetchAll('SELECT * FROM articles ORDER BY productTypeName DESC LIMIT 100');
        $categories = $app['db']->fetchAll('SELECT DISTINCT productTypeName FROM articles');
        $construs   = $app['db']->fetchAll("SELECT DISTINCT constructeur FROM articles");
        $modes      = $app['db']->fetchAll("SELECT DISTINCT model FROM articles");
        // print_r($produits);
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

        $sql = 'SELECT `ean`, `constructeur`, `urlImage`, `model`, `productGroup`, `productTypeName`, `titre`, `garranty`, `prix`, `slug` FROM articles WHERE productTypeName = "'.$request->get('productTypeName').'"';

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


    public function prixAjax(Application $app, Request $request) {
      if($request->isMethod('POST')) :

        $prix = $request->get('prix');
        $exPrix = explode(':', $prix);
        $prix1 = floatval(str_replace(' ', '', $exPrix[0]));
        $prix2 = floatval(str_replace(' ', '', $exPrix[1]));

        $sql = 'SELECT `ean`, `urlImage`, `productGroup`, `productTypeName`, `titre`, `garranty`, `prix`, `slug` FROM articles WHERE prix BETWEEN  '.$prix1.' AND '.$prix2.' ORDER BY PRIX ASC' ;

        #print_r($sql);

        if($request->get('productTypeName') != '') {
            $sql .= ' AND productTypeName  = "'.$request->get('productTypeName').'"';
        }

        if($request->get('constructeur') != '') {
            $sql .= ' AND constructeur = "'.$request->get('constructeur').'"';
        }

        $produits   = $app['db']->fetchAll($sql);
        return $app->json($produits);

      endif;
    }


    public function articleAction($productTypeName, $slugarticle, $ean, Application $app, Request $request) {

          $sql = 'SELECT * FROM articles WHERE ean = "'.$ean.'"';
          $produit   = $app['db']->fetchAll($sql);

          return $app['twig']->render('article.html.twig',[
              'produit' => $produit[0]
              // 'suggestions' => $suggestions
          ]);

      }



      public function categorieAction($productTypeName, Application $app) {

          # Déclaration de categorie
          $type = strtoupper($productTypeName);
          $sql = 'SELECT * FROM articles WHERE productTypeName = "'.$type.'"';
          $produits   = $app['db']->fetchAll($sql);
          $categories = $app['db']->fetchAll('SELECT DISTINCT productTypeName FROM articles');
          $construs   = $app['db']->fetchAll("SELECT DISTINCT constructeur FROM articles");
          $modes      = $app['db']->fetchAll("SELECT DISTINCT model FROM articles");
          // print_r($produits);
          # Affichage dans la Vue
          return $app['twig']->render('categories.html.twig',[
              'categories'  => $categories,
              'construs'  => $construs,
              'modes'  => $modes,
              'produits'  => $produits
          ]);
      }


}
