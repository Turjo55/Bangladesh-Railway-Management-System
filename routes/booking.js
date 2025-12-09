const express = require('express');
const router = express.Router();
const Route = require('../models/Route');
const Train = require('../models/Train');
const Booking = require('../models/Booking');

// Middleware to ensure login
const protect = (req, res, next) => {
    if (!req.session.user) return res.redirect('/auth/login');
    next();
};

// Seat Selection
router.get('/seat_selection', async (req, res) => {
    const { route_id, date } = req.query;
    try {
        const route = await Route.findById(route_id);
        if (!route) return res.redirect('/');

        const train = await Train.findOne({ code: route.train_code });

        res.render('seat_selection', {
            route,
            train,
            date
        });
    } catch (err) {
        res.redirect('/');
    }
});

// Booking Summary & Payment
router.get('/summary', protect, async (req, res) => {
    const { route_id, date, seats, fare } = req.query;
    try {
        const route = await Route.findById(route_id);
        const train = await Train.findOne({ code: route.train_code });

        res.render('booking_summary', {
            route, train, date, seats, totalFare: fare,
            user: req.session.user
        });
    } catch (err) {
        res.redirect('/');
    }
});

// Process Booking
router.post('/process', protect, async (req, res) => {
    const { route_id, date, seats, amount, gateway } = req.body;
    try {
        const route = await Route.findById(route_id);
        const train = await Train.findOne({ code: route.train_code });

        const pnr = 'BR' + Math.floor(100000 + Math.random() * 900000);

        const newBooking = await Booking.create({
            pnr,
            user_id: req.session.user.id,
            route_id: route._id,
            train_name: train.name,
            train_code: train.code,
            from_station: route.from_station,
            to_station: route.to_station,
            journey_date: date,
            seats,
            total_amount: amount,
            payment_status: 'Paid' // Simulated
        });

        res.redirect(`/booking/confirmation?pnr=${pnr}`);
    } catch (err) {
        console.error(err);
        res.redirect('/');
    }
});

// Confirmation
router.get('/confirmation', protect, async (req, res) => {
    try {
        const booking = await Booking.findOne({ pnr: req.query.pnr });
        if (!booking) return res.redirect('/');
        res.render('ticket_confirmation', { booking });
    } catch (err) {
        res.redirect('/');
    }
});

// My Bookings
router.get('/my_bookings', protect, async (req, res) => {
    try {
        const bookings = await Booking.find({ user_id: req.session.user.id }).sort({ createdAt: -1 });
        res.render('passenger_dashboard', { bookings });
    } catch (err) {
        res.redirect('/');
    }
});

module.exports = router;
