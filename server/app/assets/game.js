        var Module = {
            canvas: (function() {
                return canvas = document.getElementById('canvas')
            })()
        };
        var script = document.createElement('script');
        script.src = "dist/index.js";
        document.body.appendChild(script);