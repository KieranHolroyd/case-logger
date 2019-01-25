<?php
/**
 * Created by PhpStorm.
 * User: kiera
 * Date: 28/11/2018
 * Time: 02:50
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class Config
{
    // Choose a name for your community
    public static $name = 'Arma-Life';

    // SQL Settings
    // Panel SQL Storage
    public static $sql = [
        'host' => 'localhost',
        'user' => 'armalife_Kieran',
        'pass' => '#9@GH0LI*@y9x7AKHe5$2SyTC%H^mv3',
        'name' => 'armalife_staff.arma-life.com'
    ];

    // Game SQL Settings
    public static $enableGamePanel = true;

    public static $gameSql = [
        'host' => '142.44.143.176',
        'user' => 'kieran-panel',
        'pass' => '787ymC3UF2LaZGWZ',
        'name' => 'armalife'
    ];

    // Staff Teams Settings
    public static $teams = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        null => 'Unassigned Members',
        6 => 'Support Team',
        100 => 'SMT',
        500 => 'Development Team',
    ];

    // Staff Ranks Settings
    public static $ranks = [
        1 => 'Community Manager',
        10 => 'Head Administrator',
        20 => 'Senior Administrator',
        30 => 'Administrator',
        40 => 'Moderator',
        50 => 'Trial Staff',
        60 => 'Support Member'
    ];

    // Pusher Config
    public static $pusher = [
        'AUTH_KEY' => '123979dbead391bef050',
        'SECRET' => 'e23290c59594f454584e',
        'APP_ID' => '653477',
        'DEFAULT_CONFIG' => [
            'cluster' => 'eu',
            'useTLS' => true
        ]
    ];
}