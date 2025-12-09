const mongoose = require('mongoose');

const BookingSchema = new mongoose.Schema({
    pnr: { type: String, required: true, unique: true },
    user_id: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true },
    route_id: { type: mongoose.Schema.Types.ObjectId, ref: 'Route', required: true },

    // Snapshot of journey details at time of booking
    train_name: String,
    train_code: String,
    from_station: String,
    to_station: String,
    journey_date: String, // YYYY-MM-DD

    seats: String, // "A-1, B-2"
    total_amount: Number,
    payment_status: { type: String, default: 'Paid' },
    status: { type: String, default: 'Confirmed' },
    createdAt: { type: Date, default: Date.now }
});

module.exports = mongoose.model('Booking', BookingSchema);
