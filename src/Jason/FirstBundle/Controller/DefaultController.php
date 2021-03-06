<?php

namespace Jason\FirstBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jason\FirstBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Jason\FirstBundle\Entity\Category;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JasonFirstBundle:Default:index.html.twig', array('name' => $name));
    }

    public function createAction()
	{
		$category = new Category();
		$category->setName('Main Products');

	    $product = new Product();
	    $product->setName('A Foo Bar');
	    $product->setPrice('19.99');
	    $product->setDescription('Lorem ipsum dolor');
	    //relate this product to the category
	    $product->setCategory($category);

	    $em = $this->getDoctrine()->getManager();
	    $em->persist($product);
	    $em->persist($category);
	    $em->flush();

	    return $this->render(
	    	'JasonFirstBundle:Default:create.html.twig',
	    	array('product_id' => $product->getId(),
	    		'category_id' => $category->getId()
	    	)
	    );

	    /*return new Response(
	    	'Created product id '.$product->getId()
	    	. '<br/>and category id: '.$category->getId()
	    );*/
	}

	public function showAction($id)
	{
		$product = $this->getDoctrine()
			->getRepository('JasonFirstBundle:product')
			->find($id);

		if (!$product) {
			throw $this->createNotFoundException(
				'No product found for id '.$id
			);
		}else{
			return new Response('This is your name: '.$product->getName().'<br/>Price: '.$product->getPrice().'</br>Description: '.$product->getDescription() . '<br/>Category Name: ' . $product->getCategory()->getName());
		}
	}

	public function updateAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('JasonFirstBundle:Product')->find($id);

		if (!$product) {
			throw $this->createNotFoundException(
				'No Product found for id '.$id
			);
		}

		$product->setName('New product names2!');
		$em->flush();

		return $this->redirect($this->generateUrl('product_show', array('id' => $id)));
	}

	public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('JasonFirstBundle:Product')->find($id);

		if (!$product) {
			throw $this->createNotFoundException(
				'No Product found for id '.$id
			);
		}

		$em->remove($product);
		$em->flush();

		return $this->redirect($this->generateUrl('product_showCertain'));
	}

	/*
	*	Show results by certain parameters
	*/
	public function showCertainAction()
	{
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
    	'SELECT p, c
    	FROM JasonFirstBundle:Product p
    	JOIN p.category c
    	WHERE p.price >= :price
    	ORDER BY p.price ASC'
		)->setParameter('price', '0.01');
		//->setMaxResults(10);

		try {
		    $products = $query->getResult();
		} catch (\Doctrine\Orm\NoResultException $e) {
		    $products = null;
		}

		return $this->render(
	    	'JasonFirstBundle:Default:showAll.html.twig',
	    	array('products' => $products,
	    	)
	    );
	}
}
