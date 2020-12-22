
![Banner](https://i.imgur.com/Rl3wFFu.png)

# Twitter-AutoPost-for-COVID-Data
Automatically post the latest COVID-19 swab data for Ireland to a designated Twitter account. 

This was created so that I could have a cron script that would query an API for the latest COVID data in Ireland and then post that to Twitter. Currently, it posts the latest swab data because this is published first and not reported by any media outlets.

Posting case data is a bit pointless since this is released by the media before the official API is even updated (often by hours). But; I'll probably add this in eventually.

# Requirements

- PHP 7.3.0+
- [TwitterOAuth](https://github.com/abraham/twitteroauth)
- A [Twitter Developer account](https://developer.twitter.com/en/apply-for-access)

# Crontab

    * * * * * php /path/to/api.php >> /path/to/logfile


# Sample Output
The output post on Twitter will be something similar to this. Currently, a simple text tweet is sent however I want to add support for case/swab graphs in the future.

![Sample Tweet](https://i.imgur.com/6BvTrn8.png)
*Obviously, the twitter account is not **actually** verified.*

# Data Source

The API queried by this repo is my own, which is a cleansed version of the [LaboratoryLocalTimeSeriesHistoricView](https://covid19ireland-geohive.hub.arcgis.com/datasets/f6d6332820ca466999dbd852f6ad4d5a_0/) dataset from [*Ireland's COVID-19 Data Hub*](https://covid19ireland-geohive.hub.arcgis.com/). 

You can view my dashboard [here](https://covid19.shanehastings.eu/api/swabs/).
