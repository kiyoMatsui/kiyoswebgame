cd ./www/dist
em++ ./../../app/assets/emscripten/main.cpp -O2 -s INITIAL_MEMORY=16777216 -s USE_SDL=2 -o ./index.js

