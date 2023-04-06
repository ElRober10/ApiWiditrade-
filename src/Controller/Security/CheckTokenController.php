<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CheckTokenController extends AbstractController
{
   public function checktoken(Request $req){

        $token = $req->headers->get('token');
        
        $parentheses = array (
            '{' => '}',
            '[' => ']',
            '(' => ')'
        );

        $stack = array();

        for($i = 0 ; $i < strlen($token) ; $i++){
            
            if(array_key_exists($token[$i], $parentheses)){
                $stack[] = $token[$i];
            }elseif(in_array($token[$i], $parentheses)){
                if($parentheses[end($stack)] === $token[$i]){
                     array_pop($stack);
                }               
            } 
        }

        if(empty($stack)) {
            return [
                'status' => 'OK'
            ];
        }else{
            return [
                'status' => 'KO'
            ];
        }

   }
}

