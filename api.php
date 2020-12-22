<?php

/*  Allows for the latest COVID-19 Swab Data to be posted automatically to Twitter.
 *  Twitter account: @COVID19DataIE // Data source: covid19.shanehastings.eu
 *  @author Shane Hastings
 *  @version 0.1
 */

require_once "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'ENTER_YOUR_CONSUMER_KEY');
define('CONSUMER_SECRET', 'ENTER_YOUR_CONSUMER_SECRET');
define('ACCESS_TOKEN', 'ENTER_YOUR_ACCESS_TOKEN');
define('ACCESS_TOKEN_SECRET', 'ENTER_YOUR_ACCESS_TOKEN_SECRET');


/*  Posts a tweet to the @COVID19DataIE twitter account with the data given
 *  in the $tweetContent variable.
 *
 * @param tweetContent - The content of the tweet that is being sent.
 */

function sendTweet($tweetContent)
{

    /* Commented out for testing */

    //$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

    //$status = $tweetContent;
    //$post_tweets = $connection->post("statuses/update", ["status" => $status]);

    /* We will create / write a file called lastTweet.txt containing only the date of when the tweet was sent.
        This allows us to check if a tweet has been sent that day or not.

    e.g. Once a tweet is sent, the file will contain "2020-12-21" (or equivalent date). This can then be checked
    by the checkForUpdate() function.
    */
    $tweetDateFile = fopen("lastTweet.txt", "w");
    $dateToday = date("Y-m-d");
    fwrite($tweetDateFile, $dateToday);
    fclose($tweetDateFile);
}

/*  Queries the API at covid19.shanehastings.eu for a cleansed version of the latest swab data.
 *  Returns the data as an array.
 */
function getLatestSwabs()
{

    $swabJsonAPI = 'https://covid19.shanehastings.eu/api/swabs/json/';
    $swabJSON = file_get_contents($swabJsonAPI);
    $dataObject = json_decode($swabJSON, true);
    return $dataObject;
}

/*  Generates the Tweet content by querying the JSON API and wrapping the data
 *  into the correct format. Returns a string with the formatted text.
 *
 *  Sample tweet: https://twitter.com/COVID19DataIE/status/1341044045519446018
 */
function generateTweetContent()
{
    /* Get and assign swab data from getLatestSwabs() func to a variable. */
    $swabData = getLatestSwabs();

    /* Formatting the tweet string */
    $swabDate = date("l, F jS Y", strtotime($swabData['date']));
    $tweetString = $swabData['positive_swabs'] . " positive swabs, " . $swabData['positivity_rate'] . " positivity on " . number_format($swabData['swabs_24hr']) . " swabs.\n- " . $swabDate . "\n\n#COVID19Ireland";
    //echo $tweetString;
    return $tweetString;

}

/*  This function checks the JSON API to see if new data has been uploaded.
 *  The methodology for this is that it checks if the "date" attribute returned from the JSON API
 *  is equal to today's date. If it is, a tweet is generated and sent out.
 */
function checkForUpdate()
{

    /* Query the JSON API and assign to local func variable */
    $swabData = getLatestSwabs();
    $swabDateLatest = $swabData['date'];
    $dateToday = date("Y-m-d");
    $lastTweetDate = readDateFromFile();

    /* Case 1a: The dates match, so swab data for today has been released.*/
    if ($swabDateLatest == $dateToday) {
        echo "The dates match. Now check if a tweet has already been sent.\n";
        /* Case 1b: Check if a tweet has already been sent today. */
        if ($lastTweetDate == $dateToday) {
            echo "\nA tweet has already been sent today. Do nothing";
        } else if ($lastTweetDate != $dateToday) {
            /* THIS SENDS THE TWEET! */
            echo "\nA tweet has not yet been sent. Sending now \n";
            sendTweet(generateTweetContent());

            echo generateTweetContent();
        }
    } /* Case 2a: The dates don't match. Swab data hasn't been updated OR it is a Sunday / non reporting day. Do nothing.*/
    else {
        echo "The dates don't match. Keep checking.\n";
        echo "Latest swab date: " . $swabData['date'];
        echo "\nCurrent date: " . $dateToday;
    }

}

/*  Read the contents of the lastTweet.txt file. This file contains the date (YYYY-MM-DD) of the last successful
    tweet made for swabs. This ensures that swab data is only posted once to the Twitter account.
 */
function readDateFromFile()
{

    $dateFileSrc = fopen("lastTweet.txt", "r") or die("Unable to open file!");
    $dateFile = fread($dateFileSrc, filesize("lastTweet.txt"));
    fclose($dateFileSrc);
    return $dateFile;

}

checkForUpdate();

