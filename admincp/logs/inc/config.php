<?php

# Player Analytics config file
# If you have suggestions or you found a bug -> contact me: thechaoscoder+player-analytics[at]gmail.com (replace [at] with @, bot protection)
# or open an issue here https://github.com/theChaosCoder/player_analytics



//Set encoding
ini_set('default_charset', 'utf-8');

$Title = "Player Analytics";
$Show_Max_Countries = 10; # Top 10 Countries
$hide_inactive_servers_days = 0; # Hide servers that are 'inactive' since X Days

const MUST_LOG_IN = false;
const STEAM_APIKEY  = 'AD9318F7F072C5407FB63F2D743C15EC'; # add your key (optional)

# optional - replace ip with your server name
$server_names = [
    "185.107.96.138:27015"  => "AmX Zombie",
    //"185.107.96.231:27015"  => "Chew Chew Train - 24/7 ChewChew",
];

# A name that will appears in nav below the ip
# Only usefull if you don't want to use server_names
$server_sub_names = [
    #asd
];

# Replace flags like z, bce with a name like VIP, Admin etc.
$staff_group_names = [
    'z'                         =>'Normal',
    'bp'                        =>'VIP',
    'bipt'                      =>'Super VIP',
    'bcdeijptuv'                =>'Admin',
    'abcdefijptuv'              =>'Super Admin',
    'abcdefijprtuv'             =>'Dev/Demo Checker',
    'abcdefijprstuv'            =>'Server Manager',
    'abcdefghijmpqrstuv'        =>'Moderator',
    'abcdefghijkmpqrstuv'       =>'Head Moderator',
    'abcdefghijklmnpqrstuv'     =>'Administrator',
    'abcdefghijklmnopqrstuvy'   =>'Owner'
];


# Show only records with the following flags:
$staff_whitelist = [
    #"z",
    #"abc",
    'abcdefijprstuv',           // server manager 
    'abcdefghijmpqrstuv',       // moderator
    'abcdefghijkmpqrstuv',      // head mod
    'abcdefghijklmnpqrstuv',    // administrator
    'abcdefghijklmnopqrstuvy'   // owner
];
# You can not combine white and black list!
# Hide records with the following flags:
$staff_blacklist = [
    #"z",
    #"abc",
];
