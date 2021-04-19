const cors = require('cors');
const express = require('express');
const request = require('request');
const app = express();
app.use(cors({origin: false}));
app.options('*', cors({origin: false}));
const http = require('http');
const server = http.createServer(app);
const io = require('socket.io')(server,{
    cors: {
      origin: '*',
    }
});

const ACTION = {
    GET : "get",
    INSTANCE : "instance",
    COLLECT : "collect",
    KILL : "kill",
    HANDLE : "handle",
    RELEASE : "release",
    UPDATE : "update",
};
app.get('/', (req, res) => {
    res.send('<h1>Hello world</h1>');
});

io.on('connection', (socket) => {
    socket.on('request', (msg) => {
        io.emit("response","ok");
        console.log(msg);
        var action = msg.action;
        var id = msg.id;
        socket.broadcast.emit("response",{
            action:action,
            id:id,
        });
    });
    socket.broadcast.emit("response","broadcast.connect");
    console.log("connect");
});

server.listen(3031, () => {
    console.log('listening on *:3031');
});
