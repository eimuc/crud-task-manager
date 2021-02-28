<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Master;
use App\Entity\Outfit;


class OutfitController extends AbstractController
{
    /**
     * @Route("/outfit", name="outfit_index", methods={"GET"})
     */
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $masters = $this->getDoctrine()
        ->getRepository(Master::class)
        ->findAll();


        $outfits = $this->getDoctrine()
        ->getRepository(Outfit::class);
        if ('all' !== $r->query->get('master_id') && null !== $r->query->get('master_id')) {
            $outfits = $outfits->findBy(['master_id' => $r->query->get('master_id')], ['type' => 'asc']);
        }
        else {
            $outfits = $outfits->findAll();
        }
       
        return $this->render('outfit/index.html.twig', [
            'outfits' => $outfits,
            'masters' => $masters,
            'masterId' => $r->query->get('master_id') ?? 0
        ]);
    }

    /**
     * @Route("/outfit/create", name="outfit_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {

        $masters = $this->getDoctrine()
        ->getRepository(Master::class)
        ->findBy([]);

        $outfit_type = $r->getSession()->getFlashBag()->get('outfit_type', []);
        $outfit_color = $r->getSession()->getFlashBag()->get('outfit_color', []);
        $outfit_size = $r->getSession()->getFlashBag()->get('outfit_size', []);
        $outfit_about = $r->getSession()->getFlashBag()->get('outfit_about', []);

        return $this->render('outfit/create.html.twig', [
            'masters' => $masters,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'outfit_type' => $outfit_type[0] ?? '',
            'outfit_color' => $outfit_color[0] ?? '',
            'outfit_size' => $outfit_size[0] ?? '',
            'outfit_about' => $outfit_about[0] ?? ''
        ]);
    }


     /**
     * @Route("/outfit/store", name="outfit_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {

        $master = $this->getDoctrine()
        ->getRepository(Master::class)
        ->find($r->request->get('outfit_master_id'));


        $outfit = new Outfit;
        $outfit
        ->setType($r->request->get('outfit_type'))
        ->setSize($r->request->get('outfit_size'))
        ->setAbout($r->request->get('outfit_about'))
        ->setMaster($master);

        $errors = $validator->validate($outfit);

        if (count($errors) > 0) {
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('outfit_create');
        }

        if(null === $master) {
            return $this->redirectToRoute('outfit_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($outfit);
        $entityManager->flush();



        return $this->redirectToRoute('outfit_index');
    }


      /**
     * @Route("/outfit/edit/{id}", name="outfit_edit", methods={"GET"})
     */
    public function edit(Request $r, int $id): Response
    {
        $outfit = $this->getDoctrine()
        ->getRepository(Outfit::class)
        ->find($id);

        $masters = $this->getDoctrine()
        ->getRepository(Master::class)
        ->findBy([]);

        $outfit_type = $r->getSession()->getFlashBag()->get('outfit_type', []);
        $outfit_color = $r->getSession()->getFlashBag()->get('outfit_color', []);
        $outfit_size = $r->getSession()->getFlashBag()->get('outfit_size', []);
        $outfit_about = $r->getSession()->getFlashBag()->get('outfit_about', []);
        
        return $this->render('outfit/edit.html.twig', [
            'outfit' => $outfit,
            'masters' => $masters,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'outfit_type' => $outfit_type[0] ?? '',
            'outfit_color' => $outfit_color[0] ?? '',
            'outfit_size' => $outfit_size[0] ?? '',
            'outfit_about' => $outfit_about[0] ?? ''
        ]);
    }


      /**
     * @Route("/outfit/update/{id}", name="outfit_update", methods={"POST"})
     */
    public function update(Request $r, $id): Response
    {

        $outfit = $this->getDoctrine()
        ->getRepository(Outfit::class)
        ->find($id);

        // $master = $this->getDoctrine()
        // ->getRepository(Master::class)
        // ->find($r->request->get('outfits_master'));

        $outfit
        ->setType($r->request->get('outfit_type'))
        ->setSize($r->request->get('outfit_size'))
        ->setAbout($r->request->get('outfit_about'))
        ->setMasterId($r->request->get('outfit_master_id'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($outfit);
        $entityManager->flush();

        return $this->redirectToRoute('outfit_index');
    }


      /**
     * @Route("/outfit/delete/{id}", name="outfit_delete", methods={"POST"})
     */
    public function delete($id): Response
    {

        $outfit = $this->getDoctrine()
        ->getRepository(Outfit::class)
        ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($outfit);
        $entityManager->flush();



        return $this->redirectToRoute('outfit_index');
    }
}
