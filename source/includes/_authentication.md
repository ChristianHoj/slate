# Authentication

```php
// Example of usage in WordPress
<?php
$username = "chrhoj";
$password = "asd";
$response = wp_safe_remote_post( "providi.eu/API/authenticate.php", array( 'body' => array( 'user' => $username, 'pass' => $password ) ) );
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

> Response data at successful authentication

```json
{
  "data": {
    "type": "user",
    "id": "123456",
    "attributes": {
      "username": "chrhoj",
      "first_name": "Christian",
      "last_name": "Høj",
      "email": "cbh@nightcirque.com",
      "auth_token": "iuTaoiy78wksjERk9qe"
    }
  }
}
```

Authenticate a user against the Providi database. Upon successful authentication the response includes a `auth_token` that must be used in all subsequent calls to the API.

The `country` parameter is not required for this endpoint since users have globally unique ids.

### HTTP Request
`POST http://providi.eu/API/authenticate.php`

### Request Parameters
Parameter | Required? | Description
--------- | --------- | -----------
user | Required | The username to be authenticated
pass | Required | The password to use for authentication

### Response fields
The following information is included in the response:

| Information           | Key in JSON response |
| --------------------- | -------------------- |
| Authentication token  | `auth_token`         |
| Email                 | `email`              |
| First name            | `first_name`         |
| Last name             | `last_name`          |
| User id               | `id`                 |
| User name             | `username`           |
