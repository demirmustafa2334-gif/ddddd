const mongoose = require('mongoose');

const districtSchema = new mongoose.Schema({
  name: {
    type: String,
    required: true,
    trim: true
  },
  slug: {
    type: String,
    required: true,
    lowercase: true
  },
  city: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'City',
    required: true
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
  touristAttractions: [{
    name: String,
    description: String,
    image: String,
    type: {
      type: String,
      enum: ['historical', 'natural', 'cultural', 'religious', 'modern']
    },
    address: String,
    openingHours: String,
    entryFee: String
  }],
  localCuisine: [{
    name: String,
    description: String,
    image: String,
    ingredients: [String],
    preparation: String,
    restaurantRecommendations: [String]
  }],
  specialFlavors: [{
    name: String,
    description: String,
    image: String,
    type: {
      type: String,
      enum: ['dessert', 'drink', 'snack', 'main_dish', 'appetizer']
    },
    whereToFind: [String]
  }],
  culturalHighlights: [{
    title: String,
    description: String,
    image: String,
    type: {
      type: String,
      enum: ['festival', 'tradition', 'art', 'music', 'dance', 'craft']
    }
  }],
  accommodation: [{
    name: String,
    type: {
      type: String,
      enum: ['hotel', 'pension', 'apartment', 'villa', 'camping']
    },
    description: String,
    priceRange: String,
    contact: String
  }],
  transportation: {
    howToReach: String,
    localTransport: String,
    carRental: String
  },
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

districtSchema.pre('save', function(next) {
  this.updatedAt = Date.now();
  next();
});

// Compound index for city and slug uniqueness
districtSchema.index({ city: 1, slug: 1 }, { unique: true });

module.exports = mongoose.model('District', districtSchema);