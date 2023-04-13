<?php

namespace App\Controller;

use App\Entity\Url;
use App\Repository\UrlRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Validator\Constraints\Url as UrlConstraints;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UrlsController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[Route('/', name: 'app_url_create', methods: ["GET", "POST"])]

    public function create(Request $request, UrlRepository $urlRepository): Response
    {

        $form = $this->createFormBuilder()
            ->add('original', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Enter the URL to shorten here !'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'You need to enter an URL']),
                    new UrlConstraints(['message' => 'The URL entered is invalid.'])
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $url = $urlRepository->findOneBy(['Original' => $form['original']->getData()]);

            if ($url) {
                return $this->redirectToRoute('app_urls_preview', ['shortened' => $url->getShortened()]);
            }
        }



        return $this->render('urls/create.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/{shortened}/preview', name: 'app_urls_preview', methods: 'GET')]
    public function preview(Url $url): Response
    {
        return $this->render('urls/preview.html.twig', compact('url'));
    }

    #[Route('/{shortened}', name: 'app_urls_show', methods: 'GET')]
    public function show(Url $url): Response
    {
        return $this->redirect($url->getOriginal());
    }
}
