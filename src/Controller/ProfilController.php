<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\ChangePasswordFormType;
use App\Form\DuckProfilType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\DuckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Maker\MakeResetPassword;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil_index")
     */
    public function index(): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class, null, [
            'action' => $this->generateUrl('profil_edit_password'),
        ]);

        return $this->render('profil/index.html.twig', [
            'duck' => $this->getUser(),
            'editPassword' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil/edit", name="profil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $form = $this->createForm(DuckProfilType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_index');
        }

        return $this->render('profil/edit.html.twig', [
            'duck' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil/edit_password", name="profil_edit_password", methods={"GET","POST"})
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             $this->getUser()->setPassword(
                $passwordEncoder->encodePassword(
                    $this->getUser(),
                    $form->get('plainPassword')->getData()
                )
            );

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_index');
        }

        return $this->render('profil/edit_password.html.twig', [
            'duck' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }
}
