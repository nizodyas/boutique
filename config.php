<?php
define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/project/');
define('CART_COOKIE','asGhjKioL7dFv6ghij1');
define('CART_COOKIE_EXPIRE',time() + (86400 * 30));
define('TAXRATE',0.087);
define('CURRENCY', 'NRs.');
define('CHECKOUTMODE', 'TEST'); //change test to live when ready

if(CHECKOUTMODE =='TEST'){
	define('STRIPE_PRIVATE','sk_test_SHZhnvLzSQ72iNEVzq7rn4HA');
    define('STRIPE_PUBLIC', 'pk_test_ZTFvVUBOMqUiaiEZdHi4RUeu');

}

if(CHECKOUTMODE =='LIVE'){
	define('STRIPE_PRIVATE','');
    define('STRIPE_PUBLIC', '');
}
?>
