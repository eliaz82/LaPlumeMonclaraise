<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//-------------------------------------------contact-------------------------------------------
$routes->get('contact', 'Association::contact', ['as' => 'contact']);
$routes->post('contact-submit', 'Association::contactSubmit', ['as' => 'contactSubmit']);
$routes->post('mail-submit', 'Association::mailSubmit', ['as' => 'mailSubmit']);

//-------------------------------------------fichierInscription-------------------------------------------
$routes->get('fichier-inscription', 'Association::fichierInscription', ['as' => 'fichierInscription']);
$routes->post('fichierInscription-submit', 'Association::fichierInscriptionSubmit', ['as' => 'fichierInscriptionSubmit']);

//-------------------------------------------histoire-------------------------------------------
$routes->get('histoire', 'Association::histoire', ['as' => 'histoire']);

//-------------------------------------------partenaires-------------------------------------------
$routes->get('partenaires', 'Partenaires::partenaires', ['as' => 'partenaires']);
$routes->post('partenaires-submit', 'Partenaires::partenairesSumbit', ['as' => 'partenairesSubmit']);
$routes->post('partenaires-update', 'Partenaires::partenairesUpdate', ['as' => 'partenairesUpdate']);
$routes->post('partenaires-delete', 'Partenaires::partenairesDelete', ['as' => 'partenairesDelete']);

//-------------------------------------------equipe-------------------------------------------
$routes->get('equipe', 'Adherants::equipe', ['as' => 'equipe']);
$routes->post('equipe-submit', 'Adherants::equipeSumbit', ['as' => 'equipeSubmit']);
$routes->post('equipe-update', 'Adherants::equipeUpdate', ['as' => 'equipeUpdate']);
$routes->post('equipe-delete', 'Adherants::equipeDelete', ['as' => 'equipeDelete']);

//-------------------------------------------albums-------------------------------------------
$routes->get('albums-photo', 'AlbumsPhoto::AlbumsPhoto', ['as' => 'albumsPhoto']);
$routes->post('albums-photo-create', 'AlbumsPhoto::createAlbumsPhoto', ['as' => 'createAlbumsPhoto']);
$routes->post('albums-photo-delete', 'Adherants::AlbumsPhotoDelete', ['as' => 'albumsPhotoDelete']);

//-------------------------------------------albumsPhotos-------------------------------------------
$routes->get('albums-photo/(:num)', 'AlbumsPhoto::photo/$1', ['as' => 'photo']);
$routes->post('albums-photo/(:num)/create', 'AlbumsPhoto::createPhoto', ['as' => 'createPhoto']);
$routes->post('albums-photo/(:num)/delete', 'AlbumsPhoto::photoDelete', ['as' => 'photoDelete']);

//-------------------------------------------calendrier-------------------------------------------
$routes->get('calendrier', 'calendrier::calendrier', ['as' => 'calendrier']);
$routes->get('calendrier/evenement/(:num)', 'Calendrier::evenement', ['as' => 'evenement']);

//-------------------------------------------evenement-------------------------------------------
$routes->get('evenement', 'Evenement::evenement', ['as' => 'evenement']);

//-------------------------------------------faitMarquant-------------------------------------------
$routes->get('fais-marquand', 'Actualite::actualite', ['as' => 'actualite']);
$routes->get('fais-marquand/(:num)', 'Actualite::actualite/$1', ['as' => 'actualiteClick']);