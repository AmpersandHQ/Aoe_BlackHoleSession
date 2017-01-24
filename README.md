# AOE_BlackHoleSession

Authors:
- [Fabrizio Branca](https://twitter.com/fbrnc)
- [Luke Rodgers](https://twitter.com/lukerodgers90)

See: https://github.com/colinmollenhour/Cm_RedisSession/issues/91

Bots (including load balancers and reverse proxies) will create many sessions that will never be used again.
Instead we're detecting them based on the user agent and will skip creating a real session.

## Configuration

## Sessionless Bots
Add this to your local.xml file:

```
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <global>
        [...]
        <aoeblackholesession>
            <bot_regex><![CDATA[/^elb-healthchecker/i]]></bot_regex>
        </aoeblackholesession>
        [...]
    </global>
</config>
```

### Sessionless Requests  
You can define certain request URIs as being sessionless, this is particularly useful for ajax requests which do not vary content by customer (Eg. ajax stock request).  

Add the following to your local.xml file to make all requests to `some/path/here` and `another/different/path` sessionless. 

``` 
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <global>
        [...]
        <aoeblackholesession>
            <uri_regex><![CDATA[^(some\/path\/here|another\/different\/path)$^]]></uri_regex>
        </aoeblackholesession>
        [...]
    </global>
</config>
```

## Tests

Look at the `tests/README.md` for full information.
