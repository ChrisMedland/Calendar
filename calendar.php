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

	$event = new Google_Event(); 
	$event->setSummary($title);
	$event->setDescription($desc);
	$event->setLocation($locat);
	$start = new Google_EventDateTime(); 
	$start->setDateTime($from);
	$event->setStart($start);
	$end = new Google_EventDateTime(); 
	$end->setDateTime($until);
	$event->setEnd($end);
	$attendee1 = new Google_EventAttendee(); 
	$attendee1->setEmail('christophermedland@googlemail.com'); 


	$createdEvent = $cal->events->insert('primary', $event);

	echo $createdEvent->getId(); 
}
}

print "<h1>Add Item To Calendar</h1>";

print "<div style='width:100%;height:100%;'><div style='width:550px;height:320px;text-align:center;margin:200px auto;background-color:#f0ffff;border:1px solid black;'><div style='padding:50px;'><table>";
print "<form method='POST'>";
print "<tr><td>Title: </td><td><input type='text' name='title' id='title'></td></tr>";
print "<tr><td>Desc: </td><td><input type='text' name='desc' id='desc'></td></tr>";
print "<tr><td>Location: </td><td><input type='text' name='locat' id='locat'></td></tr>";
print "<tr><td colspan='2'>Date Format: YYYY-MM-DDTHH:MM:SS(.000)-00:00 (.000 = Optional)</td></tr>";
print "<tr><td>From: </td><td><input type='text' name='from' id='from'></td></tr>";
print "<tr><td>To: </td><td><input type='text' name='until' id='until'></td></tr>";
print "<tr><td> <input type='checkbox'></td><td>repeat every: </td><td><input type= 'text' name='recurrence' id='recurrence></td></tr>";
print "<tr><td>Select Calendar: </td><td><select name='calID' id='chooseCal'>";
for($i=0; $i < count($calList['items']); $i++)
{
print "<option value=".$calList['items'][$i]['id']."'>".$calList['items'][$i]['summary']."</option>";

}
print "</select></br></td></tr>";
print "<input type='hidden' name='action' value='addCalEvent'>";
print "<tr><td colspan='2'><div style='text-align:right;'><input type='submit' value='Submit'></div></td></tr>";
print "</form>";
print "</table></div></div></div>";

print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";

$_SESSION['token'] = $client->getAccessToken();
}
else
{
$authUrl = $client->createAuthUrl();
print "<a class='login' href='$authUrl'>Create a Google Calendar Event?</a>";

}
?>