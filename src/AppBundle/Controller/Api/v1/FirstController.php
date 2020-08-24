<?php

namespace AppBundle\Controller\Api\v1;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\ftask;

class FirstController extends Controller
{
    /**
     * @Route("/api/v1/first")
     * @Method("POST")
     */
    public function firstAction(Request $request)
    {
        $nameQuery = $request->query->get('name');
        $colorQuery = $request->query->get('color');
        $sizeQuery = $request->query->get('size');

       // Create a new empty object
        $ftask = new ftask();

        // Use methods from the ftask entity to set the values
        $ftask->setName($nameQuery);
        $ftask->setColor($colorQuery);
        $ftask->setSize($sizeQuery);

        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();

        // Add our ftask to Doctrine so that it can be saved
        $em->persist($ftask);

        // Save our ftask
        $em->flush();

        return new Response('It\'s probably been saved', 201);
      // return new Response("Your first task was saved");


    }


   /**
      * @Route("/api/v1/ftask")
      * @Method("GET")
     */
    public function gettAction()
    {
      $ftask = $this->getDoctrine()
                    ->getRepository('AppBundle:ftask')
                    ->findAll();

      $ftask = $this->get('jms_serializer')->serialize($ftask,'json');

        if ($ftask === null) {
          return new View("there are no data", Response::HTTP_NOT_FOUND);
     }
    
        return new Response($ftask);
    
    }


         /**
         * @Route("/api/v1/ftask/{id}")
         * @Method("GET")
         * @param $id
         */
        public function getAction($id) {

             $ftask = $this->getDoctrine()
            ->getRepository('AppBundle:ftask')
            ->findOneBy(['id' => $id]);

            $data = [
                'name' =>  $ftask->getName(),
                'color' => $ftask->getColor(),
                'size' => $ftask->getSize(),

              ];
              return new Response(json_encode($data));

        }
}
