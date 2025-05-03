/*const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"homeapp":{"uri":"\/","methods":["GET","HEAD"]},"homeapp2":{"uri":"home2","methods":["GET","HEAD"]},"rewind":{"uri":"rewind","methods":["GET","HEAD"]},"create":{"uri":"create","methods":["GET","HEAD"]},"checkresult":{"uri":"checkresult\/{idGuess}","methods":["GET","HEAD"],"parameters":["idGuess"]},"change.locale":{"uri":"change-locale","methods":["POST"]},"login":{"uri":"login","methods":["GET","HEAD"]},"logout":{"uri":"logout","methods":["POST"]},"register":{"uri":"register","methods":["GET","HEAD"]},"password.request":{"uri":"password\/reset","methods":["GET","HEAD"]},"password.email":{"uri":"password\/email","methods":["POST"]},"password.reset":{"uri":"password\/reset\/{token}","methods":["GET","HEAD"],"parameters":["token"]},"password.update":{"uri":"password\/reset","methods":["POST"]},"password.confirm":{"uri":"password\/confirm","methods":["GET","HEAD"]},"home":{"uri":"admin\/home","methods":["GET","HEAD"]},"player":{"uri":"admin\/player","methods":["GET","HEAD"]},"newplayer":{"uri":"admin\/player","methods":["POST"]},"editplayer":{"uri":"admin\/player\/edit\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"updateplayer":{"uri":"admin\/player\/edit","methods":["POST"]},"searchplayer":{"uri":"player\/search","methods":["POST"]},"newclub":{"uri":"player\/club\/{idPlayer}","methods":["POST"],"parameters":["idPlayer"]},"newtitle":{"uri":"player\/title\/{idPlayer}","methods":["POST"],"parameters":["idPlayer"]},"updatetitles":{"uri":"player\/titlesupdate\/{idPlayer}","methods":["POST"],"parameters":["idPlayer"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy }; */

export default {
    "url": "https://footble.io",
    "routes": {
        "homeapp": {
            "uri": "",
            "methods": ["GET"]
        },
        "playgame": {
            "uri": "play",
            "methods": ["GET"]
        },
        "rewind": {
            "uri": "rewind",
            "methods": ["GET"]
        },
        "create": {
            "uri": "create",
            "methods": ["GET"]
        },
        "privacy": {
            "uri": "privacy",
            "methods": ["GET"]
        }
    }
};
