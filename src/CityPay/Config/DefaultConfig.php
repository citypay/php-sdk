<?php
namespace CityPay\Config;

/**
 * The DefaultConfig class extends a superclass derived from the RuntimeConfig
 * abstract class to import the implementations of certain static functions
 * used within the API to determine default settings for the API.
 * 
 * The superclass DefaultConfig is extended from determines the default
 * behaviour of the API.
 * 
 * For development environments, DefaultConfig should extend the Development
 * class; whereas for production environments, DefaultConfig should extend
 * the Production class.
 */
class DefaultConfig
    extends Development
{

}
