<?php
/**
 * Created by PhpStorm.
 * User: kiera
 * Date: 28/11/2018
 * Time: 02:50
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class Helpers
{

    public static function ParseOtherStaff($staff)
    {
        $staff = explode(' ', $staff);
        $list = '';
        foreach ($staff as $user) {
            $list .= '<a href="/staff/#staf' . self::UsernameToID($user) . '">' . htmlspecialchars($user) . '</a> ';
        }
        return $list;
    }

    public static function UsernameToID($name = null)
    {
        global $pdo;
        if ($name) {
            $stmt = $pdo->prepare('SELECT id, username FROM users WHERE username = :username');
            $stmt->bindValue(':username', $name, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();
            if ($user) {
                return $user->id;
            }
        }
        return false;
    }

    public static function IDToUsername($id = null)
    {
        global $pdo;
        if ($id) {
            $stmt = $pdo->prepare('SELECT id, username FROM users WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();
            if ($user) {
                return $user->username;
            }
        }
        return "Staff Not Found";
    }

    public static function APIResponse($message = null, $array = null, $code = null)
    {
        return json_encode(['response' => $array, 'code' => $code, 'message' => $message]);
    }

    public static function PusherSend($data, $channel, $event)
    {
        try {
            $pusher = new Pusher\Pusher(
                Config::$pusher['AUTH_KEY'],
                Config::$pusher['SECRET'],
                Config::$pusher['APP_ID'],
                Config::$pusher['DEFAULT_CONFIG']
            );
        } catch (\Pusher\PusherException $e) {
            return false;
        }

        try {
            $pusher->trigger($channel, $event, $data);
        } catch (\Pusher\PusherException $e) {
            self::addAuditLog("PUSHER_ERROR::" . $e->getMessage());
            echo "PUSHER ERROR: " . $e->getMessage();
        }
        return true;
    }

    public static function addAuditLog($content)
    {
        global $pdo;

        $user = new User;

        $ctx = ($user->isCommand() && !$user->isStaff()) ? 'PD_EMS_COMMAND' : 'admin';

        $stmt = $pdo->prepare('INSERT INTO audit_log (log_content, log_context, logged_in_user) VALUES (:content, :ctx, :liu)');
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':ctx', $ctx, PDO::PARAM_STR);
        $stmt->bindValue(':liu', $user->info->id, PDO::PARAM_STR);
        $stmt->execute();
    }

    public static function viewingPublicPage()
    {
        $publicurls = [
            '/errors/awaitingapproval',
            '/errors/nostaff',
            '/passport',
            '/holdingpage',
            '/purchases/activate'
        ];
        foreach ($publicurls as $url) {
            if (strpos($_SERVER['REQUEST_URI'], $url) !== false) return true;
        }
        return false;
    }

    public static function fixPlayersForCase($number, $errors)
    {
        global $pdo;

        $errors = json_encode($errors);

        $stmt = $pdo->prepare("INSERT INTO case_players (case_id, type, name, guid) VALUES (:id, :type, :nm, :guid)");
        $stmt->bindValue(":id", $number);
        $stmt->bindValue(":type", 'Failed');
        $stmt->bindValue(":nm", 'Please Give The Case ID to Kieran.');
        $stmt->bindValue(":guid", 'Undefined');
        if (!$stmt->execute()) {
            self::addAuditLog("CRITICAL_ERROR::Failed To Add Player To Report " . json_encode($stmt->errorinfo()));
        } else {
            self::addAuditLog("CRITICAL_ERROR_ERROR::{$errors}");
            self::addAuditLog("CRITICAL_ERROR_FiX::Corrected add player, (CASEID: {$number}) ");
        }
    }

    public static function sanitizeUserDataArray($users)
    {
        foreach ($users as $key => $user) {
            $users[$key] = self::sanitizeUserData($user);
        }
        return $users;
    }

    public static function sanitizeUserData($user)
    {
        unset($user->password);
        unset($user->email);
        unset($user->unique_id);
        return $user;
    }

    public static function getPlayersFromCase($caseid)
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM case_players WHERE case_id = :id');
        $stmt->bindValue(':id', $caseid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function parsePlayers(array $players)
    {
        foreach ($players as $player) {
            $name = $player->name;
            $player->name = "<a href='https://panel.arma-life.com/search?type=players&query={$name}'>{$name}</a>";
        }
        return $players;
    }

    public static function parsePunishment($p)
    {
        $comments = nl2br($p->comments);
        return "<div class='punishment_report'><span class='player'>{$p->player}'s Punishment Report</span> <span class='points'>{$p->points} Points</span><p class='rules'>Rules Broken: {$p->rules}</p><p class='comments'>Staff Comment: {$comments}</p></div>";
    }

    public static function parseBan($b)
    {
        $length = ($b->length) ? $b->length . ' Days' : 'Permanent';

        $ts = self::zeroOneToYesNo($b->teamspeak);
        $ig = self::zeroOneToYesNo($b->ingame);
        $wb = self::zeroOneToYesNo($b->website);
        $tsClass = ($b->teamspeak) ? 'punishmentincase' : 'typeofreport';
        $igClass = ($b->ingame) ? 'punishmentincase' : 'typeofreport';
        $wbClass = ($b->website) ? 'punishmentincase' : 'typeofreport';

        $custom_classes = "";
        $ban_expired_text = " ~ <a onclick='markBanExpired({$b->id}, {$b->case_id})'>Expire Ban</a>";

        if ($b->length != 0) {
            if (time() > strtotime(date("Y-m-d", strtotime($b->timestamp)) . ' + ' . $b->length . ' days')) {
                $custom_classes .= " ban_expired";
                $ban_expired_text = " ~ Ban Expired";
            }
        }

//        $date = strtotime(date("Y-m-d", strtotime($b->timestamp)) . ' + ' . $b->length . ' days');

        return "<div class='punishment_report{$custom_classes}'><span class='player'>{$b->player}'s Ban Report {$ban_expired_text}</span> <span class='points'>{$length}</span>
            <div style='padding: 10px 0 0;'>
                <span class='{$tsClass}'>Teamspeak Ban: {$ts}</span>
                <span class='{$igClass}'>Ingame Ban: {$ig}</span>
                <span class='{$wbClass}'>Website Ban: {$wb}</span>
            </div>
        </div>";
    }

    public static function zeroOneToYesNo($n)
    {
        return ($n) ? 'Yes' : 'No';
    }

    public static function checkCaseHasPunishment($id)
    {
        global $pdo;

        $stmt = $pdo->prepare('SELECT COUNT(*) AS reports FROM punishment_reports WHERE case_id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $returned = ($stmt->fetch()->reports > 0) ? true : false;

        return $returned;
    }

    public static function checkCaseHasBan($id)
    {
        global $pdo;

        $stmt = $pdo->prepare('SELECT COUNT(*) AS reports FROM ban_reports WHERE case_id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $returned = ($stmt->fetch()->reports > 0) ? true : false;

        return $returned;
    }

    public static function IDToStaff(int $id)
    {
        return new User($id);
    }

    public static function getBMMakeReservedRequest($steamid, $licenseKey, $orderID, $username)
    {
        self::addAuditLog($steamid);
        try {
            $tok = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlbiI6IjNhNDg0MTE4MTlmNGFkMGYiLCJpYXQiOjE1NDc5ODkxODIsIm5iZiI6MTU0Nzk4OTE4MiwiaXNzIjoiaHR0cHM6Ly93d3cuYmF0dGxlbWV0cmljcy5jb20iLCJzdWIiOiJ1cm46dXNlcjozNTM4NyJ9.J3PMaTvGOLJLAYlYgsz-6h4ir5IBzCqehU75l7zTcgM";

            $headers = [
                'Authorization' => 'Bearer ' . $tok,
                'Accept' => 'application/json'
            ];

            $body = [
                "data" => [
                    [
                        "attributes" => [
                            "identifier" => $steamid,
                            "type" => "steamID"
                        ],
                        "type" => "identifier"
                    ]
                ]
            ];

            $body = Unirest\Request\Body::Form($body);

            $bmPlayerInfo = Unirest\Request::post('https://api.battlemetrics.com/players/match', $headers, $body);

            return '{
    "data": {
        "attributes": {
            "expires": "'.substr(date("c", time() + 60 * 60 * 24 * 30), 0, 19) .'.962Z",
            "identifiers": [
                {
                    "type": "steamID",
                    "identifier": "'.$steamid.'",
                    "manual": true
                }
            ]
        },
        "relationships": {
            "player": {
                "data": {
                    "type": "player",
                    "id": "'.$bmPlayerInfo->body->data[0]->relationships->player->data->id.'"
                }
            },
            "servers": {
                "data": [
                    {
                        "type": "server",
                        "id": "2921049"
                    }
                ]
            },
            "organization": {
                "data": {
                    "type": "organization",
                    "id": "8030"
                }
            },
            "user": {
                "data": {
                    "type": "user",
                    "id": "'.$bmPlayerInfo->body->data[0]->relationships->player->data->id.'"
                }
            }
        },
        "type": "reservedSlot"
    }
}';

//            return [
//                "data" => [
//                    "attributes" => [
////                        "expires" => substr(date('c', time() + 60 * 60 * 24 * 30), 0, 19) . ".000Z",
//                        "expires" => "2019-02-20T13:00:00.000Z",
//                        "identifiers" => [
//                            [
//                                "type" => "steamID",
//                                "identifier" => (int) $steamid,
//                                "manual" => true
//                            ]
//                        ]
//                    ],
//                    "relationships" => [
//                        "player" => [
//                            "data" => [
//                                "type" => "player",
//                                "id" => $bmPlayerInfo->body->data[0]->relationships->player->data->id
//                            ]
//                        ],
//                        "servers" => [
//                            "data" => [
//                                [
//                                    "type" => "server",
//                                    "id" => "2921049"
//                                ]
//                            ]
//                        ],
//                        "organization" => [
//                            "data" => [
//                                "type" => "organization",
//                                "id" => "8030"
//                            ]
//                        ],
//                        "user" => [
//                            "data" => [
//                                "type" => "user",
//                                "id" => $bmPlayerInfo->body->data[0]->relationships->player->data->id
//                            ]
//                        ]
//                    ],
//                    "meta" => [
//                        "licenseKey" => $licenseKey,
//                        "userDisplayName" => $username,
//                        "orderID" => $orderID
//                    ],
//                    "type" => "reservedSlot"
//                ]
//            ];
        } catch (\Unirest\Exception $e) {
            self::addAuditLog('ERROR::Unirest Error [Get Player SteamID Match] ' . $e->getMessage());
            return false;
        }

    }
}