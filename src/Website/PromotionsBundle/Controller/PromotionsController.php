<?php

namespace Website\PromotionsBundle\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;
use Website\PromotionsBundle\Entity\Promotion;
use Website\PromotionsBundle\Entity\Product;
use Website\PromotionsBundle\Entity\PromotionProduct;
use Website\PromotionsBundle\Service\PromotionService;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\DependencyInjection\ContainerAware;



class PromotionsController extends Controller
{

    /**
     * Get all promotions entity.
     *
     */
    public function indexAction()
    {
        $result = [];
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        try {
            $promotionService = $this->container->get(PromotionService::class);

            if ($this->isGranted('ROLE_ADMIN')) {
                $id = null;
            } else {
                $id = $user->getId();
            }

            $promotions = $em->getRepository('WebsitePromotionsBundle:Promotion')->getPromotionsWithProductsWithStatistics($id);

            $result = $promotionService->getDataStructure($promotions);

            return $this->render('WebsitePromotionsBundle:Promotion:index.html.twig', array(
                "result" => $result,
            ));

        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', 'Error Message: ' . $e->getMessage());
            return new RedirectResponse($this->generateUrl('promotion_index'));
        }
    }

    /**
     * Creates a new promotion entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('post') && !empty($data)) {
            $this->validationForm($data, $request);
            $em->getConnection()->beginTransaction();
            try {
                $promotion = new Promotion();
                $promotion->setUserId($user->getId());
                $em->persist($promotion);

                foreach ($data as $item) {
                    $product = $em->getRepository('WebsitePromotionsBundle:Product')->findOneBy(array('productArticle' => $item['productId']));
                    if (null == $product) {
                        $product = new Product();
                        $product->setProductArticle((int)$item['productId']);
                        $em->persist($product);
                    }

                    $promotionProduct = new PromotionProduct();
                    $promotionProduct->setNote($item['note']);
                    $promotionProduct->setProduct($product);
                    $promotionProduct->setPromotion($promotion);
                    $em->persist($promotionProduct);
                }
                $em->flush();
                $em->getConnection()->commit();

                $this->get('session')->getFlashBag()->add('success', 'Successfuly created');
                return new RedirectResponse($this->generateUrl('promotion_index'));
            } catch (\Exception $e) {

                $em->getConnection()->rollback();
                $em->close();

                $this->get('session')->getFlashBag()->add('error', 'Error Message: ' . $e->getMessage());
                return new RedirectResponse($this->generateUrl('promotion_index'));
            }
        }

        return $this->render('WebsitePromotionsBundle:Promotion:new.html.twig', array(
            'promotion' => 'promotion',
        ));
    }

    /**
     * Validation Form.
     *
     */
    private function validationForm($datas, $request)
    {
        $violations = null;
        $validator = $this->get('validator');

        foreach ($datas as $data) {
            $input = ['productId' => (int)$data["productId"], 'note' => $data["note"]];

            $constraints = new Assert\Collection([
                'productId' => [new Assert\Regex(array('pattern' => '/^[0-9]\d*$/', 'message' => 'Please use only positive numbers.'))],
                'note' => [new Assert\NotNull(), new Assert\Type('string')],
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
                $this->get('session')->getFlashBag()->add('error', $errorMessages);
                return new RedirectResponse($this->generateUrl($referer));

            }
        }
        return true;
    }

}
