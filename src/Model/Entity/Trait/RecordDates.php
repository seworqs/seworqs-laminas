<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Entity\Trait;

use Carbon\Carbon;
use Seworqs\Laminas\Helper\EntityDataHelper;

trait RecordDates
{
    /**
     * @ORM\Column (name="created_on", type="utcdatetime", nullable=true)
     */
    private $CreatedOn;
    /**
     * @ORM\Column (name="updated_on", type="utcdatetime", nullable=true)
     */
    private $UpdatedOn;
    /**
     * @ORM\Column (name="deleted_on", type="utcdatetime", nullable=true)
     */
    private $DeletedOn;

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        // Return value.
        return $this->CreatedOn;
    }

    /**
     * @param mixed $CreatedOn
     * @return RecordDates
     */
    public function setCreatedOn($CreatedOn)
    {

        // Set value.
        $this->CreatedOn = $CreatedOn;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedOn()
    {
        // Return value.
        return $this->UpdatedOn;
    }

    /**
     * @param mixed $UpdatedOn
     * @return RecordDates
     */
    public function setUpdatedOn($UpdatedOn)
    {

        // Set value.
        $this->UpdatedOn = $UpdatedOn;
        // Return.
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeletedOn()
    {
        // Return value.
        return $this->DeletedOn;
    }

    /**
     * @param mixed $DeletedOn
     * @return RecordDates
     */
    public function setDeletedOn($DeletedOn)
    {

        // Set value.
        $this->DeletedOn = $DeletedOn;
        // Return.
        return $this;
    }

    public function getRecordDatesData($tz = 'UTC', $locale = 'nl_NL')
    {
        $result = [
            'created' => EntityDataHelper::getDateTimeData($this->getCreatedOn()),
            'updated' => EntityDataHelper::getDateTimeData($this->getUpdatedOn()),
            'deleted' => EntityDataHelper::getDateTimeData($this->getDeletedOn())
        ];

        return $result;
    }
}
