<?php

require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_CalendarService.php';


session_start();

$client = new Google_Client();
$client->setApplicationName("Calendar");
$client->setClientId('461669633703-e95jqgc7d3slibnc5p5bl3f22gpps859.apps.googleusercontent.com');
$client->setClientSecret('RVPm39mG6Xyd7gzKqwN0jJR3');
$client->setRedirectUri("http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF']);
$client->setDeveloperKey('AIzaSyBOdDo1nREb-T3_08hRPCnh1PWKXw6FrgA');

$cal = new Google_CalendarService($client);
if (isset($_GET['logout']))
{
unset($_SESSION['token']);
}

if (isset($_GET['code']))
	{
	$client->authenticate($_GET['code']);
	$_SESSION['token'] = $client->getAccessToken();
	header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
	}

if (isset($_SESSION['token']))
	{
	$client->setAccessToken($_SESSION['token']);
	}

if ($client->getAccessToken())
	{
	$calList = $cal->calendarList->listCalendarList();


if(isset($_POST['action']))
	{
	$action = $_POST['action'];
	if($action == "addCalEvent")
	{
	$title = $_POST["title"];
	$desc = $_POST["desc"];
	$calID = $_POST["calID"];
	$locat = $_POST["locat"];
	$from = $_POST["from"];
	$until = $_POST["until"];
	$recurrence = $_POST["recurrence"];


	$dateS = DateTime::createFromFormat("d/m/Y", $_GET['StartDate']);
	$dateF = DateTime::createFromFormat("d/m/Y", $_GET['FinishDate']);



	$event = new Google_Event(); 
	$event->setSummary($title);
	$event->setDescription($desc);
	$event->setLocation($locat);
	$start = new Google_EventDateTime(); 
	$start->setDateTime($dateS->format("Y-m-d"));
	$start->setTimeZone('Europe/London')
	$event->setStart($start);
	$end = new Google_EventDateTime(); 
	$end->setDateTime($dateF->format("Y-m-d"));
	$end->setTimeZone('Europe/London')
	$event->setEnd($end);
	$event-> setRecurrence(array('RRULE:FREQ=$frequency;UNTIL=$EndRec'))


	$attendee1 = new Google_EventAttendee(); 
	$attendee1->setEmail('christophermedland@googlemail.com'); 


	$recurringEvent = $service->events->insert('primary', $event);

	echo $recurringEvent->getId(); 
}
}



$_SESSION['token'] = $client->getAccessToken();
}
else
{
$authUrl = $client->createAuthUrl();
print "<a class='login' href='$authUrl'>Create a Google Calendar Event?</a>";

}
?>