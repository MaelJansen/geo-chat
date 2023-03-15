<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;
use App\Services\AddressAPIService;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Message;
use FOS\RestBundle\Controller\Annotations\View;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    #[Route('/endpointUn', name: 'app_endpoint_un', methods: ['GET'])]
    public function endPointUn(Request $request, MessageRepository $messageRepository, AddressAPIService $addressApiService){
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message
            ->setLongitude($addressApiService->getlngLat($message->getAddress())[0])
            ->setLatitude($addressApiService->getlngLat($message->getAddress())[1]);
            $messageRepository->save($message, true);

            return $this->json($message);
        }
        return $message;
    }

    /**
     * @swagger
    * components:
    *   schemas:
    *     Address:
    *       type: object
    *       properties:
    *         name:
    *           type: string
    *           description: Adress
    *           example: LBordeaux
     */
    #[View(serializerGroups: ["message_basic"])]
    #[Route('/messages', name: 'app_endpoint_message', methods: ['GET'])]
    public function endPointMessage(MessageRepository $messageRepository, Request $request){
        
        $address = $request->query->get('address');
        $radius = $request->query->get('radius', 2);

        $addressApiService = new AddressAPIService();
        $longLat = $addressApiService->getlngLat($address);

        $data = $messageRepository->findClose($longLat[0], $longLat[1], $radius*1000)->getQuery()->getResult();

        return [
            "messages" => $data,
        ];
    }
}
