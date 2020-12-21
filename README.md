
# Twitter-AutoPost-for-COVID-Data
Automatically post the latest COVID-19 swab data for Ireland to a designated Twitter account. 

This was created so that I could have a cron script that would query an API for the latest COVID data in Ireland and then post that to Twitter. Currently, it posts the latest swab data because this is published first and not reported by any media outlets.

Posting case data is a bit pointless since this is released by the media before the official API is even updated (often by hours). But; I'll probably add this in eventually.

# Requirements

- PHP
- [TwitterOAuth](https://github.com/abraham/twitteroauth)
- A [Twitter Developer account](https://developer.twitter.com/en/apply-for-access)

# Sample Output
The output post on Twitter will be something similar to this. Currently, a simple text tweet is sent however I want to add support for case/swab graphs in the future.

![Sample Tweet](https://i.imgur.com/6BvTrn8.png)
*Obviously, the twitter account is not **actually** verified.*
