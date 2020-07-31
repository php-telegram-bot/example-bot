<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * User "/date" command
 *
 * Shows the date and time of the location passed as the parameter.
 *
 * A Google API key is required for this command!
 * You can be set in your config.php file:
 * ['commands']['configs']['date'] => ['google_api_key' => 'your_google_api_key_here']
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\TelegramLog;

class DateCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'date';

    /**
     * @var string
     */
    protected $description = 'Show date/time by location';

    /**
     * @var string
     */
    protected $usage = '/date <location>';

    /**
     * @var string
     */
    protected $version = '1.5.0';

    /**
     * Guzzle Client object
     *
     * @var Client
     */
    private $client;

    /**
     * Base URI for Google Maps API
     *
     * @var string
     */
    private $google_api_base_uri = 'https://maps.googleapis.com/maps/api/';

    /**
     * The Google API Key from the command config
     *
     * @var string
     */
    private $google_api_key;

    /**
     * Date format
     *
     * @var string
     */
    private $date_format = 'd-m-Y H:i:s';

    /**
     * Get coordinates of passed location
     *
     * @param string $location
     *
     * @return array
     */
    private function getCoordinates($location): array
    {
        $path  = 'geocode/json';
        $query = ['address' => urlencode($location)];

        if ($this->google_api_key !== null) {
            $query['key'] = $this->google_api_key;
        }

        try {
            $response = $this->client->get($path, ['query' => $query]);
        } catch (RequestException $e) {
            TelegramLog::error($e->getMessage());

            return [];
        }

        if (!($data = $this->validateResponseData($response->getBody()))) {
            return [];
        }

        $result = $data['results'][0];
        $lat    = $result['geometry']['location']['lat'];
        $lng    = $result['geometry']['location']['lng'];
        $acc    = $result['geometry']['location_type'];
        $types  = $result['types'];

        return [$lat, $lng, $acc, $types];
    }

    /**
     * Get date for location passed via coordinates
     *
     * @param string $lat
     * @param string $lng
     *
     * @return array
     * @throws \Exception
     */
    private function getDate($lat, $lng): array
    {
        $path = 'timezone/json';

        $date_utc  = new \DateTimeImmutable(null, new \DateTimeZone('UTC'));
        $timestamp = $date_utc->format('U');

        $query = [
            'location'  => urlencode($lat) . ',' . urlencode($lng),
            'timestamp' => urlencode($timestamp),
        ];

        if ($this->google_api_key !== null) {
            $query['key'] = $this->google_api_key;
        }

        try {
            $response = $this->client->get($path, ['query' => $query]);
        } catch (RequestException $e) {
            TelegramLog::error($e->getMessage());

            return [];
        }

        if (!($data = $this->validateResponseData($response->getBody()))) {
            return [];
        }

        $local_time = $timestamp + $data['rawOffset'] + $data['dstOffset'];

        return [$local_time, $data['timeZoneId']];
    }

    /**
     * Evaluate the response data and see if the request was successful
     *
     * @param string $data
     *
     * @return array
     */
    private function validateResponseData($data): array
    {
        if (empty($data)) {
            return [];
        }

        $data = json_decode($data, true);
        if (empty($data)) {
            return [];
        }

        if (isset($data['status']) && $data['status'] !== 'OK') {
            return [];
        }

        return $data;
    }

    /**
     * Get formatted date at the passed location
     *
     * @param string $location
     *
     * @return string
     * @throws \Exception
     */
    private function getFormattedDate($location): string
    {
        if ($location === null || $location === '') {
            return 'The time in nowhere is never';
        }

        [$lat, $lng] = $this->getCoordinates($location);
        if (empty($lat) || empty($lng)) {
            return 'It seems that in "' . $location . '" they do not have a concept of time.';
        }

        [$local_time, $timezone_id] = $this->getDate($lat, $lng);

        $date_utc = new \DateTimeImmutable(gmdate('Y-m-d H:i:s', $local_time), new \DateTimeZone($timezone_id));

        return 'The local time in ' . $timezone_id . ' is: ' . $date_utc->format($this->date_format);
    }

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        // First we set up the necessary member variables.
        $this->client = new Client(['base_uri' => $this->google_api_base_uri]);
        if (($this->google_api_key = trim($this->getConfig('google_api_key'))) === '') {
            $this->google_api_key = null;
        }

        $message = $this->getMessage();

        $chat_id  = $message->getChat()->getId();
        $location = $message->getText(true);

        $text = 'You must specify location in format: /date <city>';

        if ($location !== '') {
            $text = $this->getFormattedDate($location);
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
