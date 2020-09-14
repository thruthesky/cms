var socketIo = require('socket.io');
var http = require('http');
var server = http.createServer(function onRequest(req, res) {
    res.write("reload.js");
});
server.listen(12345);
io = socketIo(server);
io.on('connection', function (client) {
    client.on('event', function (data) {});
    client.on('disconnect', function () {});
});
var chokidar = require('chokidar');
chokidar.watch('.', { ignored: [ '.idea', 'node_modules', 'vendor', '**/vendor', '**/scss', '**/.git', 'package*', 'live-reload.js', '**/*.mp4', '**/*.mp3', '**/*.jpg'] } ).on('all', function (event, path) {
    console.log(event, path, ' at ' + ( new Date ).toLocaleString());
    io.emit('reload', { code: 'reload' });
});
