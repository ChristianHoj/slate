/* Schema generated at http://jsonschema.net/index.html */

tests["Status code is 200"] = responseCode.code === 200;

if (responseCode.code === 200) {
  var schema = {
    "$schema": "http://json-schema.org/draft-04/schema#",
    "id": "/",
    "type": "object",
    "properties": {
      "data": {
        "id": "data",
        "type": "object",
        "properties": {
          "type": {
            "id": "type",
            "type": "string"
          },
          "id": {
            "id": "id",
            "type": "string"
          },
          "attributes": {
            "id": "attributes",
            "type": "object",
            "properties": {
              "username": {
                "id": "username",
                "type": "string"
              },
              "first_name": {
                "id": "first_name",
                "type": "string"
              },
              "last_name": {
                "id": "last_name",
                "type": "string"
              },
              "email": {
                "id": "email",
                "type": "string"
              },
              "auth_token": {
                "id": "auth_token",
                "type": "string"
              }
            },
            "additionalProperties": false
          }
        },
        "additionalProperties": false
      }
    },
    "additionalProperties": false
  };

  var response = JSON.parse(responseBody);

  tests["Valid response format"] = tv4.validate(response, schema);
  if (tv4.error) {
    var message = tv4.error.message + (tv4.error.params? ". Parameter: " + tv4.error.params.key : "") + ". Path: " + tv4.error.dataPath;
    tests[message] = false;
  }

  postman.setEnvironmentVariable("admin_auth_token", response.data.attributes.auth_token);
  postman.setEnvironmentVariable("admin_id", response.data.id);
}
