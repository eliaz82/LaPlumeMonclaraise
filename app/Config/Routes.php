<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

service('auth')->routes($routes);

$routes->get('/', 'Home::index', ['as' => 'accueil']);

// ------------------------------------------- contact -------------------------------------------
$routes->get('contact', 'Association::contact', ['as' => 'contact']);
$routes->post('contact-submit', 'Association::contactSubmit', ['as' => 'contactSubmit']);

$routes->post('contact-update', 'Association::contactUpdate', ['as' => 'contactUpdate']);

// ------------------------------------------- fichierInscription -------------------------------------------
$routes->get('fichier-inscription', 'Association::fichierInscription', ['as' => 'fichierInscription']);
$routes->post('fichierInscription-submit', 'Association::fichierInscriptionSubmit', ['as' => 'fichierInscriptionSubmit']);
$routes->get('download/(:any)', 'Association::downloadFichier/$1');

$routes->get('getFichierInscriptionEtat', 'Association::getFichierInscriptionEtat');
$routes->post('updateFichierInscriptionEtat', 'Association::updateFichierInscriptionEtat');


// ------------------------------------------- FusionAssociation -------------------------------------------
$routes->get('association', 'FusionAssociation::association', ['as' => 'FusionAssociation']);
// ------------------------------------------- histoire -------------------------------------------
$routes->get('histoire', 'Association::histoire', ['as' => 'histoire']);

// ------------------------------------------- partenaires -------------------------------------------
// Dans votre fichier de routage
$routes->post('partenaires-submit', 'FusionAssociation::partenairesSubmit', ['as' => 'partenairesSubmit']);
$routes->post('partenaires-update', 'FusionAssociation::partenairesUpdate', ['as' => 'partenairesUpdate']);
$routes->post('partenaires-delete', 'FusionAssociation::partenairesDelete', ['as' => 'partenairesDelete']);

// ------------------------------------------- equipe -------------------------------------------
$routes->post('equipe-submit', 'FusionAssociation::equipeSubmit', ['as' => 'equipeSubmit']);
$routes->post('equipe-update', 'FusionAssociation::equipeUpdate', ['as' => 'equipeUpdate']);
$routes->post('equipe-delete', 'FusionAssociation::equipeDelete', ['as' => 'equipeDelete']);

// ------------------------------------------- albums -------------------------------------------
$routes->get('albums-photo', 'AlbumsPhoto::AlbumsPhoto', ['as' => 'albumsPhoto']);
$routes->post('albums-photo-create', 'AlbumsPhoto::createAlbumsPhoto', ['as' => 'createAlbumsPhoto']);
$routes->post('albums-photo-update', 'AlbumsPhoto::updateAlbumsPhoto', ['as' => 'updateAlbumsPhoto']);
$routes->post('albums-photo-delete', 'AlbumsPhoto::AlbumsPhotoDelete', ['as' => 'albumsPhotoDelete']);

// ------------------------------------------- albumsPhotos -------------------------------------------
$routes->get('albums-photo/(:num)', 'AlbumsPhoto::photo/$1', ['as' => 'photo']);
$routes->post('albums-photo/(:num)/create', 'AlbumsPhoto::createPhoto', ['as' => 'createPhoto']);
$routes->post('albums-photo/(:num)/delete', 'AlbumsPhoto::photoDelete', ['as' => 'photoDelete']);

// ------------------------------------------- calendrier -------------------------------------------
$routes->get('calendrier', 'Calendrier::calendrier', ['as' => 'calendrier']);
// ------------------------------------------- evenement -------------------------------------------

$routes->get('evenements/(:segment)', 'Evenement::evenement/$1');
$routes->get('evenements', 'Evenement::evenement', ['as' => 'evenement']);

$routes->post('evenement-create', 'Evenement::createEvenement', ['as' => 'createEvenement']);
$routes->post('evenement-update', 'Evenement::updateEvenement', ['as' => 'updateEvenement']);
$routes->post('evenement-delete', 'Evenement::evenementDelete', ['as' => 'evenementDelete']);

// ------------------------------------------- faitsMarquants -------------------------------------------
$routes->get('faits-marquants', 'Actualite::actualite', ['as' => 'actualite']);
// ------------------------------------------- logo -------------------------------------------
$routes->post('logo-update', 'Association::logoUpdate', ['as' => 'logoUpdate']);
// ------------------------------------------- localisation -------------------------------------------
$routes->post('localisation', 'Association::localisation', ['as' => 'localisation']);
// ------------------------------------------- téléphone -------------------------------------------
$routes->post('tel', 'Association::tel', ['as' => 'tel']);


$routes->get('/favicon.ico', function () {
    return redirect()->to(base_url(getAssociationLogo()));
});

$routes->get('facebook/login', 'Facebook::login');

$routes->get('facebook/hashtags/(:segment)', 'Facebook::getHashtagsByPage/$1');
$routes->post('facebook/create', 'Facebook::create');
$routes->post('facebook/delete/(:num)', 'Facebook::delete/$1');
$routes->get('facebook/expiration', 'Facebook::getTokenExpirationDate');

$routes->get('facebook/getPosts', 'Facebook::getPosts', ['as' => 'facebook.getPosts']);
$routes->post('facebook/refresh', 'Facebook::refresh', ['as' => 'facebook.refresh']);

$routes->get('getAssociationData', 'Association::getAssociationData');

$routes->get('check-token-email', 'Facebook::check_and_send_email');
