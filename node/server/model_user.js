const mongoose = require('mongoose');
const Schema = mongoose.Schema;

let UserSchema = new Schema({
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
let UserModel = mongoose.model('user', UserSchema);
module.exports = UserModel;
