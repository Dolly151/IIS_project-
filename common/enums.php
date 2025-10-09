<?php

enum PermissionLevel: int {
    case GUEST = 0;
    case ADMIN = 1;
    case STUDENT = 2;
    case LECTOR = 3;
    case GARANT = 4;
    case ANY = 5;
}

enum RequestType: int {
    case GARANT_REQUEST = 0;
    case COURSE_REGISTRATION = 1;

}