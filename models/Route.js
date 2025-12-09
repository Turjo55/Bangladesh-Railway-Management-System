const mongoose = require('mongoose');

const RouteSchema = new mongoose.Schema({
    train_code: { type: String, required: true }, // Links to Train
    from_station: { type: String, required: true },
    to_station: { type: String, required: true },
    departure_time: { type: String, required: true }, // e.g., "07:00"
    arrival_time: { type: String, required: true },   // e.g., "12:30"
    fare_ac_b: { type: Number, default: 0 },
    fare_snigdha: { type: Number, default: 0 },
    fare_shovan: { type: Number, default: 0 },
    off_days: [String] // e.g., ['Friday']
});

module.exports = mongoose.model('Route', RouteSchema);
