<?php

namespace CoreBundle\Notification\OneSignal\Request;

use CoreBundle\Notification\OneSignal\Response\PushResponse;
use CoreBundle\Notification\OneSignal\OneSignalRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PushRequest extends OneSignalRequest
{
    /**
     * @var $client
     */
    protected $client;

    /**
     * @var string
    */
    protected $apiKey;

    /**
     * @var string
    */
    protected $appId;

    /**
     * @var string
    */
    protected $message;

    /**
     * @var array
    */
    protected $segments;

    /**
     * @var \DateTime
    */
    protected $deliveryTime;

    /**
     * PushRequest constructor.
     *
     * @param Client $client
     * @param string $apiKey
     * @param string $appId
     */
    public function __construct(Client $client, $apiKey, $appId)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->appId = $appId;
    }

    /**
     * Send Push
     *
     * @return mixed
     */
    public function send()
    {
        $data = [
            'contents' => [
                'en' => $this->getMessage()
            ],
            'included_segments' => $this->getSegments()
        ];

        if($this->getDeliveryTime()) {
            $data['send_after'] = $this->getDeliveryTime();
        }

        $data = $this->resolve($data);
        $allData = [
            'headers' => [
                'Authorization' => 'Basic ' . $this->apiKey,
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            'json' => $data,
        ];
        $response = $this->client->request('POST', '/api/v1/notifications', $allData);
        return new PushResponse($response);
    }

    protected function resolve(array $data)
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setDefined('contents')
            ->setAllowedTypes('contents', 'array')
            ->setDefined('headings')
            ->setAllowedTypes('headings', 'array')
            ->setDefined('isIos')
            ->setAllowedTypes('isIos', 'bool')
            ->setDefined('isAndroid')
            ->setAllowedTypes('isAndroid', 'bool')
            ->setDefined('isWP')
            ->setAllowedTypes('isWP', 'bool')
            ->setDefined('isAdm')
            ->setAllowedTypes('isAdm', 'bool')
            ->setDefined('isChrome')
            ->setAllowedTypes('isChrome', 'bool')
            ->setDefined('isChromeWeb')
            ->setAllowedTypes('isChromeWeb', 'bool')
            ->setDefined('isSafari')
            ->setAllowedTypes('isSafari', 'bool')
            ->setDefined('isAnyWeb')
            ->setAllowedTypes('isAnyWeb', 'bool')
            ->setDefined('included_segments')
            ->setAllowedTypes('included_segments', 'array')
            ->setDefined('excluded_segments')
            ->setAllowedTypes('excluded_segments', 'array')
            ->setDefined('include_player_ids')
            ->setAllowedTypes('include_player_ids', 'array')
            ->setDefined('include_ios_tokens')
            ->setAllowedTypes('include_ios_tokens', 'array')
            ->setDefined('include_android_reg_ids')
            ->setAllowedTypes('include_android_reg_ids', 'array')
            ->setDefined('include_wp_uris')
            ->setAllowedTypes('include_wp_uris', 'array')
            ->setDefined('include_wp_wns_uris')
            ->setAllowedTypes('include_wp_wns_uris', 'array')
            ->setDefined('include_amazon_reg_ids')
            ->setAllowedTypes('include_amazon_reg_ids', 'array')
            ->setDefined('include_chrome_reg_ids')
            ->setAllowedTypes('include_chrome_reg_ids', 'array')
            ->setDefined('include_chrome_web_reg_ids')
            ->setAllowedTypes('include_chrome_web_reg_ids', 'array')
            ->setDefined('app_ids')
            ->setAllowedTypes('app_ids', 'array')
            ->setDefined('filters')
            ->setAllowedTypes('filters', 'array')
            ->setNormalizer('filters', function (Options $options, array $value) {
                $filters = [];
                foreach ($value as $filter) {
                    if (isset($filter['field'])) {
                        $filters[] = $filter;
                    } elseif (isset($filter['operator'])) {
                        $filters[] = ['operator' => 'OR'];
                    }
                }
                return $filters;
            })
            ->setDefined('tags')
            ->setAllowedTypes('tags', 'array')
            ->setNormalizer('tags', function (Options $options, array $value) {
                $tags = [];
                foreach ($value as $tag) {
                    if (isset($tag['key'], $tag['relation'], $tag['value'])) {
                        $tags[] = [
                            'key' => (string) $tag['key'],
                            'relation' => (string) $tag['relation'],
                            'value' => (string) $tag['value'],
                        ];
                    } elseif (isset($tag['operator'])) {
                        $tags[] = ['operator' => 'OR'];
                    }
                }
                return $tags;
            })
            ->setDefined('ios_badgeType')
            ->setAllowedTypes('ios_badgeType', 'string')
            ->setAllowedValues('ios_badgeType', ['None', 'SetTo', 'Increase'])
            ->setDefined('ios_badgeCount')
            ->setAllowedTypes('ios_badgeCount', 'int')
            ->setDefined('ios_sound')
            ->setAllowedTypes('ios_sound', 'string')
            ->setDefined('android_sound')
            ->setAllowedTypes('android_sound', 'string')
            ->setDefined('adm_sound')
            ->setAllowedTypes('adm_sound', 'string')
            ->setDefined('wp_sound')
            ->setAllowedTypes('wp_sound', 'string')
            ->setDefined('wp_wns_sound')
            ->setAllowedTypes('wp_wns_sound', 'string')
            ->setDefined('data')
            ->setAllowedTypes('data', 'array')
            ->setDefined('buttons')
            ->setAllowedTypes('buttons', 'array')
            ->setNormalizer('buttons', function (Options $options, array $value) {
                $buttons = [];
                foreach ($value as $button) {
                    if (!isset($button['text'])) {
                        continue;
                    }
                    $buttons[] = [
                        'id' => (isset($button['id']) ? $button['id'] : mt_rand()),
                        'text' => $button['text'],
                        'icon' => (isset($button['icon']) ? $button['icon'] : null),
                    ];
                }
                return $buttons;
            })
            ->setDefined('small_icon')
            ->setAllowedTypes('small_icon', 'string')
            ->setDefined('large_icon')
            ->setAllowedTypes('large_icon', 'string')
            ->setDefined('big_picture')
            ->setAllowedTypes('big_picture', 'string')
            ->setDefined('adm_small_icon')
            ->setAllowedTypes('adm_small_icon', 'string')
            ->setDefined('adm_large_icon')
            ->setAllowedTypes('adm_large_icon', 'string')
            ->setDefined('adm_big_picture')
            ->setAllowedTypes('adm_big_picture', 'string')
            ->setDefined('web_buttons')
            ->setAllowedTypes('web_buttons', 'array')
            ->setAllowedValues('web_buttons', function ($buttons) {
                $required_keys = ['id', 'text', 'icon', 'url'];
                foreach ($buttons as $button) {
                    if (!is_array($button)) {
                        return false;
                    }
                    if (count(array_intersect_key(array_flip($required_keys), $button)) != count($required_keys)) {
                        return false;
                    }
                }
                return true;
            })
            ->setDefined('chrome_icon')
            ->setAllowedTypes('chrome_icon', 'string')
            ->setDefined('chrome_big_picture')
            ->setAllowedTypes('chrome_big_picture', 'string')
            ->setDefined('chrome_web_icon')
            ->setAllowedTypes('chrome_web_icon', 'string')
            ->setDefined('firefox_icon')
            ->setAllowedTypes('firefox_icon', 'string')
            ->setDefined('url')
            ->setAllowedTypes('url', 'string')
            ->setAllowedValues('url', function ($value) {
                return (bool) filter_var($value, FILTER_VALIDATE_URL);
            })
            ->setDefined('send_after')
            ->setAllowedTypes('send_after', '\DateTime')
            ->setNormalizer('send_after', function (Options $options, \DateTime $value) {
                //Fri May 02 2014 00:00:00 GMT-0700 (PDT)
                return $value->format('D M d Y H:i:s eO (T)');
            })
            ->setDefined('delayed_option')
            ->setAllowedTypes('delayed_option', 'string')
            ->setAllowedValues('delayed_option', ['timezone', 'last-active'])
            ->setDefined('delivery_time_of_day')
            ->setAllowedTypes('delivery_time_of_day', '\DateTime')
            ->setNormalizer('delivery_time_of_day', function (Options $options, \DateTime $value) {
                return $value->format('g:iA');
            })
            ->setDefined('android_led_color')
            ->setAllowedTypes('android_led_color', 'string')
            ->setDefined('android_accent_color')
            ->setAllowedTypes('android_accent_color', 'string')
            ->setDefined('android_visibility')
            ->setAllowedTypes('android_visibility', 'int')
            ->setAllowedValues('android_visibility', [-1, 0, 1])
            ->setDefined('content_available')
            ->setAllowedTypes('content_available', 'bool')
            ->setDefined('android_background_data')
            ->setAllowedTypes('android_background_data', 'bool')
            ->setDefined('amazon_background_data')
            ->setAllowedTypes('amazon_background_data', 'bool')
            ->setDefined('template_id')
            ->setAllowedTypes('template_id', 'string')
            ->setDefined('android_group')
            ->setAllowedTypes('android_group', 'string')
            ->setDefined('android_group_message')
            ->setAllowedTypes('android_group_message', 'array')
            ->setDefined('adm_group')
            ->setAllowedTypes('adm_group', 'string')
            ->setDefined('adm_group_message')
            ->setAllowedTypes('adm_group_message', 'array')
            ->setDefined('ttl')
            ->setAllowedTypes('ttl', 'int')
            ->setDefined('priority')
            ->setAllowedTypes('priority', 'int')
            ->setDefault('app_id', $this->appId);
        return $resolver->resolve($data);
    }

    /**
     * @param string $message
     * @return PushRequest
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param array $segments
     * @return PushRequest
     */
    public function setSegments(array $segments)
    {
        $this->segments = $segments;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * @param \DateTime $deliveryTime
     * @return PushRequest
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }
}
