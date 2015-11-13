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
                "age": {
                  "id": "age",
                  "type": "string"
                },
                "email": {
                  "id": "email",
                  "type": "string"
                },
                "expected_earnings": {
                  "id": "expected_earnings",
                  "type": "string"
                },
                "lead_assigned_date": {
                  "id": "lead_assigned_date",
                  "type": "string"
                },
                "message": {
                  "id": "message",
                  "type": "string"
                },
                "name": {
                  "id": "name",
                  "type": "string"
                },
                "origin": {
                  "id": "origin",
                  "type": "string"
                },
                "phone": {
                  "id": "phone",
                  "type": "string"
                },
                "zipcode": {
                  "id": "zipcode",
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
