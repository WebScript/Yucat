<?php
/**
 * @author Bloodman Arun
 * @copyright Copyright By Bloodman (c) 2011
 * @link http://gshost.eu
 * @site Language - Users.lang
 */

$user = db::init()->uQuery(db::VIEWS, db::USERS, UID);

if(empty($lang) || !is_Array($lang)) $lang = Array();

$add = Array(
    'THEME_DIR' => '/styles/'.STYLE.'/'.'theme/',
    'TEMPLATE_DIR' => '/styles/'.STYLE.'/'.'template/',
    'STYLE' => STYLE,
    'UID' => UID,
    'LOGIN' => $user[db::USERS_LOGIN],
    'RANK' => $user[db::USERS_RANK],

    'FIRSTNAME' => $user[db::USERS_FIRSTNAME],
    'LASTNAME' => $user[db::USERS_LASTNAME],
    'ADDRESS' => $user[db::USERS_ADDRESS],
    'CITY' => $user[db::USERS_CITY],
    'POSTCODE' => $user[db::USERS_POSTCODE],
    'TEL' => $user[db::USERS_TELEPHONE],

    'CREDIT' => Round($user[db::USERS_CREDIT], 3),
    'LANGUAGE' => $user[db::USERS_LANGUAGE],
    'AVATAR' => $user[db::USERS_AVATAR],
    'EMAIL' => $user[db::USERS_EMAIL],
    'WEBSITE' => $user[db::USERS_WEBSITE],
    'DB_UIP' => $user[db::USERS_IP],
    'UIP' => UIP,
    'LAST_LOGIN' => Date::getDate($user[db::USERS_LL]).' '.Date::getTime($user[db::USERS_LL]),

    'CFG_WEB' => CFG_WEBSITE,
    'COST_SAMP' => COST_SAMP,

    'T_COPYRIGHT' => 'Copyright &copy; 2011, <strong>UWAP '.CFG_VERSION.'</strong> by <strong>Bloodman Arun</strong>'
);
$lang = Array_Merge($lang, $add);
?>







