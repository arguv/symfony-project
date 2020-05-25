<?php

namespace Website\PromotionsBundle\Controller;

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

class PromotionsController extends Controller
{

    /**
     * Get all promotions entity.
     *
     */
    public function indexAction()
    {
        $session = new Session();
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

            $promotions = $promotionService->getDataStructure($promotions);

            foreach ($promotions as $item) {

                if (isset($item["prevDate"])) {
                    $prevdate = $item["prevDate"]->format('Y-m-d');
                } else {
                    $prevdate = null;
                }
                $startdate = $item["prm_createdAt"]->format('Y-m-d');
                $statistics = $em->getRepository('WebsiteStatisticsBundle:Statistic')->getStatisticsByIdAndDate($item["prd_productArticle"], $startdate, $prevdate);

                $stat = [];
                foreach ($statistics as $data) {
                    $stat[] = [
                        "productId" => $data["st_productArticle"],
                        "clicks" => $data["st_clicks"],
                        "statisticCreatedAt" => $data["st_createdAt"]
                    ];
                }

                $result[$item["prm_id"]]["date"] = $item["prm_createdAt"];
                $result[$item["prm_id"]][] =
                    [
                        "promotionId" => $item["prm_id"],
                        "promotionCreatedAt" => $item["prm_createdAt"],
                        "note" => $item["pp_note"],
                        "productId" => $item["prd_productArticle"],
                        "date" => $stat
                    ];
            }

            return $this->render('WebsitePromotionsBundle:Promotion:index.html.twig', array(
                "result" => $result,
            ));
        } catch (\Exception $e) {
            $session->getFlashBag()->add('error', 'Error Message: ' . $e->getMessage());
            return new RedirectResponse($this->generateUrl('promotion_index'));
        }
    }

    /**
     * Creates a new promotion entity.
     *
     */
    public function newAction(Request $request)
    {
        $session = new Session();
        $user = $this->getUser();
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('post') && !empty($data)) {
            $this->validationForm($data, $request);
            $em->getConnection()->beginTransaction();
            try {
                $promotion = new Promotion();
                $promotion->setUserId($user->getId());
                $promotion->setCreatedAt(new \DateTime());
                $em->persist($promotion);

                foreach ($data as $item) {
                    $product = $em->getRepository('WebsitePromotionsBundle:Product')->findOneBy(array('productArticle' => $item['productId']));
                    if (null == $product) {
                        $product = new Product();
                        $product->setProductArticle((int)$item['productId']);
                        $product->setCreatedAt(new \DateTime());
                        $em->persist($product);
                    }

                    $promotionProduct = new PromotionProduct();
                    $promotionProduct->setNote($item['note']);
                    $promotionProduct->setCreatedAt(new \DateTime());
                    $promotionProduct->setProduct($product);
                    $promotionProduct->setPromotion($promotion);
                    $em->persist($promotionProduct);
                }
                $em->flush();
                $em->getConnection()->commit();

                $session->getFlashBag()->add('success', 'Successfuly created');
                return new RedirectResponse($this->generateUrl('promotion_index'));
            } catch (\Exception $e) {

                $em->getConnection()->rollback();
                $em->close();

                $session->getFlashBag()->add('error', 'Error Message: ' . $e->getMessage());
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
    private function validationForm($datas,$request)
    {
        $violations = null;
        $session = new Session();
        $validator = $this->get('validator');

        foreach ($datas as $data) {
            $input = ['productId' => (int)$data["productId"], 'note' => $data["note"]];

            $constraints = new Assert\Collection([
                'productId' => [new Assert\Regex(array('pattern' => '/^[0-9]\d*$/','message' => 'Please use only positive numbers.'))],
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
                $session->getFlashBag()->add('error', $errorMessages);
                return new RedirectResponse($this->generateUrl($referer));

            }
        }
        if (count($violations) < 0) {
            return true;
        }
    }

}
