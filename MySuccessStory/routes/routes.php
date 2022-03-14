<?php

#region Use
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerUsers;
use MySuccessStory\controllers\ControllerNotes;
#endregion

#region Connection
SimpleRouter::get("/api/token", [ControllerUsers::class, "token"]); // Create a token
SimpleRouter::post("/api/test", [ControllerUsers::class, "test"]); // Create a token
SimpleRouter::get("/api/profile", [ControllerUsers::class, "profile"]); // Show the profile of a user
SimpleRouter::put("/api/register", [ControllerUsers::class, "register"]); // Create a new account
//SimpleRouter::get("/api/login", [ControllerUsers::class, "token" /* "login" */]); // Allow the user to log in to his account
#endregion

#region CRUD Notes
SimpleRouter::get("/api/notes", [ControllerNotes::class, "read"]); // Read a single note (idNote) Read every note if no parameter is given
SimpleRouter::post("/api/notes", [ControllerNotes::class, "create"]); // Add a new note to the database (note, semester, idUser, idSubject)
SimpleRouter::patch("/api/notes", [ControllerNotes::class, "update"]); // Change the value of a note (idNote, note, semester, idUser)
SimpleRouter::delete("/api/notes", [ControllerNotes::class, "delete"]); // Delete a note (idNote)
#endregion

#region CRUD Users
SimpleRouter::get("/api/users", [ControllerUsers::class, "read"]); // Read (idUser)
SimpleRouter::post("/api/users", [ControllerUsers::class, "create"]); // Create (email, password, firstName, lastName)
SimpleRouter::patch("/api/users", [ControllerUsers::class, "update"]); // Update (idUser, email, password, firstname, lastName)
SimpleRouter::delete("/api/users", [ControllerUsers::class, "delete"]); // Delete (idUser)
#endregion

#region Subjects
SimpleRouter::get("/api/subjects", [ControllerUsers::class, "token"]); // Show all the subjects
#endregion