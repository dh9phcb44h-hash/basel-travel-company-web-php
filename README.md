# Fly with Basel - Travel Company Website

## Overview
Fly with Basel is a travel company website developed for the COMP334 Web Application and Technologies course.

The project includes two versions:

1. **Assignment 1:** a static multi-page website built with semantic HTML5.
2. **Assignment 2:** a dynamic PHP and MySQL version that loads trips from a database, supports trip search and filtering, displays trip details dynamically, and processes bookings.

The goal of the project is to show the development path from a basic static HTML website into a database-driven web application.

## Project Versions

### Assignment 1 - Static HTML Website
The first version focuses on semantic HTML structure and multi-page website organization without CSS or JavaScript.

Pages include:
- Home
- Destinations
- Tour Packages
- Booking
- About
- Gallery
- FAQ

### Assignment 2 - Dynamic PHP Website
The second version upgrades the static website into a dynamic web application using PHP, MySQL, PDO, and prepared statements.

Main features include:
- Dynamic tour package listing
- Trip details loaded from the database
- Search and filter functionality
- Booking form connected to a selected trip
- Server-side validation
- Booking processing
- Seat availability updates
- Booking confirmation page

## Features

### Static HTML Features
- Multi-page website structure
- Semantic HTML5 elements
- Header, navigation, main content, sections, articles, aside, and footer
- Destination pages with images and captions
- Tour packages table
- Detailed package sections using `details` and `summary`
- Booking form using `fieldset`, `legend`, labels, text inputs, date inputs, select menus, radio buttons, checkboxes, and textarea
- FAQ page using collapsible questions
- Gallery page using `figure` and `figcaption`

### Dynamic PHP Features
- PDO database connection
- MySQL database integration
- Dynamic homepage trip highlights
- Dynamic tour package table
- Trip details page using `trip_id` from the URL
- Search trips by:
  - Destination
  - Date range
  - Price range
  - Minimum duration
- Booking form with preloaded trip information
- Server-side form validation
- Card number validation
- Cardholder name validation
- Expiry date validation
- Available seat validation
- Duplicate booking prevention using customer email and trip ID
- Booking insertion into the database
- Automatic available seat reduction after successful booking
- Transaction-based booking process
- Booking confirmation page

## Tech Stack
- HTML5
- PHP
- MySQL
- PDO
- phpMyAdmin
- XAMPP / Apache

## Database

Default database name:

```text
travel_company
