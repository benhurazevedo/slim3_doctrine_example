<?php 
namespace lib\controllers;

/*
    autor: benhur (benhur.azevedo@hotmail.com)
    utilidade: controller que fornece acesso ao 
    model de contato
*/

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ContatoController
{
    private $entityManager;
    private $contatoRepository;
    public function __construct() 
    {
        $this->entityManager = \lib\services\EntityManagerFactory::getEntityManager();
        $this->contatoRepository = $this->entityManager->getRepository(\lib\models\Contato::class);
    }
    function __destruct()
    {
        $this->entityManager->close();
    }
    public function criarContato(Request $request, Response $response) : Response
    {
        $parsedBody = $request->getParsedBody();

        $contato = new \lib\models\Contato();
        $contato->setNome($parsedBody["nome"]);
        $contato->setTelefone($parsedBody["telefone"]);

        $id = $request->getAttribute("id_usuario");
        $usuario = $this->entityManager->find(\lib\models\Usuario::class, $id);
        $contato->setUsuario($usuario);

        $this->entityManager->persist($contato);
        $this->entityManager->flush();
        return $response->withStatus(201);
    }
    public function atualizarContato(Request $request, Response $response, array $args) : Response
    {
        $parsedBody = $request->getParsedBody();

        $contato = $this->entityManager->find(\lib\models\Contato::class, $args['id']);
        if(!$contato)
        {
            return $response->withStatus(404);
        }
        
        $usuarioId = $request->getAttribute("id_usuario");
        if($contato->getUsuario()->getId() != $usuarioId)
            return $response->withStatus(403);
        
        $contato->setNome($parsedBody["nome"]);
        $contato->setTelefone($parsedBody["telefone"]);
        $this->entityManager->merge($contato);
        $this->entityManager->flush();
        return $response->withStatus(200);
    }
    public function apagarContato(Request $request, Response $response, array $args) : Response
    {
        $contato = $this->entityManager->find(\lib\models\Contato::class, $args['id']);
        if(!$contato)
        {
            return $response->withStatus(404);
        }
        
        $usuarioId = $request->getAttribute("id_usuario");
        if($contato->getUsuario()->getId() != $usuarioId)
            return $response->withStatus(403);
        
        $this->entityManager->remove($contato);
        $this->entityManager->flush();
        return $response->withStatus(204);
    }
    public function getContato(Request $request, Response $response, array $args) : Response
    {
        $contato = $this->entityManager->find(\lib\models\Contato::class, $args['id']);
        if(!$contato)
        {
            return $response->withStatus(404);
        }
        
        $usuarioId = $request->getAttribute("id_usuario");
        if($contato->getUsuario()->getId() != $usuarioId)
            return $response->withStatus(403);
        
        $response->getBody()->write(json_encode($contato->toArray(), JSON_NUMERIC_CHECK));
        $response = $response->withHeader('content-type','application/json');
        return $response->withStatus(200);
    }
    public function listContatos(Request $request, Response $response, array $args) : Response
    {
        $usuario = $this->entityManager->find(\lib\models\Usuario::class, $request->getAttribute("id_usuario"));
        $contatos = $usuario->getContatos();

        $contatos = $contatos->toArray();
        $contatos = array_map(function($contato) { return $contato->toArray(); }, $contatos);

        $response->getBody()->write(json_encode($contatos, JSON_NUMERIC_CHECK));
        $response = $response->withHeader('content-type','application/json');
        return $response->withStatus(200);
    }
}