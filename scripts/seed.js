const mongoose = require('mongoose');
const dotenv = require('dotenv');
const User = require('../models/User');
const Train = require('../models/Train');
const Route = require('../models/Route');

dotenv.config();

const MONGO_URI = process.env.MONGO_URI || 'mongodb+srv://BdRail:tanvir123@bdrail.jlvfhwx.mongodb.net/?appName=BdRail';

mongoose.connect(MONGO_URI)
    .then(() => seedData())
    .catch(err => console.error(err));

async function seedData() {
    console.log('Seeding Data...');

    try {
        // Clear existing
        await User.deleteMany({});
        await Train.deleteMany({});
        await Route.deleteMany({});

        // 1. Users
        await User.create({
            name: 'Super Admin',
            email: 'admin@br.gov.bd',
            password: 'admin123', // Will be hashed by pre-save hook
            role: 'Super Admin',
            phone: '01700000000'
        });

        await User.create({
            name: 'Passenger Demo',
            email: 'user@example.com',
            password: 'password123',
            role: 'Passenger',
            phone: '01900000000'
        });

        // 2. Trains
        const t1 = await Train.create({ name: 'Suborno Express', code: '701', type: 'Intercity' });
        const t2 = await Train.create({ name: 'Mohanagar Provati', code: '702', type: 'Intercity' });
        const t3 = await Train.create({ name: 'Parabat Express', code: '709', type: 'Intercity' });

        // 3. Routes
        await Route.create([
            { train_code: t1.code, from_station: 'Dhaka', to_station: 'Chattogram', departure_time: '07:00', arrival_time: '12:30', fare_ac_b: 1200, fare_snigdha: 800, fare_shovan: 450 },
            { train_code: t1.code, from_station: 'Chattogram', to_station: 'Dhaka', departure_time: '15:00', arrival_time: '20:30', fare_ac_b: 1200, fare_snigdha: 800, fare_shovan: 450 },

            { train_code: t2.code, from_station: 'Dhaka', to_station: 'Chattogram', departure_time: '07:45', arrival_time: '14:00', fare_ac_b: 1100, fare_snigdha: 750, fare_shovan: 400 },

            { train_code: t3.code, from_station: 'Dhaka', to_station: 'Sylhet', departure_time: '06:20', arrival_time: '13:00', fare_ac_b: 1000, fare_snigdha: 700, fare_shovan: 350 },
            { train_code: t3.code, from_station: 'Sylhet', to_station: 'Dhaka', departure_time: '15:45', arrival_time: '22:30', fare_ac_b: 1000, fare_snigdha: 700, fare_shovan: 350 }
        ]);

        console.log('Database Seeded Successfully!');
    } catch (error) {
        console.error('Error Seeding:', error);
    } finally {
        mongoose.connection.close();
    }
}
