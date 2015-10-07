# Events

## Create Event
```php
// Example of usage in WordPress
<?php
$userId = 1234656;
$token = "19c50e5260b97952970956";
$body_json = ;
$response = wp_safe_remote_post( "providi.eu/API/create_event.php?token=" . $token . "&userId=" . $userId, array( 'body' => body_json ) );
$ext_auth = json_decode( $response['body'], true );
if( isset( $ext_auth['data'] ) ) {
  // External user exists, try to load the user info from the WordPress user table
  $userobj = new WP_User();
  $user = $userobj->get_data_by( 'email', $ext_auth['data']['attributes']['email'] );
  $user = new WP_User($user->ID); // Attempt to load up the user with that ID

  // ...
} else if( isset( $ext_auth['error'] ) ) {
  // User does not exist, show an error message
  $user = new WP_Error( 'denied', __("ERROR: User/pass bad") );
}
```

> Response data at successful event creation

```json
{
  "data": {
    "type": "user",
    "id": 123456,
    "attributes": {
      "providi_event_id": 876234
    }
  }
}
```

### HTTP Request
`POST https://providi.eu/API/create_event.php`


### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
token | Required | The authentication token for the current user. Obtained from calling [`authenticate`](#authentication).
userId | Required | The id of the current user. Must be paired with `token`.

### POST Body
The following information is sent in the POST body when creating an event:

| Information                 | Key in JSON response   |
| --------------------------- | ---------------------- |
| Address                     | `address`              |
| City                        | `city`                 |
| Company Name                | `company_name`         |
| Country                     | `country`              |
