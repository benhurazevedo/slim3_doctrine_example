<?php
namespace lib\middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class EmailMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $ok = true;

        $parsedBody = $request->getParsedBody();
        
        if(!array_key_exists("email", $parsedBody))
            $ok = false;

        $entityManager = \lib\services\EntityManagerFactory::getEntityManager();

        $UsuariosRepository = $entityManager->getRepository(\lib\models\Usuario::class);

        $usuarios = $UsuariosRepository->findBy(['email' => $parsedBody['email']]);

        $entityManager->close();

        if(count($usuarios) > 0)
            $ok = false;
        
        if(!$ok)
        {
            return $response->withStatus(400);
        }
        $response = $next($request, $response);
        return $response;
    }
}