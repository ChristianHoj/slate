# Events

## Create Event
```php
// Example of usage in WordPress
<?php
$userId = 1234656;
$token = "19c50e5260b97952970956";
$body_json = '
  "data": {
    "type": "event",
    "attributes": {
      "city": "København",
      "date": "2015-10-27T14:25:16+01:00",
      "providi_event_id": "876234"
    }
  }
}';
$response = wp_safe_remote_post( "providi.eu/API/create_event.php?token=" . $token . "&userId=" . $userId, array( 'body' => body_json ) );
$response = json_decode( $response['body'], true );
```

> Data sent in POST body of request for event creation

```json
{
  "data": {
    "type": "event",
    "attributes": {
      "city": "København",
      "date": "2015-10-27T14:25:16+01:00",
      "providi_event_id": "876234",
      "event_type": "startup"
    }
  }
}
```

> Response at successful creation of event

```json
{
  "data": {
    "type": "event",
    "id": "34",
    "attributes": {
      "providi_event_id": "876234"
    }
  }
}
```

This POST request is sent when a new meeting/event is created in the Wordpress based system. It shall be used to establish a link between the meetings in Wordpress and the attendee list in the backend database.

### HTTP Request
`POST http://providi.eu/API/create_event.php`


### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.

### POST Body
The following information is sent in the POST body when creating an event:

| Information            | Key in JSON request | Possible values
| ---------------------- | ------------------- | ---------------
| City for event         | `city`              |
| Event date             | `date`              |
| Event ID in new system | `providi_event_id`  |
| Event type             | `event_type`        | `intro`, `startup`, `sales_training`, `seminar`, `vs_club_evening`


## Add User To Event
```php
// Example of usage in WordPress
<?php
$userId = 1234656;
$token = "19c50e5260b97952970956";
$body_json = '
  "data": {
    "type": "event",
    "attributes": {
      "city": "København",
      "date": "2015-10-27T14:25:16+01:00",
      "providi_event_id": "876234"
    }
  }
}';
$response = wp_safe_remote_post( "providi.eu/API/create_event.php?token=" . $token . "&userId=" . $userId, array( 'body' => body_json ) );
$response = json_decode( $response['body'], true );
```

> Data that can be sent in POST body of request for attending event

```json
{
  "data": {
    "type": "event_attendee",
    "attributes": {
      "event_id": "34",
      "providi_event_id": "876234",
      "attendees": [
        {
          "type": "guest",
          "attributes": {
            "address": "Svenstrup Skolevej 24",
            "city": "Svenstrup",
            "zipcode": "9320",
            "email": "oluf@sand.dk",
            "guest_contact_via": "marketing",
            "host": "Egon Olsen",
            "invited_by": "Christian Høj",
            "name": "Oluf Sand",
            "phone": "12345678"
          }
        }
      ]
    }
  }
}
```

> Response at successful addition of attendee(s) to an event

```json
{
  "data": {
    "type": "event_attend",
    "id": "34",
    "attributes": {
      "providi_event_id": "876234",
      "status": "ok"
    }
  }
}
```

This POST request is sent when one or more persons are signed up for a meeting/event. It provides information to link the attendee(s) to a meeting/event created by a previous call to [`create_event`](#create-event).

Attendee information is always passed in the `attendees` array even if only one attendee is to be added.

The response for this POST request is simply a confirmation that the attendees were successfully added.

### HTTP Request
`POST http://providi.eu/API/event_attend.php`


### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token     | Required  | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId    | Required  | The id of the current user. Must be paired with `token`.

### POST Body
The following information can be included in the POST body when signing up users for an event:

| Information              | Key in JSON request | Required? | Possible values
| ------------------------ | ------------------- | --------- | ---------------
| Attendee array           | `attendees[]`       | required  |
| Attendee address         | `address`           | Optional  |
| Attendee city            | `city`              | Optional  |
| Attendee email           | `email`             | Required  |
| Attendee invited by      | `invited_by`        | Optional  |
| Attendee name            | `name`              | Required  |
| Attendee phone           | `phone`             | Optional  |
| Attendee type            | `type`              | Optional  | `guest`, `partner`, `executive`, `professional`
| Attendee zipcode         | `zipcode`           | Optional  |
| Event ID in "old" system | `event_id`          | Required  |
| Event ID in new system   | `providi_event_id`  | Required  |
| Guest greeted by         | `host`              | Optional  |
| Guest known via          | `guest_contact_via` | Optional  | `marketing`, `acquaintance`
