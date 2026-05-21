var cachename = 'my-cache-v1';
var cachefiles = [

];

self.addEventListener('install', function (event) {
    
    event.waitUntil(
     caches.open(cachename).then(function (cache) {
         //   console.log("[service worker] Caching cachefiles");
         return cache.addAll(cachefiles);
     })
   )
});

self.addEventListener('activate', function (event) {
    
    event.waitUntil(
    caches.keys().then(function (cachenames) {
        return Promise.all(cachenames.map(function (thiscachenames) {
            if (thiscachenames != cachenames) {
                // console.log("[service worker] Remove Caching cachefiles", thiscachenames);
                return caches.delete(thiscachenames);
            }
        }))
    })
    )
});


/* request is being made */
self.addEventListener("fetch", function (event) {
    //To tell browser to evaluate the result of event
    event.respondWith(
		caches.match(event.request) //To match current request with cached request it
		.then(function (response) {
		    //If response found return it, else fetch again.
		    return response || fetch(event.request);
		    // console.log("fetch -->", response);
		})
		.catch(function (error) {
		    // console.error("Error: ", error);
		})
  );
});