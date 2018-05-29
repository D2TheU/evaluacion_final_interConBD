const mongoose = require('mongoose');
const Schema = mongoose.Schema;

let UsersSchema = new Schema({
    chrEmail: {
        type: String,
        required: true,
        unique: true
    },
    chrFullName: {
        type: String,
        required: true
    },
    chrPassword: {
        type: String,
        required: true
    },
    dtdBirth: {
        type: Date,
        required: true
    }
})
let UsersModel = mongoose.model('users', UsersSchema);
module.exports = UsersModel;
