<?php

namespace PleskX\Api\Struct\Mail;

/**
 * @author David Valenta <info@davidvalenta.cz>
 */
class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var integer */
    public $id;

    /** @var string */
    public $userGuid;

    /** @var string */
    public $name;

    /** @var string */
    public $antivir;

    /** @var string */
    public $description;

    /** @var array */
    public $forwarding;

    /** @var array */
    public $aliases;

    /** @var array */
    public $mailbox;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'user-guid',
            'antivir',
            'description'
        ]);

        $this->forwarding   = (array) $apiResponse->forwarding;
        $this->aliases      = (array) $apiResponse->alias;
        $this->mailbox      = (array) $apiResponse->mailbox;
    }
}
