const http = require('http');
const path = require('path');
// const RoutingEvents = require('./routes_event.js');
const RoutingLogin = require('./routes_login.js');
// const RoutingUsers = require('./routes_user.js');
const express = require('express');
const bodyParser = require('body-parser');
const mongoose = require('mongoose');

const PORT = 3000;
const app = express();

const Server = http.createServer(app);

mongoose.connect('mongodb://localhost/calendar')

app.use(express.static('client'));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({
    extended: true
}));
// app.use('/events', RoutingEvents);
app.use('/login', RoutingLogin);
// app.use('/login', RoutingUsers);

Server.listen(PORT, function() {
    console.log('Server is listening on port ' + PORT);
})
