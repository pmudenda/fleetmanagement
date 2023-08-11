<?php

namespace App\Services\Requisitions;

class InterCityDistanceService
{
    private $interCityDistanceArray;

    public function __construct()
    {
        $cities = array(
            'Lusaka' => array('Lusaka' => 0, 'Kitwe' => 360, 'Ndola' => 319, 'Kabwe' => 140, 'Chingola' => 412, 'Mufulira' => 401, 'Luanshya' => 330, 'Livingstone' => 470, 'Kasama' => 859),
            'Kitwe' => array('Lusaka' => 360, 'Kitwe' => 0, 'Ndola' => 63, 'Kabwe' => 219, 'Chingola' => 53, 'Mufulira' => 43, 'Luanshya' => 55, 'Livingstone' => 830, 'Kasama' => 760),
            'Ndola' => array('Lusaka' => 319, 'Kitwe' => 63, 'Ndola' => 0, 'Kabwe' => 180, 'Chingola' => 114, 'Mufulira' => 68, 'Luanshya' => 51, 'Livingstone' => 795, 'Kasama' => 766),
            'Kabwe' => array('Lusaka' => 140, 'Kitwe' => 219, 'Ndola' => 180, 'Kabwe' => 0, 'Chingola' => 272, 'Mufulira' => 246, 'Luanshya' => 180, 'Livingstone' => 616, 'Kasama' => 719),
            'Chingola' => array('Lusaka' => 412, 'Kitwe' => 53, 'Ndola' => 114, 'Kabwe' => 272, 'Chingola' => 0, 'Mufulira' => 58, 'Luanshya' => 106, 'Livingstone' => 887, 'Kasama' => 869),
            'Mufulira' => array('Lusaka' => 401, 'Kitwe' => 43, 'Ndola' => 68, 'Kabwe' => 246, 'Chingola' => 58, 'Mufulira' => 0, 'Luanshya' => 92, 'Livingstone' => 863, 'Kasama' => 526),
            'Luanshya' => array('Lusaka' => 330, 'Kitwe' => 55, 'Ndola' => 51, 'Kabwe' => 180, 'Chingola' => 106, 'Mufulira' => 92, 'Luanshya' => 0, 'Livingstone' => 797, 'Kasama' => 768),
            'Livingstone' => array('Lusaka' => 470, 'Kitwe' => 830, 'Ndola' => 795, 'Kabwe' => 616, 'Chingola' => 887, 'Mufulira' => 863, 'Luanshya' => 797, 'Livingstone' => 0, 'Kasama' => 1334),
            'Kasama' => array('Lusaka' => 859, 'Kitwe' => 760, 'Ndola' => 766, 'Kabwe' => 719, 'Chingola' => 869, 'Mufulira' => 526, 'Luanshya' => 768, 'Livingstone' => 1334, 'Kasama' => 0),
            'Chipata' => array('Lusaka' => 575, 'Kitwe' => 929, 'Ndola' => 747, 'Kabwe' => 711, 'Chingola' => 982, 'Mufulira' => 874, 'Luanshya' => 892, 'Livingstone' => 1051, 'Kasama' => 536),
            'Mongu' => array('Lusaka' => 595, 'Kitwe' => 945, 'Ndola' => 795, 'Kabwe' => 724, 'Chingola' => 739, 'Mufulira' => 768, 'Luanshya' => 798, 'Livingstone' => 513, 'Kasama' => 1357),
            'Mansa' => array('Lusaka' => 764, 'Kitwe' => 222, 'Ndola' => 245, 'Kabwe' => 425, 'Chingola' => 241, 'Mufulira' => 183, 'Luanshya' => 295, 'Livingstone' => 1040, 'Kasama' => 344),
            'Kapiri Mposhi' => array('Lusaka' => 201, 'Kitwe' => 158, 'Ndola' => 121, 'Kabwe' => 61, 'Chingola' => 209, 'Mufulira' => 190, 'Luanshya' => 119, 'Livingstone' => 678, 'Kasama' => 658),
            'Mpika' => array('Lusaka' => 645, 'Kitwe' => 591, 'Ndola' => 552, 'Kabwe' => 505, 'Chingola' => 642, 'Mufulira' => 573, 'Luanshya' => 552, 'Livingstone' => 1122, 'Kasama' => 217),
            'Mkushi' => array('Lusaka' => 301, 'Kitwe' => 245, 'Ndola' => 204, 'Kabwe' => 159, 'Chingola' => 296, 'Mufulira' => 272, 'Luanshya' => 206, 'Livingstone' => 774, 'Kasama' => 566),
        );

        $this->interCityDistanceArray = $cities;
    }

    /**
     * @return array
     */
    public function getInterCityDistanceArray(): array
    {
        return $this->interCityDistanceArray;
    }

    public function getDistance($from, $to): int
    {
        $result = $this->interCityDistanceArray[$from];
        return $result[$to];
    }

}
