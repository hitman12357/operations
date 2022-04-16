<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    /**
     * @Route("/operation", name="app_operation")
     */
    public function index(
        OperationRepository $repository,
        Request $request
    ): Response {

        try {
            $filter = [
                'from' => DateTime::createFromFormat('d.m.Y H:i:s', $request->query->get('from')),
                'to' => DateTime::createFromFormat('d.m.Y H:i:s', $request->query->get('to')),
                'local' => (string)$request->query->get('local'),
            ];

            $operations = $repository->findByDateCreateOrLocal($filter);
            return $this->json($operations, Response::HTTP_OK, ['content-type' => 'application/json']);
        } catch (Exception $exception) {
            return $this->json(
                ['status'=> false, 'error' => $exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        }
    }

    /**
     * @Route("/local", name="app_local")
     */
    public function local(
        OperationRepository $repository
    ): Response {
        try {
            $locals = $repository->getLocals();
            return $this->json($locals, Response::HTTP_OK, ['content-type' => 'application/json']);
        } catch (Exception $exception) {
            return $this->json(
                ['status'=> false, 'error' => $exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        }
    }
}
