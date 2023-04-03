<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UrlsController extends AbstractController
{
    #[Route('/', name: 'app_url_create')]
    public function create(Request $request): Response
    {

        $form = $this->createFormBuilder()
            ->add('original', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Enter the URL to shorten here !'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'You need to enter an URL']),
                    new Url(['message' => 'The URL entered is invalid'])
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd('Traitement..');
        }



        return $this->render('urls/create.html.twig', ['form' => $form->createView()]);
    }
}
