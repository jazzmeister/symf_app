<?php

namespace Jason\FirstBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Jason\FirstBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JasonFirstBundle:Default:index.html.twig', array('name' => $name));
    }

    public function createAction()
	{
	    $product = new Product();
	    $product->setName('A Foo Bar');
	    $product->setPrice('19.99');
	    $product->setDescription('Lorem ipsum dolor');

	    $em = $this->getDoctrine()->getManager();
	    $em->persist($product);
	    $em->flush();

	    return new Response('Created product id '.$product->getId());
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
			return new Response('This is your name: '.$product->getName().'<br/>Price: '.$product->getPrice().'</br>Description: '.$product->getDescription());
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
    	'SELECT p
    	FROM JasonFirstBundle:Product p
    	WHERE p.price >= :price
    	ORDER BY p.price ASC'
		)->setParameter('price', '0.01');
		//->setMaxResults(10);

		try {
		    $products = $query->getResult();
		} catch (\Doctrine\Orm\NoResultException $e) {
		    $products = null;
		}

		$resultHTML ='';
		foreach ($products as $product) {
			$resultHTML .= 'ID: ' . $product->getId() . ' - Name: '. $product->getName() . ' - Price: ' . $product->getPrice() . '<br>';
		}
		return new Response($resultHTML);

		/*
		if ($product)
			return new Response('This is your name: '.$product->getName().'<br/>Price: '.$product->getPrice().'</br>Description: '.$product->getDescription());
		else 
			return new Response('No products found');
		*/
	}
}
