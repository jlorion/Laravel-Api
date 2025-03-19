db = db.getSiblingDB("laravel"); // Switch to "laravel" database

// Create a collection
db.createCollection("test");

// Insert a sample document (optional)
db.test.insertOne({
    name: "John Doe",
    email: "john@example.com",
    created_at: new Date()
});