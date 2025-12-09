const mongoose = require('mongoose');

const TrainSchema = new mongoose.Schema({
    name: { type: String, required: true }, // e.g., Suborno Express
    code: { type: String, required: true, unique: true }, // e.g., 701
    type: { type: String, default: 'Intercity' },
    facilities: [String] // e.g. ['AC', 'Food']
});

module.exports = mongoose.model('Train', TrainSchema);
