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
                "address": {
                  "id": "address",
                  "type": "string"
                },
                "customerID": {
                  "id": "customerID",
                  "type": "string"
                },
                "kalorie_login": {
                  "id": "kalorie_login",
                  "type": "string"
                },
                "latest_login": {
                  "id": "latest_login",
                  "type": "string"
                },
                "meal_price": {
                  "id": "meal_price",
                  "type": "string"
                },
                "name": {
                  "id": "name",
                  "type": "string"
                },
                "new_target_link": {
                  "id": "new_target_link",
                  "type": "string"
                },
                "old_target_link": {
                  "id": "old_target_link",
                  "type": "string"
                },
                "phone": {
                  "id": "phone",
                  "type": "string"
                },
                "points": {
                  "id": "points",
                  "type": "string"
                },
                "sign_up_date": {
                  "id": "sign_up_date",
                  "type": "string"
                },
                "unpaid_months": {
                  "id": "unpaid_months",
                  "type": "string"
                },
                "username": {
                  "id": "username",
                  "type": "string"
                },
                "vs_login_link": {
                  "id": "vs_login_link",
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
    var message = tv4.error.message + (tv4.error.params ? ". Parameter: " + tv4.error.params.key : "") + ". Path: " + tv4.error.dataPath;
    tests[message] = false;
  }
}
