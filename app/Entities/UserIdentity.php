<?php

namespace App\Entities;

use CodeIgniter\Shield\Entities\UserIdentity as ShieldUserIdentity;

/**
 * Class UserIdentity
 *
 * Represents a single set of user identity credentials.
 * For the base Shield system, this would be one of the following:
 *  - password
 *  - reset hash
 *  - access token
 *
 * This can also be used to store credentials for social logins,
 * OAUTH or JWT tokens, etc. A user can have multiple of each,
 * though a Authenticator may want to enforce only one exists for that
 * user, like a password.
 */
class UserIdentity extends ShieldUserIdentity
{
}
