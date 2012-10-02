<?php

namespace AntiMattr\GoogleBundle\Controller;

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
        $url = 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode($search) . '&sensor=false';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($ch);

        $status = 404;
        $markers = array();
        $localization = array(
            'lat'   => 0,
            'lng'   => 0,
            'name'  => ''
        );
        if (200 === curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            $response = json_decode($response);
            if (!empty($response->results)) {
                foreach ($response->results as $result) {
                    if (isset($result->geometry)
                        && isset($result->geometry->location)
                        && isset($result->geometry->location->lat)
                        && isset($result->geometry->location->lng)
                    ) {
                        $localization['lat']    = $result->geometry->location->lat;
                        $localization['lng']    = $result->geometry->location->lng;
                        $localization['name']   = $result->formatted_address;
                        $markers[]              = $localization;
                    }
                }
            } else {
                $markers = array();
            }
            $status = 200;
        } else {
            $status = 500;
            $markers = array();
        }
        curl_close($ch);

        return new Response(json_encode($markers), $status, array(
            'Content-type' => 'application/json',
        ));
    }
}
