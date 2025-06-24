const mongoose = require("mongoose");

const guideSchema = new mongoose.Schema({
  name: String,
  experience: Number,
  languages: [String],
  rating: { type: Number, default: 5 },
  createdAt: { type: Date, default: Date.now }
});

module.exports = mongoose.model("Guide", guideSchema);
