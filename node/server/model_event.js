const mongoose = require('mongoose');
const Schema = mongoose.Schema;

let EventSchema = new Schema({
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
    chrStartTime: {
        type: String,
        required: true
    },
    chrEndTime: {
        type: String,
        required: true
    },
    intAllDay: {
        type: Boolean,
        required: true
    }
})
let EventModel = mongoose.model('event', EventSchema);
module.exports = EventModel;
