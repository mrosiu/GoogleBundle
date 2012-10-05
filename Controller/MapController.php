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
        $url = 'http://maps.google.com/maps/geo?hl=pl&output=json&oe=utf8&q=' . urlencode($search);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($ch);

        $status = 404;
        $markers = array();
        if (200 === curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            $response = json_decode($response);
            if (!empty($response->Placemark)) {
                foreach ($response->Placemark as $result) {
                    if (isset($result->Point)
                        && isset($result->Point->coordinates)
                    ) {
                        $localization = array();
                        $localization['lat']    = $result->Point->coordinates[1];
                        $localization['lng']    = $result->Point->coordinates[0];
                        if (isset($result->AddressDetails->Country->AdministrativeArea->AdministrativeAreaName)) {
                            $localization['province'] = $result->AddressDetails->Country->AdministrativeArea->AdministrativeAreaName;
                        }
                        if (isset($result->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality)) {
                            $address = $result->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality;
                            if (isset($address->LocalityName)) {
                                $localization['name'] = $address->LocalityName;
                            }
                            if (isset($address->PostalCode)) {
                                foreach ($address->PostalCode as $postCode) {
                                    $localization['postCode'] = $postCode;
                                }
                            }
                            if (isset($address->Thoroughfare)
                                && isset($address->Thoroughfare->ThoroughfareName)
                            ) {
                                $localization['street'] = $address->Thoroughfare->ThoroughfareName;
                            }
                        }
                        $localization['text'] = json_encode($localization);
                        $markers[] = $localization;
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
