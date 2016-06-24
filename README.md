FORMAT: 1A
HOST: http://polls.apiblueprint.org/

# Pigeon Post

[todo] a little story about pigeons

## Email Capsules [/email]

### Release a Bird [POST]

| PROPERTY                         | TYPE             | REQUIRED | LIMITS | DESCRIPTION                                                                                                               |
|----------------------------------|------------------|----------|--------|---------------------------------------------------------------------------------------------------------------------------|
| **to**                           | Array of Objects | YES      |        | Our prime offering. These birds are big and strong, fed the freshest grains twice a day by beautiful women.               |
| &nbsp;&nbsp;&nbsp;&nbsp;address  | String           | YES      |        | These birds will fly true to this destination.                                                                            |
| &nbsp;&nbsp;&nbsp;&nbsp;name     | String           | NO       |        | The name you'd like on the capsule, written in our best calligraphy.                                                      |
| **cc**                           | Array of Objects | NO       |        | A selection of smaller birds, kept in the back of the coop.                                                               |
| &nbsp;&nbsp;&nbsp;&nbsp;address  | String           | YES      |        | We'll pass this along, hopefully the birds don't forget.                                                                  |
| &nbsp;&nbsp;&nbsp;&nbsp;name     | String           | NO       |        | The name you'd like on the capsule, scribbled with a ball point pen.                                                      |
| **bcc**                          | Array of Objects | NO       |        | If we told you about these birds, we'd have to kill you.                                                                  |
| &nbsp;&nbsp;&nbsp;&nbsp;address  | String           | YES      |        | We promise, our birds will never divulge this secret.                                                                     |
| &nbsp;&nbsp;&nbsp;&nbsp;name     | String           | NO       |        | The name you'd like on the capsule, written on flash paper with invisible ink.                                            |
| **subject**                      | String           | NO       |        | Be sure to grab their attention, otherwise they might just eat the bird and not read the message!                         |
| **message**                      | HTML/Text        | NO       |        | The body of your message, make it a good one.                                                                             |
| **attachments**                  | Array of Objects | NO       | 10MB   | Our birds can carry a lot of weight, but don't weigh them down too heavily or the recipient might just send them back.    |
| &nbsp;&nbsp;&nbsp;&nbsp;content  | String           | YES      |        | The attachment itself. (Pigeon Post Corp. takes no responsibility to damage sustained during transit)                     |
| &nbsp;&nbsp;&nbsp;&nbsp;type     | String           | YES      |        | The meme type of the attachment. Normally something unpronounceable by our pigeons like "application/pdf" or "image/jpg"  |
| &nbsp;&nbsp;&nbsp;&nbsp;filename | String           | YES      |        | We'll stencil this to the side of the attachment with care.                                                                    |


+ Request (application/json)

        {
            "to": [
                {
                    "address": "amanda.rvx@gmail.com",
                    "name": "Amanda Carter"
                },
                {
                    "address": "alberto.rvx@gmail.com",
                    "name": "Alberto Siza"
                }
            ],
            "cc": [],
            "bcc": [],
            "reply_to": {
                "address": "wyland.rvx@gmail.com",
                "name": "Wyland Wu"
            },
            "subject": "Take good care of this pigeon",
            "message": "",
            "attachments": []
        }

+ Response 200
+ Response 500 