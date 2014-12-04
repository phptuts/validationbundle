<?php

namespace NoahGlaser\ValidationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxController extends Controller
{
    public function validateAction(Request $request)
    {
      $postdata = $request->request->all();
      $formname = key($postdata);
      $form = $this->createForm($formname); 
      if($request->query->has('id'))
      {
          $classname = $form->getConfig()->getDataClass();
          $entity  = $this->getDoctrine()->getManager()->getRepository($classname)->findOneBy(array('id' => $request->query->get('id')));
          $form = $this->createForm($formname, $entity); 
      }
      
      $form->handleRequest($request);
      
      if($form->isValid())
      {
            return new JsonResponse(array('success' => true, 'hasError' => false));
      }
      else
      {
           $errors =  $this->get('formerrors')->getAllFormErrors($form);
           return new JsonResponse($errors);
      }

    }
}