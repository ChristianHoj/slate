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
              "meetings": {
                "id": "meetings",
                "type": "array",
                "items": {
                  "id": "1",
                  "type": "object",
                  "properties": {
                    "city": {
                      "id": "city",
                      "type": "string"
                    },
                    "country": {
                      "id": "country",
                      "type": "string"
                    },
                    "date": {
                      "id": "date",
                      "type": "string"
                    },
                    "meeting_type": {
                      "id": "meeting_type",
                      "type": "string"
                    },
                    "attendees": {
                      "id": "attendees",
                      "type": "array",
                      "items": {
                        "id": "1",
                        "type": "object",
                        "properties": {
                          "name": {
                            "id": "name",
                            "type": "string"
                          }
                        },
                        "additionalProperties": false
                      },
                      "additionalItems": false
                    }
                  },
                  "additionalProperties": false
                },
                "additionalItems": false
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
}
