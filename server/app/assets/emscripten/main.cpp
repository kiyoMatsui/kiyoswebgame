#include <SDL2/SDL.h>
#include <emscripten.h>

void loop_handler();
SDL_Window* window = NULL;
SDL_Surface* screenSurface = NULL;
SDL_Renderer *renderer = NULL;

int main( int argc, char* args[] ) {
    SDL_Init(SDL_INIT_VIDEO);
    SDL_CreateWindowAndRenderer(640, 480, 0, &window, &renderer);
	SDL_FillRect( screenSurface, NULL, SDL_MapRGB( screenSurface->format, 0x00, 0x00, 0x00 ) );
	emscripten_set_main_loop(loop_handler, -1, 0);	
	//SDL_Quit(); // Examples include function to call this on exit (SDL_DestroyWindow(window); seems not to be included) 
	return 0;
}

void loop_handler() {
	SDL_SetRenderDrawColor(renderer, 0, 0, 0, SDL_ALPHA_OPAQUE);
    SDL_RenderClear(renderer);
    SDL_SetRenderDrawColor(renderer, 100, 100, 100, SDL_ALPHA_OPAQUE);
    SDL_RenderDrawLine(renderer, 320, 200, 300, 240);
    SDL_RenderDrawLine(renderer, 300, 240, 340, 240);
    SDL_RenderDrawLine(renderer, 340, 240, 320, 200);			
    SDL_RenderPresent(renderer); 
}
