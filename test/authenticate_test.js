/* Schema generated at http://jsonschema.net/index.html */

tests["Status code is 200"] = responseCode.code === 200;

if (responseCode.code === 200) {
  var schema = {
    "$schema": "http://json-schema.org/draft-04/schema#",
    "id": "http://jsonschema.net",
    "type": "object",
    "properties": {
      "data": {
        "id": "http://jsonschema.net/data",
        "type": "object",
        "properties": {
          "type": {
            "id": "http://jsonschema.net/data/type",
            "type": "string"
          },
          "id": {
            "id": "http://jsonschema.net/data/id",
            "type": "string"
          },
          "attributes": {
            "id": "http://jsonschema.net/data/attributes",
            "type": "object",
            "properties": {
              "username": {
                "id": "http://jsonschema.net/data/attributes/username",
                "type": "string"
              },
              "first_name": {
                "id": "http://jsonschema.net/data/attributes/first_name",
                "type": "string"
              },
              "last_name": {
                "id": "http://jsonschema.net/data/attributes/last_name",
                "type": "string"
              },
              "email": {
                "id": "http://jsonschema.net/data/attributes/email",
                "type": "string"
              },
              "auth_token": {
                "id": "http://jsonschema.net/data/attributes/auth_token",
                "type": "string"
              }
            },
            "additionalProperties": false
          }
        },
        "additionalProperties": false,
        "required": [
          "type",
          "id",
          "attributes"
        ]
      }
    },
    "additionalProperties": false,
    "required": [
      "data"
    ]
  };

  var response = JSON.parse(responseBody);

  tests["Valid response format"] = tv4.validate(response, schema);
  if (tv4.error) {
    var message = tv4.error.message + (tv4.error.params? ". Parameter: " + tv4.error.params.key : "") + ". Path: " + tv4.error.dataPath;
    tests[message] = false;
  }

  postman.setEnvironmentVariable("auth_token", response.data.attributes.auth_token);
}
