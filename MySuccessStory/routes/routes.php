<?php

#region Use
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\Controllers\ControllerUsers;
use MySuccessStory\controllers\ControllerNotes;
use MySuccessStory\controllers\ControllerSubject;

#endregion

#region CRUD Notes
SimpleRouter::post("/api/notes", [ControllerNotes::class, "create"]); // Create
SimpleRouter::get("/api/notes", [ControllerNotes::class, "read"]); // Read
SimpleRouter::patch("/api/notes", [ControllerNotes::class, "update"]); // Update
SimpleRouter::delete("/api/notes", [ControllerNotes::class, "delete"]); // Delete
#endregion

#region CRUD Users
SimpleRouter::put('/api/login', [ControllerUsers::class, 'login']); // Create a token

SimpleRouter::post("/api/users", [ControllerUsers::class, "create"]); // Create
SimpleRouter::get("/api/users", [ControllerUsers::class, "read"]); // Read
SimpleRouter::patch("/api/users", [ControllerUsers::class, "update"]); // Update
SimpleRouter::delete("/api/users", [ControllerUsers::class, "delete"]); // Delete
#endregion

#region CRUD Subject
SimpleRouter::post("/api/subject", [ControllerSubject::class, "create"]); // Create
SimpleRouter::get("/api/subject", [ControllerSubject::class, "read"]); // Read
#endregion