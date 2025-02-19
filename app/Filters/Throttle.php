<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class Throttle implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttle = Services::throttler();

        // 3 requêtes max toutes les 30 secondes
        if ($throttle->check(md5($request->getIPAddress()), 3, 30) === false) {
            return Services::response()
                ->setStatusCode(429)
                ->setBody('Trop de requêtes. Réessayez plus tard.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après la requête
    }
}
