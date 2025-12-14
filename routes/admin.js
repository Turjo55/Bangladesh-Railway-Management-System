const express = require('express');
const router = express.Router();
const Booking = require('../models/Booking');
const Train = require('../models/Train');
const User = require('../models/User');
const Route = require('../models/Route');

// Middleware to ensure Admin Access
const protectAdmin = (req, res, next) => {
    if (!req.session.user || req.session.user.role !== 'Super Admin') {
        return res.redirect('/');
    }
    next();
};

// Admin Dashboard - Overview
router.get('/dashboard', protectAdmin, async (req, res) => {
    try {
        const totalBookings = await Booking.countDocuments();
        const totalTrains = await Train.countDocuments();
        const totalRevenue = await Booking.aggregate([
            { $group: { _id: null, total: { $sum: "$total_amount" } } }
        ]);

        const recentBookings = await Booking.find().sort({ createdAt: -1 }).limit(10);

        const fifteenMinutesAgo = new Date(Date.now() - 15 * 60 * 1000);
        const liveUsersCount = await User.countDocuments({ lastActive: { $gte: fifteenMinutesAgo } });

        res.render('admin_dashboard', {
            stats: {
                bookings: totalBookings,
                trains: totalTrains,
                revenue: totalRevenue[0] ? totalRevenue[0].total : 0,
            },
            recentBookings
        });
    } catch (err) {
        console.error(err);
        res.redirect('/');
    }
});

// Booking Manager
router.get('/bookings', protectAdmin, async (req, res) => {
    try {
        const bookings = await Booking.find().populate('user_id', 'name email').sort({ createdAt: -1 });
        res.render('admin/bookings', { bookings });
    } catch (err) {
        console.error(err);
        res.redirect('/admin/dashboard');
    }
});

// Route Manager
router.get('/routes', protectAdmin, async (req, res) => {
    try {
        const routes = await Route.find().sort({ train_code: 1 });
        res.render('admin/routes', { routes });
    } catch (err) {
        console.error(err);
        res.redirect('/admin/dashboard');
    }
});

// Add New Route
// Add New Route
router.post('/routes/add', protectAdmin, async (req, res) => {
    try {
        console.log('Adding Route:', req.body); // Debug log
        const { train_code, from_station, to_station, departure_time, arrival_time, fare_shovan } = req.body;

        // Basic Validation
        if (!train_code || !from_station || !to_station || !departure_time || !arrival_time) {
            console.error('Missing required fields');
            return res.redirect('/admin/routes?error=missing_fields');
        }

        const newRoute = new Route({
            train_code,
            from_station,
            to_station,
            departure_time,
            arrival_time,
            fare_shovan: parseFloat(fare_shovan) || 0 // Ensure number
        });

        await newRoute.save();
        console.log('Route added successfully');
        res.redirect('/admin/routes?success=route_added');
    } catch (err) {
        console.error('Error adding route:', err);
        res.redirect('/admin/routes?error=server_error');
    }
});

// Edit Route
router.post('/routes/edit/:id', protectAdmin, async (req, res) => {
    try {
        console.log('Editing Route ID:', req.params.id, 'Data:', req.body);
        const { train_code, from_station, to_station, departure_time, arrival_time, fare_shovan } = req.body;

        await Route.findByIdAndUpdate(req.params.id, {
            train_code,
            from_station,
            to_station,
            departure_time,
            arrival_time,
            fare_shovan: parseFloat(fare_shovan) || 0
        });

        res.redirect('/admin/routes?success=route_updated');
    } catch (err) {
        console.error('Error editing route:', err);
        res.redirect('/admin/routes?error=update_failed');
    }
});

// Delete Route
router.get('/routes/delete/:id', protectAdmin, async (req, res) => {
    try {
        console.log('Deleting Route ID:', req.params.id);
        await Route.findByIdAndDelete(req.params.id);
        res.redirect('/admin/routes?success=route_deleted');
    } catch (err) {
        console.error('Error deleting route:', err);
        res.redirect('/admin/routes?error=delete_failed');
    }
});


// User Manager
router.get('/users', protectAdmin, async (req, res) => {
    try {
        const users = await User.find().sort({ createdAt: -1 });
        res.render('admin/users', { users });
    } catch (err) {
        console.error(err);
        res.redirect('/admin/dashboard');
    }
});

// Reports
router.get('/reports', protectAdmin, async (req, res) => {
    res.render('admin/reports'); // Kept simple for now
});

// Settings
router.get('/settings', protectAdmin, (req, res) => res.render('admin/settings'));


module.exports = router;
