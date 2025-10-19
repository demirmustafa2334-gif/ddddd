const mongoose = require('mongoose');

const citySchema = new mongoose.Schema({
  name: {
    type: String,
    required: true,
    unique: true,
    trim: true
  },
  slug: {
    type: String,
    required: true,
    unique: true,
    lowercase: true
  },
  description: {
    type: String,
    required: true
  },
  image: {
    type: String,
    required: true
  },
  coordinates: {
    lat: Number,
    lng: Number
  },
  population: Number,
  area: Number,
  establishedYear: Number,
  districts: [{
    type: mongoose.Schema.Types.ObjectId,
    ref: 'District'
  }],
  touristAttractions: [{
    name: String,
    description: String,
    image: String,
    type: {
      type: String,
      enum: ['historical', 'natural', 'cultural', 'religious', 'modern']
    }
  }],
  localCuisine: [{
    name: String,
    description: String,
    image: String,
    ingredients: [String],
    preparation: String
  }],
  specialFlavors: [{
    name: String,
    description: String,
    image: String,
    type: {
      type: String,
      enum: ['dessert', 'drink', 'snack', 'main_dish', 'appetizer']
    }
  }],
  seoKeywords: [String],
  metaDescription: String,
  isActive: {
    type: Boolean,
    default: true
  },
  createdAt: {
    type: Date,
    default: Date.now
  },
  updatedAt: {
    type: Date,
    default: Date.now
  }
});

citySchema.pre('save', function(next) {
  this.updatedAt = Date.now();
  next();
});

module.exports = mongoose.model('City', citySchema);