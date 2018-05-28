const Router = require('express').Router();
const Users = require('./model_user.js');

// Valida login
Router.post('/', function(req, res) {
    let email = req.params.user
    let password = req.params.pass
    Users.findOne({
        chrEmail: email,
        chePassword: password
    }).exec(function(err, user) {
        if (err) {
            res.status(500);
            res.json(err);
        }
        if(user === null) {
            res.send("nop");
        } else {
            res.send('Password and username do not match.');
        }
    });
})

module.exports = Router;
