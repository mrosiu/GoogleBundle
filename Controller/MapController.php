<?php

namespace AntiMattr\GoogleBundle\Controller;

use AntiMattr\GoogleBundle\Helper\CitiesTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use AntiMattr\GoogleBundle\Form\SearchType;

/**
 * @Route("/google")
 */
class MapController extends Controller
{
    /**
     * @Route("/search")
     */
    public function searchAction()
    {
        $form = $this->createForm(new SearchType());

        return $this->render('GoogleBundle:Maps:search.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/cities/{search}", defaults = {"search" = ""})
     */
    public function citiesAction($search)
    {
        $cityData = $this->get('google.city.searcher')->getCityData($search);
        $citiesTransformer = new CitiesTransformer();

        return new Response(json_encode($citiesTransformer->transform($cityData)), 200, array(
            'Content-type' => 'application/json',
        ));
    }
}
