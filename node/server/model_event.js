const mongoose = require('mongoose');
const Schema = mongoose.Schema;

let EventsSchema = new Schema({
    intIDEvent: {
        type: Number,
        required: true,
        unique: true
    },
    chrTitle: {
        type: String,
        required: true
    },
    chrFKUserEmail: {
        type: String,
        required: true
    },
    dtdStart: {
        type: Date,
        required: true
    },
    dtdEnd: {
        type: Date
    },
    boolAllDay: {
        type: Boolean,
        required: true
    }
})
let EventsModel = mongoose.model('events', EventsSchema);
module.exports = EventsModel;
