const Router = require('express').Router();
const Users = require('./model_user.js');

// Agregar a un usuario
Router.post('/new', function(req, res) {
    let user = new Users({
        chrEmail: req.params.email,
        chrFullName: req.params.fullname,
        chrPassword: req.params.password,
        dtdBirth: req.params.datebirth
    })
    user.save(function(error) {
        if (error) {
            res.status(500)
            res.json(error)
        }
        res.send("Registro guardado")
    })
})

module.exports = Router;
