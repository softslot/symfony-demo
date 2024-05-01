<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UseCase\SignUp;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignUpController extends AbstractController
{
    #[Route('/sign-up', name: 'auth.signup', methods: ['POST', 'GET'])]
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
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/auth/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
