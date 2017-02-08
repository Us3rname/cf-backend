<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 14-1-2017
 * Time: 20:59
 */

namespace AppBundle\Commands;


use AppBundle\ValueObjects\ContactDetails;

class AddAffiliateCommand extends AbstractCommand
{

    private $affiliateName;

    /** @var  ContactDetails $contactDetails */
    private $contactDetails;

    public function __construct($userId, $name, ContactDetails $contactDetails)
    {
        $this->contactDetails = $contactDetails;
        $this->affiliateName = $name;

        parent::__construct($userId);
    }

    public function getName()
    {
        return get_class($this);
    }
}