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
          "id": "0",
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
                "email": {
                  "id": "email",
                  "type": "string"
                },
                "lead_assigned_date": {
                  "id": "lead_assigned_date",
                  "type": "string"
                },
                "lead_type": {
                  "id": "lead_type",
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
                "serious": {
                  "id": "serious",
                  "type": "string"
                },
                "status": {
                  "id": "status",
                  "type": "string",
                  "enum": [
                    null, "not_contacted", "signed_up", "not_available", "no_show", "no_money", "not_interested", "non_existing", "never_asked_for_contact", "no"
                  ]
                },
                "weight_loss": {
                  "id": "weight_loss",
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
          "0"
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
    var message = tv4.error.message + (tv4.error.params ? ". Parameter: " + tv4.error.params.key : "") + ". Path: " + tv4.error.dataPath;
    tests[message] = false;
  }

  postman.setEnvironmentVariable("lead_id", response.data[0].id);
}
