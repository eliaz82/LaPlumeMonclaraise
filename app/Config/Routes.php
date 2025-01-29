<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['as' => 'accueil']);

// ------------------------------------------- contact -------------------------------------------
$routes->get('contact', 'Association::contact', ['as' => 'contact']);
$routes->post('contact-submit', 'Association::contactSubmit', ['as' => 'contactSubmit']);

$routes->post('contact-update', 'Association::contactUpdate', ['as' => 'contactUpdate']);

// ------------------------------------------- fichierInscription -------------------------------------------
$routes->get('fichier-inscription', 'Association::fichierInscription', ['as' => 'fichierInscription']);
$routes->post('fichierInscription-submit', 'Association::fichierInscriptionSubmit', ['as' => 'fichierInscriptionSubmit']);
$routes->get('download/(:any)', 'Association::downloadFichier/$1');

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
$routes->get('albums-photo/(:any)', 'AlbumsPhoto::photo/$1', ['as' => 'photo']);
$routes->post('albums-photo/(:num)/create', 'AlbumsPhoto::createPhoto', ['as' => 'createPhoto']);
$routes->post('albums-photo/(:num)/delete', 'AlbumsPhoto::photoDelete', ['as' => 'photoDelete']);

// ------------------------------------------- calendrier -------------------------------------------
$routes->get('calendrier', 'Calendrier::calendrier', ['as' => 'calendrier']);
$routes->get('calendrier/evenement/(:num)', 'Calendrier::evenement', ['as' => 'evenements']);

// ------------------------------------------- evenement -------------------------------------------
$routes->get('evenement', 'Evenement::evenement', ['as' => 'evenement']);

// ------------------------------------------- faitMarquant -------------------------------------------
$routes->get('fais-marquant', 'Actualite::actualite', ['as' => 'actualite']);
$routes->get('fais-marquant/(:num)', 'Actualite::actualite/$1', ['as' => 'actualiteClick']);
// ------------------------------------------- logo -------------------------------------------
$routes->post('logo-update', 'Association::logoUpdate', ['as' => 'logoUpdate']);

$routes->get('/favicon.ico', function () {
    return redirect()->to(base_url(getAssociationLogo()));
});

$routes->get('/login', 'Facebook::login');
$routes->get('/posts', 'Facebook::getPosts');
$routes->get('send-email', 'Facebook::sendEmail');

$routes->get('/facebook/(:segment)', 'Facebook::showView/$1');
