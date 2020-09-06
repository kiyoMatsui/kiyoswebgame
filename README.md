# kiyoswebgame
A web app using docker, nette and emscripten.

## Build
All you need to run a dev build is docker and a spare email...
Modify email.neon with your email and password, you might have to configure your email to allow "insecure" apps to use it.
This is usually quite easy and your email will tell you this when webgame uses the email.
Then to build the application run: "docker-compose -f ./docker-compose-build.yml up"
To host the https dev build run: "docker-compose up"

Thats it... if you want to modify this for production build the webpack bundles rather than hosting the webpack dev server. Remove the redirect in the apache virtualhost. Turn off XDebug and use real certs. Also, turn off Tracy, this should get things started.

Webgame is still a work in progress but aims are to keep docker as the only dependency.

## Licence
Apache 2