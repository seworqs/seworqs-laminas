<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Form;

use Laminas\Form\Element\Csrf;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Seworqs\Laminas\Service\CurrentUserService;

abstract class BaseForm extends Form
{
    protected $currentUser;

    public function __construct(CurrentUserService $currentUser, $options = [])
    {

        $name = $options['name'];
        unset($options['name']);

        parent::__construct($name, $options);

        // Defaults.
        $this->setAttribute('id', $name);
        $this->setAttribute('class', 'form');
        $this->setAttribute('method', 'POST');

        // Attributes.
        foreach ($options as $key => $value) {
            $this->setAttribute($key, $value);
        }

        $this->currentUser = $currentUser;
    }

    abstract function addInputElements();
    abstract public function addInputFilters();

    public function prepare()
    {
        parent::prepare();
        $this->prepareForDisplay();
    }

    //    public function setCurrentUser(CurrentUserService $currentUser) {
    //        $this->currentUser = $currentUser;
    //        return $this;
    //    }
    //
    //    public function getCurrentUser(): CurrentUserService {
    //        return $this->currentUser;
    //    }

    public function convertDateTimeFields($fromTimezone, $toTimezone)
    {
        $convertedData = [];
        foreach ($this->getElements() as $element) {
            if ($element instanceof \Laminas\Form\Element\DateTimeLocal) {
                $value = $element->getValue();
                if ($value) {
                    $dateTime = new \DateTime($value, new \DateTimeZone($fromTimezone));
                    $dateTime->setTimezone(new \DateTimeZone($toTimezone));
                    $element->setValue($dateTime->format('Y-m-d\TH:i:s'));

                    // Collect the changed data.
                    $convertedData[$element->getName()] = $element->getValue();
                }
            }
        }

        // Check for converted data.
        if (count($convertedData) > 0) {

            // Set converted data to form.
            $this->setData($convertedData);
        }
    }


    /**
     * Prepare the DateTimeLocal fields from UTC (from database) to user timezone.
     *
     * @return void
     */
    public function prepareForDisplay()
    {
        $this->convertDateTimeFields('UTC', $this->currentUser->getTimezone());
    }

    /**
     * Prepare the DateTimeLocal fields from user timezone to UTC (database).
     * @return void
     */
    public function prepareForStorage()
    {
        $this->convertDateTimeFields($this->currentUser->getTimezone(), 'UTC');
    }
}
