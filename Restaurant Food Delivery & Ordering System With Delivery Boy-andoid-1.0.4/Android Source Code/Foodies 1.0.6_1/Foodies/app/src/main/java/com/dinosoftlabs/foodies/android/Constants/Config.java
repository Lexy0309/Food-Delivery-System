package com.dinosoftlabs.foodies.android.Constants;

/**
 * Created by Belal on 11/14/2015.
 */
public class Config {


    public static String baseURL = "http://192.168.100.25/mobileapp_api/api/";
   public static String imgBaseURL = "http://192.168.100.25/mobileapp_api/";



    //URL to our login.php file
    public static final String LOGIN_URL = baseURL+"login";

    public static final String showCountries = baseURL+"showCountries";
    public static final String Verify_URL = baseURL+"verifyPhoneNo";
    public static final String SignUp_URL = baseURL+"registerUser";

    public static final String SHOW_RESTAURANTS = baseURL+"showRestaurants";
    public static final String SHOW_RESTAURANT_MENU = baseURL+"showRestaurantsMenu";
    public static final String ADD_FAV_RESTAURANT = baseURL+"addFavouriteRestaurant";
    public static final String SHOW_FAV_RESTAURANT = baseURL+"showFavouriteRestaurants";

    public static final String SHOW_SLIDER = baseURL+"showAppSliderImages";
    public static final String SHOW_ORDERS = baseURL+"showOrders";
    public static final String SHOW_DEALS = baseURL+"showDeals";

    public static final String CHANGE_PASSWORD = baseURL+"changePassword";
    public static final String EDIT_PROFILE = baseURL+"editUserProfile";

    public static final String FORGOT_PASSWORD = baseURL+"forgotPassword";

    public static final String ADD_PAYMENT_METHOD = baseURL+"addPaymentMethod";
    public static final String GET_PAYMENT_METHID = baseURL+"getPaymentDetails";
    public static final String ADD_DELIVERY_ADDRESS = baseURL+"addDeliveryAddress";
    public static final String GET_DELIVERY_ADDRESES = baseURL+"showDeliveryAddresses";

    public static final String SHOW_COUNTRIES_LIST = baseURL+"showCountries";
    public static final String SHOW_ORDER_DETAIL = baseURL+"showOrderDetail";
    public static final String SHOW_RIDER_LOCATION_AGAINST_LATLONG = baseURL+"showRiderLocationAgainstOrder";

    public static final String SHOW_MENU_EXTRA_ITEM = baseURL+"showMenuExtraItems";
    public static final String SHOE_TOTAL_RATINGS = baseURL+"showRestaurantRatings";
    public static final String SHOW_RESTAURANT_DEALS = baseURL+"showRestaurantDeals";


    public static final String SHOW_REST_AGAINST_SPECIALITY = baseURL+"showRestaurantsAgainstSpeciality";
    public static final String SHOW_REST_SPECIALITY_LIST = baseURL+"showRestaurantsSpecialities";

    public static final String VERIFY_COUPAN = baseURL+"verifyCoupon";
    public static final String PLACE_ORDER = baseURL+"placeOrder";

    public static final String ORDER_DEAL = baseURL+"orderDeal";


    /// All Riders Api

    public static final String SHOW_RIDER_ORDERS = baseURL+"showRiderOrders";
    public static final String SHOW_RIDER_TRACKING = baseURL+"showRiderTracking";
    public static final String TRACK_RIDER_STATUS = baseURL+"trackRiderStatus";
    public static final String SHOW_RIDER_TIMING = baseURL+"showRiderTimingBasedOnDate";

    public static final String ADD_LOCATIONS = baseURL+"addRiderLocation";
    public static final String ADD_RIDER_TIMING = baseURL+"addRiderTiming";
    public static final String SHOW_RIDER_ORDER_BASE_ONDATE= baseURL+"showRiderOrdersBasedOnDate";
    public static final String SHOW_UP_COMMING_RIDER_SHIFTS = baseURL+"showUpComingRiderShifts";
    public static final String ONLINE_STATUS = baseURL+"showUserOnlineStatus";
    public static final String UPDATE_RIDER_STATUS = baseURL+"checkIn";
    public static final String Accept_RIDER_ORDER=baseURL+"updateRiderOrderStatus";
    public static final String UPDATE_RIDER_SHIFT_STATUS = baseURL+"updateRiderShiftStatus";
    public static final String DELETE_RIDER_TIMING = baseURL+"deleteRiderTiming";
    public static final String SHOW_USER_ONLINE_STATUS = baseURL+"showUserOnlineStatus";
    public static final String SHOW_RIDER_REVIEW = baseURL+"showRiderRatings";



    /// All Admin Apis

    public static final String SHOW_ORDER_BASED_RESTAU = baseURL+"showOrdersBasedOnRestaurant";
    public static final String ACCEPT_DECLINE_STATUS = baseURL+"updateRestaurantOrderStatus";
    public static final String SHOW_REST_COMPLETE_ORDER = baseURL+"showRestaurantCompletedOrders";


    // Reviews API

    public static final String AddRestaurantRating = baseURL+"addRestaurantRating";
    public static final String GiveRatingsToRider = baseURL+"giveRatingsToRider";



    // Api For GetPlaces

    public static final String GET_CITY_BOUNDRIES = "http://maps.google.com/maps/api/geocode/json?address=";



    //Keys for email and password as defined in our $_POST['key'] in login.php
    public static final String KEY_EMAIL = "email";
    public static final String KEY_PASSWORD = "password";
    public static final String DEVICE_TOCKEN = "device_token";

    //If server response is equal to this that means login is successful
    public static final String LOGIN_SUCCESS = "200";

    //Keys for Sharedpreferences
    //This would be the name of our shared preferences
    public static final String SHARED_PREF_NAME = "myloginapp";

    //This would be used to store the email of current logged in user
    public static final String EMAIL_SHARED_PREF = "email";

    //We will use this to store the boolean in sharedpreference to track user is loggedin or not
    public static final String LOGGEDIN_SHARED_PREF = "loggedin";



    // global topic to receive app wide push notifications
    public static final String TOPIC_GLOBAL = "global";

    // broadcast receiver intent filters
    public static final String REGISTRATION_COMPLETE = "registrationComplete";
    public static final String PUSH_NOTIFICATION = "pushNotification";

    // id to handle the notification in the notification tray
    public static final int NOTIFICATION_ID = 100;
    public static final int NOTIFICATION_ID_BIG_IMAGE = 101;

    public static final String SHARED_PREF = "ah_firebase";

}
