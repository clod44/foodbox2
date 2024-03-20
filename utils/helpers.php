<?php
/**
 * Reads a value from the session.
 * @example $currentUser = SESSION_READ('user');
 * @example $currentUserEmail = SESSION_READ(['user','email']);
 *
 * @param string|array $keys    The key(s) to read from the session.
 * @param mixed        $default The default value to return if the key does not exist.
 *
 * @return mixed The value from the session or the default value.
 * 
 */
function SESSION_READ($keys, $default = null)
{
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    if (!is_array($keys))
        $keys = [$keys];

    $key = array_shift($keys);

    if (isset ($_SESSION[$key])) {
        $value = $_SESSION[$key];

        if (empty ($keys)) {
            return $value;
        }

        if (is_array($value)) {
            return SESSION_READ($keys, $value);
        } else {
            // If the value is not an array but keys are remaining, return the default value
            return $default;
        }
    }

    return $default;
}



/**
 * Writes a value to the session.
 * @example SESSION_WRITE('user', newUser); //with password inside etc
 * @example SESSION_WRITE(['user', 'password'], newPassword);
 *
 * @param string|array $keys  The key(s) to write to in the session.
 * @param mixed        $value The value to write.
 *
 * @return void
 * 
 */
function SESSION_WRITE($keys, $value)
{
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    if (!is_array($keys))
        $keys = [$keys];

    $key = array_shift($keys);

    if (!empty ($keys)) {
        if (!isset ($_SESSION[$key]) || isset ($keys[0])) {
            $_SESSION[$key] = [];
        }
        SESSION_WRITE($keys, $value);
    } else {
        $_SESSION[$key] = $value;
    }
}

/**
 * Unsets a single key and its content if there are any
 * @example SESSION_UNSET('user');
 * @param string    $key  The key to unset
 * @return void
 */
function SESSION_UNSET_KEY($key)
{
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    unset($_SESSION[$key]);
}
?>