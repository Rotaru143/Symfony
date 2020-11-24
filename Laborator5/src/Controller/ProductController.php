<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
   /**
     * @Route("/product", name="create_product")
     */
    public function createProduct(ValidatorInterface $validator): Response
    {
        
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName('Monitor');
        $product->setPrice(2000);
        $product->setDescription('styl');

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }
    /**
 * @Route("/product/{id}", name="product_show")
 */
public function show(Product $product)
{
   /* $product = $productRepository
        ->find($id);
    $product = $this->getDoctrine()
        ->getRepository(Product::class)
        ->find($id);
    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }
    return new Response('Check out this great product: '.$product->getName());*/

    // or render a template
    // in the template, print things with {{ product.name }}
     return $this->render('Product/show.html.twig', ['product' => $product]);
}
    /**
 * @Route("/list", name="product_list")
 */
public function showAll()
{
    $products  = $this->getDoctrine()->getRepository(Product::class)->findAll();

    return $this->render('Product/list.html.twig',[
        'products' => $products,
    ]);
}

/**
 * @Route("/product/edit/{id}", name="product_edit")
 */
public function update($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Product::class)->find($id);

    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }

    $product->setName('New product name!');
    $entityManager->flush();

    return $this->redirectToRoute('product_show', [
        'id' => $product->getId()
    ]);
}
/**
 * @Route("/product/delete/{id}", name="product_delete")
 */
public function delete($id){
    $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
    $entityManager = $this->getDoctrine()->getManager();

    //remove the product with id = $id
    $entityManager -> remove($product);
    $entityManager -> flush();

    return new Response('Deleted product with id '.$id);
}
/**
 * @Route("/greater/{price}", name="product_greater")
 */
public function greater($price){
    $minPrice = $price;
    $products = $this->getDoctrine()
    ->getRepository(Product::class)
    ->findAllGreaterThanPrice($minPrice);

    return $this->render('Product/greater.html.twig',[
        'products' => $products,
        'price' =>$minPrice,
    ]);
}

}