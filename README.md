phplibphone
===========

Parse E.164 compliant phone numbers

Identifies country and area codes (and names) via loaded lookup libraries.

To create a parser inject a country code library and optionally specify a
default country code used when parsing numbers with no explicit country code.
To create a parser with country names i english and 46 as the default country
code use:

    use iio\phplibphone\Number;
    use iio\phplibphone\Library\Countries;
    use iio\phpcountry\Country as PhpCountry;

    $phpCountry = new PhpCountry;
    $phpCountry->setLang('en');

    $parser = new Number($phpCountry, 46);

Register area code libraries to enable phplibphone to correctly identify area
codes.

    use iio\phplibphone\Library\AreasSeSv;
    $parser->setAreaLib(new AreasSeSv);

Parse numbers with the `setRaw()` method. Spaces and unknown characters are
ignored.

    $parser->setRaw('087740212');

Or equivalently

    $parser->setRaw('+4687740212');

Retrieve parsed information

    echo $parser->getCountryCode();
    //46
    
    echo $parser->getNationalDestinationCode();
    //8

    echo $parser->getAreaCode();
    //08
    
    echo $parser->getSubscriberNumber();
    //7740212

    echo $parser->getE164();
    //+4687740212
    
    echo $parser->getInternationalFormat();
    //+46 8 774 02 12
    
    echo $parser->getNationalFormat();
    //08-774 02 12
    
    echo $parser->format();
    //08-774 02 12
    // If country code equals default country code national format is used.
    // Else international.

    echo $parser->getCountry();
    // Sweden
    
    echo $parser->getArea();
    // Stockholm
