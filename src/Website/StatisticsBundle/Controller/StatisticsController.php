<?php

namespace Website\StatisticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;
use Website\StatisticsBundle\Entity\Statistic;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;


class StatisticsController extends Controller
{

    /**
     * Get all statistics entity.
     *
     */
    public function indexAction()
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        try {
            $statistics = $em->getRepository('WebsiteStatisticsBundle:Statistic')->findBy(array(),array('createdAt' => 'DESC'));

            return $this->render('WebsiteStatisticsBundle:Statistic:index.html.twig', array(
                'statistics' => $statistics,
            ));
        } catch (\Exception $e) {
            $session->getFlashBag()->add('error', 'Error Message: ' . $e->getMessage());
            return new RedirectResponse($this->generateUrl('statistic_index'));
        }
    }

    /**
     * Creates a new statistic entity.
     *
     */
    public function newAction(Request $request)
    {
        $session = new Session();
        $statistic = new Statistic();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('Website\StatisticsBundle\Form\StatisticType', $statistic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->validationForm($request);
            $em->getConnection()->beginTransaction();
            try {
                $datetime = \DateTime::createFromFormat('Y-m-d', $request->request->get('website_statisticsbundle_statistic')["createdAt"]);
                $datetime->format('Y-m-d H:i:s');

                $statistic->setCreatedAt($datetime);
                $em->persist($statistic);
                $em->flush();
                $em->getConnection()->commit();

                $session->getFlashBag()->add('success', 'Successfuly created');
                return new RedirectResponse($this->generateUrl('statistic_index'));
            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                $em->close();

                $session->getFlashBag()->add('error', 'Error Message: ' . $e->getMessage());
                return new RedirectResponse($this->generateUrl('statistic_new'));
            }
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
            $this->validationForm($request);
            $em->getConnection()->beginTransaction();
            try {
                $datetime = \DateTime::createFromFormat('Y-m-d', $request->request->get('website_statisticsbundle_statistic')["createdAt"]);
                $datetime->format('Y-m-d H:i:s');

                $statistic->setCreatedAt($datetime);
                $em->persist($statistic);
                $em->flush();
                $em->getConnection()->commit();

                $session->getFlashBag()->add('success', 'Successfuly updated');
                return new RedirectResponse($this->generateUrl('statistic_index'));
            } catch (\Exception $e) {

                $em->getConnection()->rollback();
                $em->close();

                $session->getFlashBag()->add('error', 'Error Message: ' . $e->getMessage());
                return new RedirectResponse($this->generateUrl('statistic_new'));
            }
        }

        return $this->render('WebsiteStatisticsBundle:Statistic:edit.html.twig', array(
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Validation Form.
     *
     */
    private function validationForm($request)
    {
        $session = new Session();
        $validator = $this->get('validator');

        $datas = $request->request->get("website_statisticsbundle_statistic");
        $datetime = \DateTime::createFromFormat('Y-m-d', $datas["createdAt"]);
        $datetime->format('Y-m-d H:i:s');

        $input = ['productArticle' => $datas["productArticle"], 'clicks' => $datas["clicks"], 'createdAt' => $datetime];

        $constraints = new Assert\Collection([
            'productArticle' => [new Assert\Regex(array('pattern' => '/^[0-9]\d*$/','message' => 'Please use only positive numbers.'))],
            'clicks' => [new Assert\Regex(array('pattern' => '/^[0-9]\d*$/','message' => 'Please use only positive numbers.'))],
            'createdAt' => [new Assert\Date()],
        ]);

        $violations = $validator->validate($input, $constraints);

        if (count($violations) > 0) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $errorMessages = [];
            foreach ($violations as $violation) {
                $accessor->setValue($errorMessages,
                    $violation->getPropertyPath(),
                    $violation->getMessage());
            }

            $referer = $request->headers->get('referer');
            $session->getFlashBag()->add('error', $errorMessages);
            return new RedirectResponse($referer);

        } else {
            return true;
        }
    }
}
