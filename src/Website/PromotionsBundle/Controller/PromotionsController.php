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

        $promotionService = $this->container->get(PromotionService::class);

        if ($this->isGranted('ROLE_ADMIN')) {
            $promotions = $em->getRepository('WebsitePromotionsBundle:Promotion')->getPromotionsWithProductsWithStatistics();
        } else {
            $promotions = $em->getRepository('WebsitePromotionsBundle:Promotion')->getPromotionsWithProductsWithStatisticsById($user->getId());
        }

        $promotions = $promotionService->getDataStructure($promotions);

        foreach ($promotions as $item) {

            if (isset($item["prevDate"])) {
                $prevdate = $item["prevDate"];
                $statistics = $em->getRepository('WebsiteStatisticsBundle:Statistic')->getStatisticsByIdAndDate($item["prd_productArticle"], $item["prm_createdAt"], $prevdate);
            } else {
                $prevdate = null;
                $statistics = $em->getRepository('WebsiteStatisticsBundle:Statistic')->getStatisticsById($item["prd_productArticle"], $item["prm_createdAt"]);
            }

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

        if (!empty($data)) {

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

            $session->getFlashBag()->add('success', 'Successfuly created');
            return new RedirectResponse($this->generateUrl('promotion_index'));
        }

        return $this->render('WebsitePromotionsBundle:Promotion:new.html.twig', array(
            'promotion' => 'promotion',
        ));
    }

}
