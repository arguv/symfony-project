<?php

namespace Website\StatisticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;
use Website\StatisticsBundle\Entity\Statistic;

class StatisticsController extends Controller
{
    /**
     * Get all statistics entity.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $statistics = $em->getRepository('WebsiteStatisticsBundle:Statistic')->findBy(array(),array('createdAt' => 'DESC'));

        return $this->render('WebsiteStatisticsBundle:Statistic:index.html.twig', array(
            'statistics' => $statistics,
        ));
    }

    /**
     * Creates a new statistic entity.
     *
     */
    public function newAction(Request $request)
    {
        $date =  new \DateTime($request->request->get('website_statisticsbundle_statistic')["createdAt"] . " 00:00:00");

        $session = new Session();
        $statistic = new Statistic();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('Website\StatisticsBundle\Form\StatisticType', $statistic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $statistic->setCreatedAt($date);
            $em->persist($statistic);
            $em->flush();

            $session->getFlashBag()->add('success', 'Successfuly created');
            return new RedirectResponse($this->generateUrl('statistic_index'));
        }

        return $this->render('WebsiteStatisticsBundle:Statistic:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Edit an existing statistic entity.
     *
     */
    public function editAction(Request $request, Statistic $statistic)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $statistic = $em->getRepository('WebsiteStatisticsBundle:Statistic')->findOneById($statistic->getId());
        $editForm = $this->createForm('Website\StatisticsBundle\Form\StatisticType', $statistic);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($statistic);
            $em->flush();

            $session->getFlashBag()->add('success', 'Successfuly updated');
            return new RedirectResponse($this->generateUrl('statistic_index'));
        }

        return $this->render('WebsiteStatisticsBundle:Statistic:edit.html.twig', array(
            'edit_form' => $editForm->createView(),
        ));
    }
}
