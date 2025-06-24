const mongoose = require("mongoose");

const tourSchema = new mongoose.Schema({
  title: String,
  description: String,
  price: Number,
  duration: String,
  image: String,
  createdAt: { type: Date, default: Date.now }
});

module.exports = mongoose.model("Tour", tourSchema);
