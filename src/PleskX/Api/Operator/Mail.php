<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Mail as Struct;

class Mail extends \PleskX\Api\Operator
{

    /**
     * @param string $name
     * @param integer $siteId
     * @param boolean $mailbox
     * @param string $password
     * @return Struct\Info
     */
    public function create($name, $siteId, $mailbox = false, $password = '')
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('create');

        $filter = $info->addChild('filter');
        $filter->addChild('site-id', $siteId);
        $mailname = $filter->addChild('mailname');
        $mailname->addChild('name', $name);
        if ($mailbox) {
            $mailname->addChild('mailbox')->addChild('enabled', 'true');
        }
        if (!empty($password)) {
            $mailname->addChild('password')->addChild('value', $password);
        }

        $response = $this->_client->request($packet);
        return new Struct\Info($response->mailname);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @param integer $siteId
     * @return bool
     */
    public function delete($field, $value, $siteId)
    {
        $packet = $this->_client->getPacket();
        $filter = $packet->addChild($this->_wrapperTag)->addChild('remove')->addChild('filter');
        $filter->addChild('site-id', $siteId);
        $filter->addChild($field, $value);
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }

    /**
     * @return Struct\GeneralInfo[]
     */
    public function getAllBySiteId($siteId)
    {
        $response = $this->_get('get_info', 'site-id', $siteId);

        $items = [];
        if (isset($response->mail->get_info->result))
        {
            foreach ($response->mail->get_info->result as $mail)
            {
                if (isset($mail->mailname))
                {
                    $items[] = new Struct\GeneralInfo($mail->mailname);
                }
            }
        }

        return $items;
    }

    /**
     * @param $name
     * @param $siteId
     *
     * @return Struct\GeneralInfo
     */
    public function getById($name, $siteId)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get_info');

        $filterTag = $getTag->addChild('filter');

        if ($siteId)
        {
            $filterTag->addChild('site-id', $siteId);
        }

        if (!is_null($name)) {
            $filterTag->addChild('name', $name);
        }

        $getTag->addChild('forwarding');

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $items = [];
        if (isset($response->mail->get_info->result))
        {
            foreach ($response->mail->get_info->result as $mail)
            {
                if (isset($mail->mailname))
                {
                    $items[] = new Struct\GeneralInfo($mail->mailname);
                }
            }
        }



        return $items[0];
    }

    public function rename($newName, $oldName, $siteId)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('rename');

        $info->addChild('site-id', $siteId);
        $info->addChild('name', $oldName);
        $info->addChild('new-name', $newName);

        $response = $this->_client->request($packet);

        return $response;
    }

    public function update($values, $name, $siteId)
    {
        foreach ($values as $field => $value)
        {
            $this->_update($field, $value, $name, $siteId);
        }
    }
}
