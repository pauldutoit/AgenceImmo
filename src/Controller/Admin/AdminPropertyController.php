<?php

namespace App\Controller\Admin;

use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;
use App\Form\PropertyType;


class AdminPropertyController extends Controller
{
    public function __construct(PropertyRepository $repository, ObjectManager $em){
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return Response
     */

    public function index() : Response
    {
        $properties = $this->repository->findAll();

        return $this->render('admin/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/{id}/edit",name="admin.property.edit")
     * @return Response
     */

    public function edit(Property $property, Request $request) : Response
    {
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/edit.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/bien/create", name="admin.property.new")
     */

    public function new(Request $request): Response
    {

        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($property);
            $this->em->flush();
            return $this->redirectToRoute('admin.property.index');
        }

    return $this->render('admin/new.html.twig',[
        'property' => $property,
        'form' => $form->createView()
    ]);

    }

    /**
     * @Route("/admin/{id}/delete",name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @return Response
     */

    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))){
            $this->em->remove($property);
            $this->em->flush();
        }
        return $this->redirectToRoute('admin.property.index');

    }


}