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

### Stateless Requests  

Within your magento instance you can set a flag in your controllers predispatch such as 

```
	public function preDispatch()
	{
		$this->setFlag('', self::FLAG_NO_START_SESSION, 1);
		$this->setFlag('', self::FLAG_NO_COOKIES_REDIRECT, 1);
		parent::preDispatch();
		return $this;
	}
```

However, other actions and observers may erroneously instantiate the session. This module hooks into a predispatch method to ensure no session is used or cookies are written when this flag is set.

## Tests

Look at the `tests/README.md` for full information.
