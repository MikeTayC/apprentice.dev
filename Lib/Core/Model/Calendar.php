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
        $client_id = '433657982361-40ctf1na0vahl950epgi1nffesb020kp.apps.googleusercontent.com';
        $service_account_name = 'michael.tay@blueacorn.com';
        $key_file_location = '/home/mike/sites/apprentice.dev/Lib/Core/Model/apprentice-9faa49d689bf.p12';

        //calendar id
        $this->calendarId = 'blueacorn.com_j2d2fnauptd0u7mgnrf0u5e0ss@group.calendar.google.com';

        $googleClient->setApplicationName('Incubate Calendar');

        $this->service = new Google_Service_Calendar($googleClient);

        $key = file_get_contents($key_file_location);
        $cred = new Google_Auth_AssertionCredentials(
            $service_account_name,
            array('https://www.googleapis.com/auth/calendar'),
            $key
        );

        $googleClient->setAssertionCredentials($cred);

//        $cals = $this->service->calendarList->listCalendarList();
//        echo '<pre>';
//        print_r($cals);
//        echo '</pre>';
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

        $createdEvent = $this->service->events->insert($this->calendarId, $event, $optArgs);


    }
}