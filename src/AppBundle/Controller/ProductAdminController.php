<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductAdminController extends Controller
{
    /**
     * @Route("/admin/products", name="product_list")
     */
    public function listAction()
    {
        $products = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/products/new", name="product_new")
     */
    public function newAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $product = new Product();
            $product->setName($request->get('name'));
            $product->setPrice($request->get('price'));
            $product->setDescription($request->get('description'));

            $product->setAuthor($this->getUser());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'product created');

            return $this->redirectToRoute('product_list');
        }
        return $this->render('product/new.html.twig');
    }
}
