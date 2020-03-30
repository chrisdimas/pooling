## Short description

A plugin to extend WordPress functionalities to enable people create voluntary clusters and help each other during the covid19 pandemic.

see https://covid19help.eu

## Description

Pooling matches all users within 1km radius, displays a google map with their markers and their respective needs and/or offers. Each user can have both needs and offers. 
A user need to register for an account and fill their address (Google Maps Places API for autocomplete) and he/she will be verified by an sms code (via Twilio API). Afterwards, the user is redirected to the map where he/she can find other users within 1km radius and send an aid offer (as "aid provider") to them. 
The recipient of the offer (the "aid recipient") can accept (or not - ignore) the offer. Once the offer is acccepted *Pooling* will send, each party, an email with their personal information so they can communicate by phone and verify these information or exchange information about the needs of the "aid recipient".

The aid provider can withdraw the offer within 1hrs (be default) and the corresponding email notifications will be triggered.
Both users can report each other over the aid offer.

The addresses shouldn't be accurate but they should be close enough to the resindent address in order to match the users efficiently.

## Contributions

All developers & translators are needed and they are welcome to discuss about the project and create a PR. See our Todo list below.
All contributors will be credited both at this repo and https://covid19help.eu credits page as well.

## Todo

1. User reports
2. Simple Live chat between the two users of an aid offer.
3. Image upload on user profiles (it adds some extra security for the aid recipient).
4. User should be able to write a text description accompaning their needs (not the offer).

Users' personal data will not be published until each party of the aid "transaction" have accepted the aid offer. 

## License

**Pooling** is released under the [GNU AGPLv3](https://github.com/indie-systems/pooling/blob/master/LICENSE.txt).