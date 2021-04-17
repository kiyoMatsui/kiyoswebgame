cd ./www/dist
em++ ./../../app/assets/emscripten/main.cpp -std=c++17 -pthread -O2 -gsource-map -s PTHREAD_POOL_SIZE=4 -s INITIAL_MEMORY=16777216 -s USE_SDL=2 -s USE_SDL_TTF=2 -o ./index.js --preload-file ./../../app/assets/emscripten/tp --source-map-base http://localhost:8080/ --emrun


