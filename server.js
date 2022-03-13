const express = require('express');
const app = express();
const server = require('http').createServer(app);
const io = require('socket.io')(server, {cors: {origin:'*'}});

io.on('connection', function (socket) {
    console.log('connection');
    
    socket.on('sendMessage', function (message) {
        console.log(message)
    });
    
    socket.on('disconnect', function () {
        console.log('disconnect')
    })
});

server.listen(3000, function () {
    console.log('Server Is UP')
});