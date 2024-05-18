<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UseCase\SignUp;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignUpController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/signup', name: 'auth.signup', methods: ['POST', 'GET'])]
    public function request(Request $request, SignUp\Request\Handler $handler): Response
    {
        $command = new SignUp\Request\Command();
        $form = $this->createForm(SignUp\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($form->getData());
                $this->addFlash('success', 'Check your email.');

                return $this->redirectToRoute('auth.signup');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/auth/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/signup/{token}', name: 'auth.signup.confirm', methods: ['GET'])]
    public function confirm(string $token, SignUp\Confirm\Handler $handler): Response
    {
        $command = new SignUp\Confirm\Command($token);

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email is successfully confirmed.');

            return $this->redirectToRoute('home');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return $this->redirectToRoute('home');
        }
    }
}
