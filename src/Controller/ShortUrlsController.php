<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\Security\CheckTokenController;
 

class ShortUrlsController extends AbstractController
{
    /**@var CheckTokenController $checkTokenController*/
    protected $checkTokenController; 

    public function __construct(CheckTokenController $checkTokenController)
    {
        $this->checkTokenController = $checkTokenController;
    }

    //Cambiar nombre al terminar por createUrl
    public function index(Request $request)
    {
        try{
        
        $token = $this->checkTokenController->checktoken($request);
        //dd($token['status']);
        if($token['status'] == 'KO'){
            
            return $this->json( 
                    [
                        'status' => 'KO',
                        'message' => 'El token no es valido'
                    ]
                    ); 
        }
        // Obtener la URL enviada en el cuerpo de la petición
        $url = $request->getContent();
        $url = json_decode($url, true);
        $url = $url['url'];    

        // Validar que la URL no esté vacía
        if (empty($url)) {
            return $this->json(
                ['status' => 'KO',
                'error' => 'La URL es requerida'],
                 400);
            }

        // Generar la URL corta utilizando la API de TinyURL
        $tinyUrl = file_get_contents('https://tinyurl.com/api-create.php?url=' . urlencode($url));
        
            return new JsonResponse(
                [
                    'status' => 'OK',

                    'newUrl' => $tinyUrl
                ],
                Response::HTTP_OK,
                );
        }catch(Exception $e){
  
            return new JsonResponse(
            [
                'status' => 'KO',
                'message' => 'Ha ocurrido un error', 
            ],
            Response::HTTP_OK,
            );
        }

        
    }
}
