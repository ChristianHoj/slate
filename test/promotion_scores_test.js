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
        "type": "array",
        "items": {
          "id": "1",
          "type": "object",
          "properties": {
            "id": {
              "id": "id",
              "type": "string"
            },
            "type": {
              "id": "type",
              "type": "string"
            },
            "attributes": {
              "id": "attributes",
              "type": "object",
              "properties": {
                "title": {
                  "id": "title",
                  "type": "string"
                }
              },
              "additionalProperties": false
            }
          },
          "additionalProperties": false,
          "required": [
            "id",
            "type",
            "attributes"
          ]
        },
        "additionalItems": false,
        "required": [
          "1"
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
}
