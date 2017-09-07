<?php

namespace App\Provider;

use Silex\Api\ControllerProviderInterface;

class IndexControllerProvider implements ControllerProviderInterface {

    /**
     * {@inheritDoc}
     * @see \Silex\Api\ControllerProviderInterface::connect()
     */
    public function connect(\Silex\Application $app)
    {

        # : Créer une instance de Silex\ControllerCollection
        # : https://silex.symfony.com/api/master/Silex/ControllerCollection.html
        $controllers = $app['controllers_factory'];

            # Page d'Accueil
            $controllers
                # On associe une Route à un Controller et une Action
                ->get('/', 'App\Controller\IndexController::indexAction')
                # En option je peux donner un nom à la route, qui servira plus tard
                # pour la créations de lien : "controller_action"
                ->bind('index_index');

            # Page Categories
            $controllers
                // # On associe une Route à un Controller et une Action
                ->get('/categories', 'App\Controller\IndexController::categoriesAction')
                // # En option je peux donner un nom à la route, qui servira plus tard
                // # pour la créations de lien : "controller_action"
                ->bind('index_categories');

            $controllers
                ->post('/productTypeName', 'App\Controller\IndexController::productTypeNameAjax')
                ->bind('productTypeName_ajax');

            $controllers
                ->post('/constructeur', 'App\Controller\IndexController::constructeurAjax')
                ->bind('constructeur_ajax');


# controllers pour l'inscription
            $controllers
                ->match('/inscription', 'App\Controller\IndexController::inscriptionAction')
                ->method('GET|POST')
                ->bind('index_inscription');

# controllers pour l'inscription
            $controllers
                ->match('/inscription', 'App\Controller\IndexController::inscriptionPost')
                ->method('GET|POST')
                ->bind('index_inscription');

# controllers pour la connexion
            $controllers
            ->match('/connexion', 'App\Controller\IndexController::connexionAction')
            ->method('GET|POST')
            ->bind('index_connexion');

        # On retourne la liste des controllers (ControllerCollection)
        return $controllers;

    }
}
