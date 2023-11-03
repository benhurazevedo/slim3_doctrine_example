<?php 
namespace lib\controllers;

/*
    autor: benhur (benhur.azevedo@hotmail.com)
    utilidade: controller que fornece acesso ao 
    model de usuario alem de oferecer acesso ao
    login do app
*/

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UsuarioController
{
    private $entityManager;
    private $usuarioRepository;
    public function __construct() 
    {
        $this->entityManager = \lib\services\EntityManagerFactory::getEntityManager();
        $this->usuarioRepository = $this->entityManager->getRepository(\lib\models\Usuario::class);
    }
    function __destruct()
    {
        $this->entityManager->close();
    }
    public function consultarEmail(Request $request, Response $response) : Response
    {
        $parsedBody = $request->getParsedBody();
        
        $usuarios = $this->usuarioRepository->findBy(["email" => $parsedBody["email"]]);
        $emailJahCadastrado = count($usuarios) > 0;
        
        $response = $response->withJson(["email_jah_cadastrado" => mb_convert_encoding($emailJahCadastrado, 'UTF-8', mb_list_encodings())]);
        return $response->withStatus(200);
    }
    public function cadastrarUsuario(Request $request, Response $response) : Response
    {
        $parsedBody = $request->getParsedBody();
        
        $usuario = new \lib\models\Usuario();

        $usuario->setNome($parsedBody["nome"]);
        $usuario->setEmail($parsedBody["email"]);
        $usuario->setHashSenha(md5($parsedBody["senha"]));

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        return $response->withStatus(201);
    }
    public function logar(Request $request, Response $response) : Response
    {
        $parsedBody = $request->getParsedBody();
        
        $usuarios = $this->usuarioRepository->findBy(["email" => $parsedBody["email"], "hashSenha" => md5($parsedBody["senha"])]);
        if(count($usuarios) == 0)
            return $response->withStatus(403);

        $id = $usuarios[0]->getId();
        
        $jwt = \lib\services\JWTService::codificar(["id"=> $id]);

        $response = $response->withJson(["jwt_hash" => mb_convert_encoding($jwt, 'UTF-8', mb_list_encodings())]);
        return $response->withStatus(200);
    }    
}