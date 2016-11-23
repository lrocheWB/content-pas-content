<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Feature;
use AppBundle\Form\FeatureType;

/**
 * Feature controller.
 *
 * @Route("/admin/feature")
 */
class FeatureController extends Controller
{
    /**
     * Lists all Feature entities.
     *
     * @Route("/", name="feature_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $features = $em->getRepository('AppBundle:Feature')->findAll();

        return $this->render('feature/index.html.twig', array(
            'features' => $features,
        ));
    }

    /**
     * Creates a new Feature entity.
     *
     * @Route("/new", name="feature_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $feature = new Feature();
        $form = $this->createForm('AppBundle\Form\FeatureType', $feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feature);
            $em->flush();

            return $this->redirectToRoute('feature_show', array('id' => $feature->getId()));
        }

        return $this->render('feature/new.html.twig', array(
            'feature' => $feature,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Feature entity.
     *
     * @Route("/{id}", name="feature_show")
     * @Method("GET")
     */
    public function showAction(Feature $feature)
    {
        $deleteForm = $this->createDeleteForm($feature);

        return $this->render('feature/show.html.twig', array(
            'feature' => $feature,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Feature entity.
     *
     * @Route("/{id}/edit", name="feature_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Feature $feature)
    {
        $deleteForm = $this->createDeleteForm($feature);
        $editForm = $this->createForm('AppBundle\Form\FeatureType', $feature);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feature);
            $em->flush();

            return $this->redirectToRoute('feature_edit', array('id' => $feature->getId()));
        }

        return $this->render('feature/edit.html.twig', array(
            'feature' => $feature,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Feature entity.
     *
     * @Route("/{id}", name="feature_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Feature $feature)
    {
        $form = $this->createDeleteForm($feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($feature);
            $em->flush();
        }

        return $this->redirectToRoute('feature_index');
    }

    /**
     * Creates a form to delete a Feature entity.
     *
     * @param Feature $feature The Feature entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Feature $feature)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('feature_delete', array('id' => $feature->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
