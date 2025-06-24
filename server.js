const express = require("express");
const mongoose = require("mongoose");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
const cors = require("cors");
require("dotenv").config();

const User = require("./models/User");
const Tour = require("./models/Tour");
const Booking = require("./models/Booking");

const app = express();
app.use(express.json());
app.use(cors());

mongoose
  .connect("mongodb://127.0.0.1:27017/touristBooking", { useNewUrlParser: true, useUnifiedTopology: true })
  .then(() => console.log("MongoDB Connected"))
  .catch(err => console.log(err));

const PORT = process.env.PORT || 5000;

// Register User
app.post("/register", async (req, res) => {
  const { name, email, password } = req.body;
  const hashedPassword = await bcrypt.hash(password, 10);
  try {
    const user = new User({ name, email, password: hashedPassword });
    await user.save();
    res.status(201).json({ message: "User Registered Successfully" });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
});

// Login User
app.post("/login", async (req, res) => {
  const { email, password } = req.body;
  const user = await User.findOne({ email });
  if (!user) return res.status(404).json({ error: "User not found" });

  const isMatch = await bcrypt.compare(password, user.password);
  if (!isMatch) return res.status(400).json({ error: "Invalid credentials" });

  const token = jwt.sign({ userId: user._id }, "secretKey", { expiresIn: "1h" });
  res.json({ token });
});

// Get Tours
app.get("/tours", async (req, res) => {
  const tours = await Tour.find();
  res.json(tours);
});

// Book a Tour
app.post("/book", async (req, res) => {
  const { userId, tourId } = req.body;
  try {
    const booking = new Booking({ user: userId, tour: tourId });
    await booking.save();
    res.json({ message: "Tour Booked Successfully" });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
});

app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
