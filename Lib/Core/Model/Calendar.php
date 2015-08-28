<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/5/15
 * Time: 1:22 PM
 *
 * Core_Model_Calendar interface for interacting with google calendar service account
 **/
class Core_Model_Calendar
{
    /** @var Google_Service_Calendar Google calendar service client */
    public $service;

    /** @var  calendar id used to manipulate set calendar */
    public $calendarId;

    /**
     * Requires a google client to use google servie calendar class
     * Needs various configuration information from config
     *
     * @param Google_Client $googleClient
     **/
    public function __construct(Google_Client $googleClient)
    {
        /** @var service account information from configuration $calendarConfig */
        $calendarConfig = Bootstrap::getCalendarConfig();

        /** @var $service_account_name name of service account */
        $service_account_name = $calendarConfig['name'];

        /** @var  $key_file_location p12 file location, acts as a key */
        $key_file_location = $calendarConfig['key_location'];

        /** @var  calendarId id of calendar to be accessed */
        $this->calendarId = $calendarConfig['id'];

        /** Sets application name */
        $googleClient->setApplicationName('Incubate Calendar');

        /** @var  service sets reference to google servicde calendar*/
        $this->service = new Google_Service_Calendar($googleClient);

        /** Creditionals for service account are set */
        $key = file_get_contents($key_file_location);
        $cred = new Google_Auth_AssertionCredentials(
            $service_account_name,
            array('https://www.googleapis.com/auth/calendar'),
            $key
        );

        /** google client asserts the calendar service account credentials */
        $googleClient->setAssertionCredentials($cred);
    }

    /**
     * Uses google calendar service to fire an event and set it on the calendar
     *
     * @param $title
     * @param $description
     * @param $eventStart
     * @param $eventEnd
     * @param array $inviteList
     **/
    public function setEvent($title, $description, $eventStart, $eventEnd, $inviteList = array())
    {
        foreach ($inviteList as $email) {
            if($email) {
                $attendee = new Google_Service_Calendar_EventAttendee();
                $attendee->setEmail($email);
                $attendeeArray[] = $attendee;
            }
        }
        $event = new Google_Service_Calendar_Event();
        $event->setSummary($title);
        $event->setDescription($description);

        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDateTime($eventStart);
        $event->setStart($start);

        $end = new Google_Service_Calendar_EventDateTime();
        $end->setDateTime($eventEnd);
        $event->setEnd($end);

        $event->setAttendees($attendeeArray);

        $optArgs = array(
          "sendNotifications" => true
        );

        $this->service->events->insert($this->calendarId, $event, $optArgs);
    }
}