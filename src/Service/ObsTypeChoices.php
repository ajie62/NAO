<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 09/04/2018
 * Time: 14:29
 */

namespace App\Service;

/**
 * Class ObsTypeChoices
 * @package App\Service
 */
class ObsTypeChoices
{
    /**
     * Return a list of genders
     * @return array
     */
    public function getGenders()
    {
        return [
            'Indéfini' => null,
            'Mâle' => 'Mâle',
            'Femelle' => 'Femelle'
        ];
    }

    /**
     * Get a list of death causes
     * @return array
     */
    public function getDeathCauses()
    {
        $deathCauses = [
            'Indéfinie' => null,
            'Abattu' => 'Abattu',
            'Percuté par un véhicule' => 'Percuté',
            'Empoisonné' => 'Empoisonné',
            'Tué par un autre animal' => 'Tué'
        ];

        return $deathCauses;
    }

    /**
     * Get a list of flight directions
     * @return array
     */
    public function getFlightDirections()
    {
        return [
            'Indéfinie' => null,
            'Au sol' => 'Au sol',
            'Nord' => 'N',
            'Nord-Nord-Est' => 'N.N.E',
            'Nord-Est' => 'N.E',
            'Est-Nord-Est' => 'E.N.E',
            'Est' => 'E',
            'Est-Sud-Est' => 'E.S.E',
            'Sud-Est' => 'S.E',
            'Sud-Sud-Est' => 'S.S.E',
            'Sud' => 'S',
            'Sud-Sud-Ouest' => 'S.S.O',
            'Sud-Ouest' => 'S.O',
            'Ouest-Sud-Ouest' => 'O.S.O',
            'Ouest' => 'O',
            'Ouest-Nord-Ouest' => 'O.N.O',
            'Nord-Ouest' => 'N.O',
            'Nord-Nord-Ouest' => 'N.N.O'
        ];
    }

    /**
     * Get a list of atlas codes
     * @return array
     */
    public function getAtlasCodes()
    {
        $codes = [];

        for ($i = 0; $i < 20; $i++)
            array_push($codes, $i);

        return $codes;
    }
}