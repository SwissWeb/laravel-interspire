<?php namespace Aglipanci\Interspire;


class Interspire
{

    /**
     * @var string
     */
    private $api_url;

    /**
     * @var string
     */
    private $api_user;

    /**
     * @var string
     */
    private $api_token;

    public function __construct()
    {
        $this->api_url = env('INTERSPIRE_URL', config('interspire.url'));
        $this->api_user = env('INTERSPIRE_USER', config('interspire.api_user'));
        $this->api_token = env('INTERSPIRE_TOKEN', config('interspire.api_token'));

        if (is_null($this->api_url) || is_null($this->api_user) || is_null($this->api_token))
            abort(403, 'Some Interspire credentials missing');
    }

    /**
 * POST data to API
 * @param $xml
 * @return string
 */
    private function postData($xml)
    {
        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $result = curl_exec($ch);

        if ($result === false || is_null($result) || empty($result))
            return null;

        $xml_doc = simplexml_load_string($result);

        /** @noinspection PhpUndefinedFieldInspection */
        if ($xml_doc->status == 'SUCCESS')
            return 'OK';

        /** @noinspection PhpUndefinedFieldInspection */
        return $xml_doc->errormessage->__toString();
    }

    /**
     * Add subscriber to list
     * @param $name
     * @param $surname
     * @param $email
     * @param $list_id
     * @return string
     */
    public function addSubscriberToList($name, $surname, $email, $list_id)
    {

        $xml = '<xmlrequest>
		<username>' . $this->api_user . '</username>
		<usertoken>' . $this->api_token . '</usertoken>
		<requesttype>subscribers</requesttype>
		<requestmethod>AddSubscriberToList</requestmethod>
		<details>
		<emailaddress>' . $email . '</emailaddress>
		<mailinglist>' . $list_id . '</mailinglist>
		<format>html</format>
		<confirmed>yes</confirmed>
		<customfields>
		<item>
		<fieldid>2</fieldid>
		<value>' . $name . '</value>
		</item>
		<item>
		<fieldid>3</fieldid>
		<value>' . $surname . '</value>
		</item>
		</customfields>
		</details> 
		</xmlrequest>
		';

        return $this->postData($xml);
    }

    /**
     * Ban a subscriber
     * @param $email
     * @param int|string $list_id
     * @return string
     */
    public function addBannedSubscriber($email, $list_id = 'global')
    {

        $xml = '<xmlrequest>
		<username>' . $this->api_user . '</username>
		<usertoken>' . $this->api_token . '</usertoken>
		<requesttype>subscribers</requesttype>
		<requestmethod>AddBannedSubscriber</requestmethod>
		<details>
		<emailaddress>' . $email . '</emailaddress>
		<listid>' . $list_id . '</listid>
		</details>
		</xmlrequest>';

        return $this->postData($xml);
    }

    /**
     * Delete a subscriber
     * @param $email
     * @param int $list_id
     * @return string
     */
    public function deleteSubscriber($email, $list_id = 1)
    {

        $xml = '<xmlrequest>
		<username>' . $this->api_user . '</username>
		<usertoken>' . $this->api_token . '</usertoken>
		<requesttype>subscribers</requesttype>
		<requestmethod>DeleteSubscriber</requestmethod>
		<details>
		<emailaddress>' . $email . '</emailaddress>
		<list>' . $list_id . '</list>
		</details>
		</xmlrequest>';

        return $this->postData($xml);
    }

    /**
     * Check if user is on list
     * @param $email
     * @param $list_id
     * @return string
     */
    public function isSubscriberOnList($email, $list_id)
    {
        $xml = '<xmlrequest>
		<username>' . $this->api_user . '</username>
		<usertoken>' . $this->api_token . '</usertoken>
		<requesttype>subscribers</requesttype>
		<requestmethod>IsSubscriberOnList</requestmethod>
		<details>
		<Email>' . $email . '</Email>
		<List>' . $list_id . '</List>
		</details>
		</xmlrequest>';

        return $this->postData($xml);
    }

    /**
     * @param $email
     * @param int $list_id
     * @return string
     */
    public function bounceSubscriber($email, $list_id = 1)
    {
        $xml = '<xmlrequest>
		<username>' . $this->api_user . '</username>
		<usertoken>' . $this->api_token . '</usertoken>
		<requesttype>subscribers</requesttype>
		<requestmethod>BounceSubscriber</requestmethod>
		<details>
		<emailaddress>' . $email . '</emailaddress>
		<listid>' . $list_id . '</listid>
		</details>
		</xmlrequest>';

        return $this->postData($xml);
    }


    public function unsubscribeSubscriber($email, $list_id = 1)
    {
        $xml = '<xmlrequest>
		<username>' . $this->api_user . '</username>
		<usertoken>' . $this->api_token . '</usertoken>
		<requesttype>subscribers</requesttype>
		<requestmethod>UnsubscribeSubscriber</requestmethod>
		<details>
		<emailaddress>' . $email . '</emailaddress>
		<listid>' . $list_id . '</listid>
		</details>
		</xmlrequest>';

        return $this->postData($xml);
    }

    /**
     * @param $email
     * @param string $list_ids
     * @return array|null
     */
    public function getAllListsForEmailAddress($email, $list_ids = null)
    {
        if (is_null($list_ids))
            $list_ids = implode(',', $this->getLists());

        $xml = '<xmlrequest>
		<username>' . $this->api_user . '</username>
		<usertoken>' . $this->api_token . '</usertoken>
		<requesttype>subscribers</requesttype>
		<requestmethod>GetAllListsForEmailAddress</requestmethod>
		<details>
		<email>' . $email . '</email>
		<listids>' . $list_ids . '</listids>
		</details>
		</xmlrequest>';

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $result = curl_exec($ch);

        if ($result === false || is_null($result) || empty($result))
            return null;

        $xml_doc = simplexml_load_string($result);

        if((string) $xml_doc->status == 'FAILED')
            return null;

        $list_ids = [];
        /** @noinspection PhpUndefinedFieldInspection */
        foreach ($xml_doc->data->item as $data)
        {
            $list_ids[] = (string) $data->listid;
        }

        return (array) $list_ids;
    }


    /**
     * Get All available lists
     *
     * @return null|array
     */
    public function getLists()
    {
        $xml = '<xmlrequest>
		<username>' . $this->api_user . '</username>
		<usertoken>' . $this->api_token . '</usertoken>
		<requesttype>lists</requesttype>
		<requestmethod>GetLists</requestmethod>
		<details>
		</details>
		</xmlrequest>';

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $result = curl_exec($ch);

        if ($result === false || is_null($result) || empty($result))
            return null;

        $response = (array) simplexml_load_string($result);

        if ($response['status'] == 'SUCCESS')
        {
            $listids = [];
            foreach($response['data'] as $list)
            {
                $listids[] = (string) $list->listid;
            }

            return (array) $listids;
        }

        return null;
    }
}
