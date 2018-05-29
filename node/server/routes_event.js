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
                        case 'intIDEvent':
                            tempEvent['id'] = docs[i]['intIDEvent']
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
        let eventID = c + 1;
        let title = req.body.title
        let email = req.body.email
        let start = new Date(req.body.start)
        let end = new Date(req.body.end)
        let allDay = req.body.allDay == 1 ? true : false;
        // let title = "new Event"
        // let email = "oasd"
        // let start = new Date("2018-05-02T06:00")
        // let end = new Date("2018-05-04T12:00")
        // let allDay = 0 == 1 ? true : false;

        let event = new Events({
            intIDEvent: eventID,
            chrTitle: title,
            chrFKUserEmail: email,
            dtdStart: start,
            dtdEnd: end,
            boolAllDay: allDay
        })
        res.json(event)
        // event.save(function(error) {
        //     if (error) {
        //         res.status(500)
        //         res.json(error)
        //     } else {
        //         res.json({
        //             'id': eventID
        //         });
        //     }
        // })
    })
})
// // Obtener un usuario por su nombre
// Router.get('/', function(req, res) {
//     let nombre = req.query.nombre
//     Users.findOne({
//         nombres: nombre
//     }).exec(function(err, doc) {
//         if (err) {
//             res.status(500)
//             res.json(err)
//         }
//         res.json(doc)
//     })
// })
// // Agregar a un usuario
// Router.post('/new', function(req, res) {
//     let user = new Users({
//         userId: Math.floor(Math.random() * 50),
//         nombres: req.body.nombres,
//         apellidos: req.body.apellidos,
//         edad: req.body.edad,
//         sexo: req.body.sexo,
//         estado: "Activo"
//     })
//     user.save(function(error) {
//         if (error) {
//             res.status(500)
//             res.json(error)
//         }
//         res.send("Registro guardado")
//     })
// })
// // Eliminar un usuario por su id
// Router.get('/delete/:id', function(req, res) {
//     let uid = req.params.id
//     Users.remove({
//         userId: uid
//     }, function(error) {
//         if (error) {
//             res.status(500)
//             res.json(error)
//         }
//         res.send("Registro eliminado")
//     })
// })

module.exports = Router;
