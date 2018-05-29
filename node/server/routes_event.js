const mongoose = require('mongoose');
const Router = require('express').Router();
const Events = require('./model_event.js');
//Obtener todos los usuarios
Router.get('/all/:email', function(req, res) {
    let email = req.params.email;
    Events.find({
        chrFKUserEmail: email
    }, 'intIDEvent chrTitle dtdStart dtdEnd boolAllDay').exec(function(err, docs) {
        if (err) {
            res.status(500);
            res.json(err);
        } else {
            var response = [];
            for (var i = 0; i < docs.length; i++) {
                var tempEvent = {};
                for (var key in docs[i]) {
                    switch (key) {
                        case '_id':
                            tempEvent['id'] = docs[i]['_id'].toString()
                            tempEvent['idtype'] = typeof(docs[i]['_id'])
                            break;
                        case 'chrTitle':
                            tempEvent['title'] = docs[i]['chrTitle']
                            break;
                        case 'dtdStart':
                            tempEvent['start'] = docs[i]['dtdStart']
                            break;
                        case 'dtdEnd':
                            tempEvent['end'] = docs[i]['dtdEnd']
                            break;
                        case 'boolAllDay':
                            tempEvent['allDay'] = docs[i]['boolAllDay']
                            break;
                        default:
                    }
                }
                response.push(tempEvent);
            }
            res.json(response);
        }
    });
});
// Agregar evento
Router.post('/new', function(req, res) {
    Events.count({}, function(err, c) {
        let title = req.body.title
        let email = req.body.email
        let start = new Date(req.body.start)
        let end = req.body.allDay == 1 ? new Date(req.body.start) : new Date(req.body.end);
        let allDay = req.body.allDay == 1 ? true : false;

        let event = new Events({
            chrTitle: title,
            chrFKUserEmail: email,
            dtdStart: start,
            dtdEnd: end,
            boolAllDay: allDay
        })
        event.save(function(error, room) {
            if (error) {
                res.status(500)
                res.json(error)
            } else {
                res.json({
                    'id': room.id
                });
            }
        })
    })
})
// Actualizar eventos
Router.post('/update', function(req, res) {
    let eventID = req.body.id
    let email = req.body.email
    let title = req.body.title
    let start = new Date(req.body.start)
    var end = req.body.allDay == 1 ? new Date(req.body.start) : new Date(req.body.end);
    let allDay = req.body.allDay == 1 ? true : false;

    if (!allDay && (!Object.prototype.toString.call(end) === "[object Date]" || isNaN(end) || req.body.end.indexOf('Invalid' != -1))) {
        end = start;
    }
    Events.update({
        _id: mongoose.Types.ObjectId(eventID),
        chrFKUserEmail: email
    }, {
        chrTitle: title,
        dtdStart: start,
        dtdEnd: end,
        boolAllDay: allDay
    }, function(err) {
        if (err) {
            res.status(500)
            res.json(err)
        } else {
            res.send("Evento actualizado.")
        }
    });
})
// Eliminar evento por su id/email
Router.post('/delete', function(req, res) {
    let eventID = req.body.id
    let email = req.body.email
    Events.remove({
        _id: mongoose.Types.ObjectId(eventID),
        chrFKUserEmail: email
    }, function(error) {
        if (error) {
            res.status(500)
            res.json(error)
        } else {
            res.send("Evento eliminado.")
        }
    })
})

module.exports = Router;
