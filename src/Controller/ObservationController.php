<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 27/03/2018
 * Time: 18:14
 */

namespace App\Controller;

use App\Entity\Observation;
use App\Entity\Species;
use App\Entity\User;
use App\Form\ObservationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ObservationController extends AbstractController
{
    /** @var EntityManagerInterface $entityManager */
    private $em;
    private $choices;

    public function __construct($entityManager, $choices)
    {
        $this->em = $entityManager;
        $this->choices = $choices;
    }

    /**
     * List of observations
     * @Route("/observation", name="observation.search")
     */
    public function searchObservation()
    {
        $observationList = $this->em->getRepository(Observation::class)->findAll();

        return $this->render('observation/search.html.twig', [
            'observationList' => $observationList,
        ]);
    }

    /**
     * Add an observation
     * @Route("/observation/add", name="observation.add")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request)
    {
        return $this->setObservation($request, new Observation());
    }

    /**
     * Update an observation
     * @Route("/observation/{id}/update", name="observation.update", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param Observation $observation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Observation $observation)
    {
        return $this->setObservation($request, $observation);
    }

    /**
     * Delete an observation
     * @Route("observation/{id}/delete", name="observation.delete", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param Observation $observation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, Observation $observation)
    {
        $form = $this->getDeleteForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('DELETE')) {
            $this->em->remove($observation);
            $this->em->flush();
            $this->addFlash('notice', 'L\'observation a bien été supprimée !');

            return $this->redirectToRoute('observation.list');
        }

        return $this->render('observation/delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Set an observation
     *
     * @param Request $request
     * @param Observation $observation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function setObservation(Request $request, Observation $observation)
    {
        $isNewObservation = $observation->getId() === null;
        $speciesList = $this->em->getRepository(Species::class)->findBy(
            array(),
            array('id' => 'desc'),
            null,
            null
        );
        $form = $this->createForm(ObservationType::class, $observation, ['choices_data' => $this->choices]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$isNewObservation)
                $observation->setUpdatedAt(new \DateTime());

            $observation->setUser($this->getUser());
            $this->em->persist($observation);
            $this->em->flush();

            if ($isNewObservation)
                $this->addFlash('notice', 'L\'observation a bien été ajoutée !');
            else
                $this->addFlash('notice', 'L\'observation a bien été mise à jour !');

            return $this->redirectToRoute('observation.search');
        }

        return $this->render('observation/set.html.twig', [
            'form' => $form->createView(),
            'isNewObservation' => $isNewObservation,
            'observation' => $observation,
            'speciesList' => $speciesList,
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getDeleteForm()
    {
        $form = $this->createFormBuilder()->setMethod('DELETE')->getForm();

        return $form;
    }

    /**
     * @Route("/ajax_search_observation", name="observation.ajax.search_observation")
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function ajaxGetObservation(Request $request) {
        $data = $request->request->get('id');
        $results = $this->em->getRepository(Species::class)->findOneBy(["id" => $data]);

        $observations = [];
        /** @var Observation $observation */
        foreach ($results->getObservations() as $observation) {
            /** @var User $user */
            $user = $observation->getUser();
            $userFirstname = ucfirst($user->getFirstname());
            $userLastname = ucfirst($user->getLastname());
            $dateFormat = 'd/m/Y';
            $observedAt = date_format($observation->getObservedAt(), $dateFormat);
            $updatedAt = null;

            if ($observation->getUpdatedAt())
                $updatedAt = date_format($observation->getUpdatedAt(), $dateFormat);

            $observations[] = [
                'id' => $observation->getId(),
                'longitude' => $observation->getLongitude(),
                'latitude' => $observation->getLatitude(),
                'flightDirection' => $observation->getFlightDirection(),
                'sex' => $observation->getSex(),
                'deceased' => $observation->getDeceased(),
                'deathCause' => $observation->getDeathCause(),
                'atlasCode' => $observation->getAtlasCode(),
                'comment' => $observation->getComment(),
                'observedAt' => $observedAt,
                'updatedAt' => $updatedAt,
                'image' => $observation->getImage(),
                'userFirstname' => $userFirstname,
                'userLastname' => $userLastname
            ];
        }

        if ($observations)
            return $this->json($observations);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/species", name="observation.get_species", methods={"GET"})
     * Get species (async request)
     * @param Request $request
     * @return JsonResponse
     */
    public function getSpecies(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $results = $this->em->getRepository(Species::class)->findAll();
            $output = [];
            /** @var Species $result */
            foreach ($results as $result) {
                $output[] = [
                    'id' => $result->getId(),
                    'name' => $result->getName(),
                    'family' => $result->getFamily(),
                    'order' => $result->getOrder()
                ];
            }
            $output = ['count' => count($output), 'items' => $output];
            return $this->json($output);
        }
        throw new NotFoundHttpException();
    }
}
