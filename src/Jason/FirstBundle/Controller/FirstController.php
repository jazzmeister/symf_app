<?php

namespace Jason\FirstBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FirstController extends Controller
{
	public function indexAction($name)
	{
		return $this->render(
			'JasonFirstBundle:First:index.html.twig',
			array('name' => $name)
		);
		// render a php template instead
	}
}