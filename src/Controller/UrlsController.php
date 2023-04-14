<?php

namespace App\Controller;


use App\Entity\Url;

use Illuminate\Support\Str;
use App\Repository\UrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Url as UrlConstraints;

class UrlsController extends AbstractController


{
    private $urlRepository;

    function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    #[Route('/', name: 'app_home', methods: ["GET", "POST"])]


    public function create(Request $request, EntityManagerInterface $em): Response
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


            $url = $this->urlRepository->findOneBy(['Original' => $form['original']->getData()]);

            if ($url) {
                return $this->redirectToRoute('app_urls_preview', ['shortened' => $url->getShortened()]);
            }

            $url = new Url;
            $url->setOriginal($form['original']->getData());
            $url->setShortened($this->getUniqueShortenedString());
            $em->persist($url);
            $em->flush();

            return $this->redirectToRoute('app_urls_preview', ['shortened' => $url->getShortened()]);
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

    private function getUniqueShortenedString()
    {
        $shortened =  Str::random(6);
        if ($this->urlRepository->findOneBy(compact('shortened'))) {
            return $this->getUniqueShortenedString();
        }
        return $shortened;
    }
}
