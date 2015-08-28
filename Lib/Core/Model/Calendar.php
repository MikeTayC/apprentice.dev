<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/5/15
 * Time: 1:22 PM
 */
class Core_Model_Calendar
{
    public $client_id;

    public $service;

    public $calendarId;

    public function __construct(Google_Client $googleClient)
    {
        //service account info
        $calendarConfig = Bootstrap::getCalendarConfig();
        $service_account_name = $calendarConfig['name'];
        $key_file_location = $calendarConfig['key_location'];
        $this->calendarId = $calendarConfig['id'];

        $googleClient->setApplicationName('Incubate Calendar');

        $this->service = new Google_Service_Calendar($googleClient);

        $key = file_get_contents($key_file_location);
        $cred = new Google_Auth_AssertionCredentials(
            $service_account_name,
            array('https://www.googleapis.com/auth/calendar'),
            $key
        );
        $googleClient->setAssertionCredentials($cred);
    }

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