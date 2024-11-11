<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

trait Gender
{
    /**
     * @ORM\Column (name="gender", type="String" length=1)
     */
    private $gender;

    /**
     * @return mixed
     */
    public function getGender()
    {
        // Return value.
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return Gender
     */
    public function setGender($gender)
    {

        // Set value.
        $this->gender = $gender;
        // Return.
        return $this;
    }

    public function getGenderData()
    {

        switch (strtolower($this->getGender())) {
            case 'f':
                $genderKey = tc('Gender', 'F');
                $genderText = tc('Gender', 'Female');

                break;
            case 'm':
                $genderKey = tc('Gender', 'M');
                $genderText = tc('Gender', 'Male');

                break;
            case 'x':
            default:
                $genderKey = tc('Gender', 'X');
                $genderText = tc('Gender', 'Neuter');

                break;
        }

        $data = [
            'formatted' => [
                'key' => $genderKey,
                'text' => $genderText
            ]
        ];
        return $data;
    }
}
